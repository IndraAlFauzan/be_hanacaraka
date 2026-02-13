<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\UpdateUserRequest;
use App\Http\Requests\Api\V1\UploadAvatarRequest;
use App\Http\Resources\V1\UserResource;
use App\Services\UserService;
use Illuminate\Http\JsonResponse;

class UserController extends Controller
{
    public function __construct(
        protected UserService $userService
    ) {}

    /**
     * Get current authenticated user profile
     */
    public function profile(): JsonResponse
    {
        $user = $this->userService->getUserWithRelations(auth()->id());

        return response()->json([
            'success' => true,
            'data' => new UserResource($user),
        ]);
    }

    /**
     * Update current authenticated user profile
     */
    public function update(UpdateUserRequest $request): JsonResponse
    {
        $user = $this->userService->updateProfile(
            auth()->user(),
            $request->validated()
        );

        return response()->json([
            'success' => true,
            'message' => 'Profile updated successfully',
            'data' => new UserResource($user),
        ]);
    }

    /**
     * Upload avatar for current authenticated user
     */
    public function uploadAvatar(UploadAvatarRequest $request): JsonResponse
    {
        try {
            $avatarUrl = $this->userService->uploadAvatar(
                auth()->user(),
                $request->file('avatar')
            );

            return response()->json([
                'success' => true,
                'message' => 'Avatar uploaded successfully',
                'data' => [
                    'avatar_url' => $avatarUrl,
                ],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to upload avatar: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get user by ID (Admin only)
     */
    public function show(int $id): JsonResponse
    {
        $user = $this->userService->getUserWithRelations($id);

        return response()->json([
            'success' => true,
            'data' => new UserResource($user),
        ]);
    }
}
