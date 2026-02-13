<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\UploadImageRequest;
use App\Services\FileUploadService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class FileUploadController extends Controller
{
    public function __construct(
        protected FileUploadService $fileUploadService
    ) {}

    /**
     * Upload image file (admin only)
     */
    public function uploadImage(UploadImageRequest $request): JsonResponse
    {
        try {
            $file = $request->file('image');
            $type = $request->input('type');

            $folder = match ($type) {
                'material' => 'materials',
                'reference' => 'references',
                'avatar' => 'avatars',
                default => 'uploads',
            };

            $url = $this->fileUploadService->uploadImage($file, $folder, "{$type}_");

            return response()->json([
                'success' => true,
                'message' => 'Image uploaded successfully',
                'data' => [
                    'url' => $url,
                    'filename' => basename($url),
                    'type' => $type,
                ],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to upload image: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Delete image file (admin only)
     */
    public function deleteImage(Request $request): JsonResponse
    {
        $request->validate([
            'url' => 'required|string',
            'type' => 'required|in:material,reference,avatar',
        ]);

        try {
            $folder = match ($request->input('type')) {
                'material' => 'materials',
                'reference' => 'references',
                'avatar' => 'avatars',
                default => 'uploads',
            };

            $this->fileUploadService->deleteImage($request->input('url'), $folder);

            return response()->json([
                'success' => true,
                'message' => 'Image deleted successfully',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete image: ' . $e->getMessage(),
            ], 500);
        }
    }
}
