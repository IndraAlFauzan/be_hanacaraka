<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class FileUploadService
{
    /**
     * Allowed MIME types for images.
     */
    protected array $allowedMimeTypes = [
        'image/jpeg',
        'image/png',
        'image/gif',
        'image/webp',
    ];

    /**
     * Allowed extensions for images.
     */
    protected array $allowedExtensions = [
        'jpg',
        'jpeg',
        'png',
        'gif',
        'webp',
    ];

    /**
     * Maximum file size in bytes (5MB).
     */
    protected int $maxFileSize = 5242880;

    /**
     * Upload image file to storage with security checks.
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
            // Validate file security
            $this->validateFile($file);

            // Generate secure filename
            $filename = $this->generateSecureFilename($prefix, $file);

            // Sanitize folder path
            $folder = $this->sanitizeFolderPath($folder);

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

            // Verify the uploaded file is actually an image
            if (!$this->isValidImage($destinationPath)) {
                unlink($destinationPath);
                throw new \Exception("Uploaded file is not a valid image");
            }

            return asset("storage/{$folder}/{$filename}");
        } catch (\Exception $e) {
            Log::error("Image upload failed: " . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Validate uploaded file for security.
     */
    protected function validateFile(UploadedFile $file): void
    {
        // Check file size
        if ($file->getSize() > $this->maxFileSize) {
            throw new \Exception("File size exceeds maximum allowed size of 5MB");
        }

        // Check MIME type
        $mimeType = $file->getMimeType();
        if (!in_array($mimeType, $this->allowedMimeTypes)) {
            throw new \Exception("Invalid file type. Allowed types: " . implode(', ', $this->allowedMimeTypes));
        }

        // Check extension
        $extension = strtolower($file->getClientOriginalExtension());
        if (!in_array($extension, $this->allowedExtensions)) {
            throw new \Exception("Invalid file extension. Allowed extensions: " . implode(', ', $this->allowedExtensions));
        }

        // Check for PHP code in file content (security measure)
        $content = file_get_contents($file->getPathname());
        if (preg_match('/<\?php|<\?=|<script/i', $content)) {
            throw new \Exception("File contains potentially malicious content");
        }
    }

    /**
     * Generate a secure filename.
     */
    protected function generateSecureFilename(string $prefix, UploadedFile $file): string
    {
        $timestamp = time();
        $uuid = Str::uuid();
        $extension = strtolower($file->getClientOriginalExtension());

        // Sanitize prefix
        $prefix = preg_replace('/[^a-zA-Z0-9_-]/', '', $prefix);

        return "{$prefix}_{$timestamp}_{$uuid}.{$extension}";
    }

    /**
     * Sanitize folder path to prevent directory traversal.
     */
    protected function sanitizeFolderPath(string $folder): string
    {
        // Remove any directory traversal attempts
        $folder = str_replace(['..', '/', '\\'], '', $folder);
        
        // Only allow alphanumeric, underscore, and dash
        return preg_replace('/[^a-zA-Z0-9_-]/', '', $folder);
    }

    /**
     * Verify that the file is a valid image.
     */
    protected function isValidImage(string $path): bool
    {
        $imageInfo = @getimagesize($path);
        
        if ($imageInfo === false) {
            return false;
        }

        // Check if it's a valid image type
        $validTypes = [IMAGETYPE_JPEG, IMAGETYPE_PNG, IMAGETYPE_GIF, IMAGETYPE_WEBP];
        
        return in_array($imageInfo[2], $validTypes);
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
            
            // Sanitize filename to prevent directory traversal
            $filename = preg_replace('/[^a-zA-Z0-9._-]/', '', $filename);
            
            $folder = $this->sanitizeFolderPath($folder);
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
