<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class FileUploadService
{
    /**
     * Upload image file to storage
     *
     * @param UploadedFile $file
     * @param string $folder Subfolder in storage (e.g., 'materials', 'quizzes')
     * @param string $prefix Filename prefix
     * @return string URL of uploaded file
     * @throws \Exception
     */
    public function uploadImage(UploadedFile $file, string $folder, string $prefix = 'image'): string
    {
        try {
            $timestamp = time();
            $uuid = Str::uuid();
            $extension = $file->getClientOriginalExtension();
            $filename = "{$prefix}_{$timestamp}_{$uuid}.{$extension}";

            // Ensure directory exists
            $directory = storage_path("app/public/{$folder}");
            if (!file_exists($directory)) {
                mkdir($directory, 0755, true);
            }

            // Full destination path
            $destinationPath = $directory . '/' . $filename;

            // Use native PHP move_uploaded_file
            $tmpPath = $file->getPathname();
            $moved = move_uploaded_file($tmpPath, $destinationPath);

            if (!$moved) {
                // Fallback to copy if move_uploaded_file fails
                $moved = copy($tmpPath, $destinationPath);
            }

            if (!$moved) {
                throw new \Exception("Failed to move uploaded file to: {$destinationPath}");
            }

            // Verify file exists and has content
            if (!file_exists($destinationPath) || filesize($destinationPath) === 0) {
                throw new \Exception("File is empty or does not exist after upload");
            }

            return asset("storage/{$folder}/{$filename}");
        } catch (\Exception $e) {
            Log::error("Image upload failed: " . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Delete image from storage
     *
     * @param string|null $imageUrl
     * @param string $folder
     * @return bool
     */
    public function deleteImage(?string $imageUrl, string $folder): bool
    {
        if (!$imageUrl) {
            return false;
        }

        try {
            $path = parse_url($imageUrl, PHP_URL_PATH);
            $filename = basename($path);
            $filePath = storage_path("app/public/{$folder}/{$filename}");

            if (file_exists($filePath)) {
                unlink($filePath);
                return true;
            }

            return false;
        } catch (\Exception $e) {
            Log::error("Failed to delete image: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Check if URL is from our storage
     *
     * @param string|null $url
     * @return bool
     */
    public function isStorageUrl(?string $url): bool
    {
        if (!$url) {
            return false;
        }

        return str_contains($url, '/storage/');
    }
}
