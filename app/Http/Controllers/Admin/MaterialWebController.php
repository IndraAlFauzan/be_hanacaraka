<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Material;
use App\Models\Stage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class MaterialWebController extends Controller
{
    public function index(Request $request)
    {
        $query = Material::with(['stage.level']);

        if ($request->filled('stage_id')) {
            $query->where('stage_id', $request->stage_id);
        }

        $materials = $query->orderBy('stage_id')->orderBy('order_index')->paginate(20);
        $stages = Stage::with('level')->orderBy('level_id')->orderBy('stage_number')->get();

        return view('admin.materials.index', compact('materials', 'stages'));
    }

    public function create()
    {
        $stages = Stage::with('level')->orderBy('level_id')->orderBy('stage_number')->get();
        return view('admin.materials.create', compact('stages'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'stage_id' => 'required|exists:stages,id',
            'title' => 'required|string|max:255',
            'content_text' => 'nullable|string',
            'content_markdown' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,jpg,png|max:2048',
            'order_index' => 'required|integer|min:1',
        ]);

        // Handle image upload
        if ($request->hasFile('image')) {
            \Log::info('File upload detected', [
                'hasFile' => $request->hasFile('image'),
                'isValid' => $request->file('image')->isValid(),
                'originalName' => $request->file('image')->getClientOriginalName(),
                'size' => $request->file('image')->getSize(),
                'mimeType' => $request->file('image')->getMimeType(),
                'tmpPath' => $request->file('image')->getPathname()
            ]);
            $validated['image_url'] = $this->uploadImage($request->file('image'));
        }

        Material::create($validated);
        return redirect()->route('admin.materials.index')
            ->with('success', 'Materi berhasil ditambahkan!');
    }

    public function edit(string $id)
    {
        $material = Material::with(['stage.level'])->findOrFail($id);
        $stages = Stage::with('level')->orderBy('level_id')->orderBy('stage_number')->get();
        return view('admin.materials.edit', compact('material', 'stages'));
    }

    public function update(Request $request, string $id)
    {
        $material = Material::findOrFail($id);

        $validated = $request->validate([
            'stage_id' => 'required|exists:stages,id',
            'title' => 'required|string|max:255',
            'content_text' => 'nullable|string',
            'content_markdown' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,jpg,png|max:2048',
            'order_index' => 'required|integer|min:1',
        ]);

        // Handle new image upload
        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($material->image_url) {
                $this->deleteImage($material->image_url);
            }
            $validated['image_url'] = $this->uploadImage($request->file('image'));
        }

        $material->update($validated);
        return redirect()->route('admin.materials.index')
            ->with('success', 'Materi berhasil diperbarui!');
    }

    public function destroy(string $id)
    {
        $material = Material::findOrFail($id);

        // Delete image if exists
        if ($material->image_url) {
            $this->deleteImage($material->image_url);
        }

        $material->delete();

        return redirect()->route('admin.materials.index')
            ->with('success', 'Materi berhasil dihapus!');
    }

    /**
     * Upload and resize image
     */
    private function uploadImage($file)
    {
        try {
            $timestamp = time();
            $uuid = Str::uuid();
            $extension = $file->getClientOriginalExtension();
            $filename = "material_{$timestamp}_{$uuid}.{$extension}";

            // Ensure directory exists
            $directory = storage_path('app/public/materials');
            if (!file_exists($directory)) {
                mkdir($directory, 0755, true);
            }

            // Full destination path
            $destinationPath = $directory . '/' . $filename;

            // Use native PHP move_uploaded_file as Laravel Storage is failing
            $tmpPath = $file->getPathname();

            \Log::info('Attempting file upload', [
                'tmpPath' => $tmpPath,
                'tmpExists' => file_exists($tmpPath),
                'tmpSize' => file_exists($tmpPath) ? filesize($tmpPath) : 0,
                'destinationPath' => $destinationPath,
                'directoryExists' => file_exists($directory),
                'directoryWritable' => is_writable($directory)
            ]);

            // Try move_uploaded_file first
            $moved = move_uploaded_file($tmpPath, $destinationPath);

            if (!$moved) {
                // If move_uploaded_file fails (might happen in non-upload context), try copy
                \Log::warning('move_uploaded_file failed, trying copy');
                $moved = copy($tmpPath, $destinationPath);
            }

            if (!$moved) {
                throw new \Exception('Failed to move uploaded file to: ' . $destinationPath);
            }

            // Verify file exists and has content
            if (!file_exists($destinationPath)) {
                throw new \Exception('File does not exist after move: ' . $destinationPath);
            }

            $fileSize = filesize($destinationPath);
            if ($fileSize === 0) {
                throw new \Exception('File is empty after move: ' . $destinationPath);
            }

            \Log::info('File uploaded successfully', [
                'destination' => $destinationPath,
                'size' => $fileSize
            ]);

            // Return full URL using asset helper
            return asset('storage/materials/' . $filename);
        } catch (\Exception $e) {
            \Log::error('Image upload failed: ' . $e->getMessage(), [
                'exception' => get_class($e),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);
            throw $e;
        }
    }

    /**
     * Delete image from storage
     */
    private function deleteImage($imageUrl)
    {
        // Extract filename from URL (handle both full URL and relative path)
        $path = parse_url($imageUrl, PHP_URL_PATH);
        $filename = basename($path);

        $storagePath = "public/materials/{$filename}";

        if (Storage::exists($storagePath)) {
            Storage::delete($storagePath);
        }
    }

    /**
     * Resize and compress image using GD library
     */
    private function resizeAndCompress($file)
    {
        $sourceImage = null;
        $mime = $file->getMimeType();

        if ($mime === 'image/jpeg' || $mime === 'image/jpg') {
            $sourceImage = imagecreatefromjpeg($file->getPathname());
        } elseif ($mime === 'image/png') {
            $sourceImage = imagecreatefrompng($file->getPathname());
        }

        if (!$sourceImage) {
            throw new \Exception('Failed to load image');
        }

        // Get original dimensions
        $origWidth = imagesx($sourceImage);
        $origHeight = imagesy($sourceImage);

        // Max width for material images
        $maxWidth = 1920;
        if ($origWidth > $maxWidth) {
            $targetWidth = $maxWidth;
            $targetHeight = (int)($origHeight * ($maxWidth / $origWidth));
        } else {
            $targetWidth = $origWidth;
            $targetHeight = $origHeight;
        }

        // Create new image
        $newImage = imagecreatetruecolor($targetWidth, $targetHeight);

        // Preserve transparency for PNG
        if ($mime === 'image/png') {
            imagealphablending($newImage, false);
            imagesavealpha($newImage, true);
        }

        // Resize
        imagecopyresampled(
            $newImage,
            $sourceImage,
            0,
            0,
            0,
            0,
            $targetWidth,
            $targetHeight,
            $origWidth,
            $origHeight
        );

        // Output to buffer
        ob_start();
        if ($mime === 'image/png') {
            imagepng($newImage, null, 8);
        } else {
            imagejpeg($newImage, null, 85);
        }
        $imageData = ob_get_clean();

        // Clean up
        imagedestroy($sourceImage);
        imagedestroy($newImage);

        return $imageData;
    }
}
