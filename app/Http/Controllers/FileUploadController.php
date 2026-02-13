<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;

class FileUploadController extends Controller
{
    /**
     * Upload image file (admin only)
     * Types: material, reference, avatar
     */
    public function uploadImage(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'image' => 'required|image|mimes:jpeg,jpg,png|max:2048', // 2MB
            'type' => 'required|in:material,reference,avatar'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $file = $request->file('image');
            $type = $request->input('type');

            // Generate unique filename
            $uuid = Str::uuid();
            $timestamp = time();
            $extension = $file->getClientOriginalExtension();
            $filename = "{$type}_{$timestamp}_{$uuid}.{$extension}";

            // Determine storage path based on type
            $storagePath = match ($type) {
                'material' => 'public/materials',
                'reference' => 'public/references',
                'avatar' => 'public/avatars',
                default => 'public/uploads'
            };

            // Resize and compress image using GD
            $image = $this->resizeAndCompress($file, $type);

            // Store file
            $path = $storagePath . '/' . $filename;
            Storage::put($path, $image);
            $url = Storage::url($path);

            return response()->json([
                'success' => true,
                'message' => 'Image uploaded successfully',
                'data' => [
                    'url' => $url,
                    'filename' => $filename,
                    'type' => $type
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to upload image: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Resize and compress image using GD library
     */
    private function resizeAndCompress($file, $type)
    {
        // Load image
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

        // Determine target dimensions
        if ($type === 'reference' || $type === 'avatar') {
            // Square crop
            $targetWidth = 1024;
            $targetHeight = 1024;
        } else {
            // Maintain aspect ratio, max width 1920
            $maxWidth = 1920;
            if ($origWidth > $maxWidth) {
                $targetWidth = $maxWidth;
                $targetHeight = (int)($origHeight * ($maxWidth / $origWidth));
            } else {
                $targetWidth = $origWidth;
                $targetHeight = $origHeight;
            }
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
            imagepng($newImage, null, 8); // Compression level 8
        } else {
            imagejpeg($newImage, null, 85); // Quality 85%
        }
        $imageData = ob_get_clean();

        // Clean up
        imagedestroy($sourceImage);
        imagedestroy($newImage);

        return $imageData;
    }

    /**
     * Delete uploaded file (admin only)
     */
    public function deleteImage(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'url' => 'required|string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $url = $request->input('url');
            $path = str_replace('/storage/', 'public/', parse_url($url, PHP_URL_PATH));

            if (Storage::exists($path)) {
                Storage::delete($path);

                return response()->json([
                    'success' => true,
                    'message' => 'Image deleted successfully'
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'File not found'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete image: ' . $e->getMessage()
            ], 500);
        }
    }
}
