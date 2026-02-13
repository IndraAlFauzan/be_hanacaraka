<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Http\UploadedFile;

class UserService
{
    public function __construct(
        protected FileUploadService $fileUploadService
    ) {}

    /**
     * Get user with badges and progress
     */
    public function getUserWithRelations(int $userId): User
    {
        return User::with(['badges', 'progress'])
            ->withCount([
                'progress as completed_stages' => fn($q) => $q->where('status', 'completed'),
            ])
            ->findOrFail($userId);
    }

    /**
     * Update user profile
     */
    public function updateProfile(User $user, array $data): User
    {
        $user->update($data);
        return $user->fresh();
    }

    /**
     * Upload and update user avatar
     */
    public function uploadAvatar(User $user, UploadedFile $file): string
    {
        // Delete old avatar if exists
        if ($user->avatar_url) {
            $this->fileUploadService->deleteImage($user->avatar_url, 'avatars');
        }

        // Upload new avatar
        $avatarUrl = $this->fileUploadService->uploadImage(
            $file,
            'avatars',
            'avatar_' . $user->id . '_'
        );

        // Update user
        $user->update(['avatar_url' => $avatarUrl]);

        return $avatarUrl;
    }
}
