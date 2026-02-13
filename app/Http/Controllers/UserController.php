<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    /**
     * Get user detail dengan badges dan stats
     */
    public function show($id)
    {
        $user = auth()->user();

        // Only allow user to view their own profile or admin can view any
        if ($user->id != $id && !$user->isAdmin()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 403);
        }

        $targetUser = User::with(['badges', 'progress'])
            ->withCount([
                'progress as completed_stages' => function ($query) {
                    $query->where('status', 'completed');
                }
            ])
            ->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $targetUser->id,
                'name' => $targetUser->name,
                'email' => $targetUser->email,
                'role' => $targetUser->role,
                'total_xp' => $targetUser->total_xp,
                'current_level' => $targetUser->current_level,
                'streak_count' => $targetUser->streak_count,
                'last_activity_date' => $targetUser->last_activity_date,
                'daily_goal_xp' => $targetUser->daily_goal_xp,
                'avatar_url' => $targetUser->avatar_url,
                'completed_stages' => $targetUser->completed_stages,
                'badges' => $targetUser->badges->map(function ($badge) {
                    return [
                        'id' => $badge->id,
                        'name' => $badge->name,
                        'description' => $badge->description,
                        'icon_url' => $badge->icon_url,
                        'earned_at' => $badge->pivot->earned_at
                    ];
                }),
                'created_at' => $targetUser->created_at
            ]
        ]);
    }

    /**
     * Update user profile (self only)
     */
    public function update(Request $request, $id)
    {
        $user = auth()->user();

        // Only allow user to update their own profile
        if ($user->id != $id) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 403);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|string|max:100',
            'daily_goal_xp' => 'sometimes|integer|min:10|max:500',
            'avatar_url' => 'sometimes|url'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        $user->update($request->only(['name', 'daily_goal_xp', 'avatar_url']));

        return response()->json([
            'success' => true,
            'message' => 'Profile updated successfully',
            'data' => $user
        ]);
    }

    /**
     * Upload avatar (self only)
     */
    public function uploadAvatar(Request $request, $id)
    {
        $user = auth()->user();

        // Only allow user to upload their own avatar
        if ($user->id != $id) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 403);
        }

        $validator = Validator::make($request->all(), [
            'avatar' => 'required|image|mimes:jpeg,jpg,png|max:2048' // 2MB
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            // Delete old avatar if exists
            if ($user->avatar_url) {
                $oldPath = str_replace('/storage/', 'public/', parse_url($user->avatar_url, PHP_URL_PATH));
                Storage::delete($oldPath);
            }

            // Generate unique filename
            $file = $request->file('avatar');
            $filename = 'avatar_' . $user->id . '_' . time() . '.' . $file->getClientOriginalExtension();

            // Store file
            $path = $file->storeAs('public/avatars', $filename);
            $url = Storage::url($path);

            // Update user
            $user->update(['avatar_url' => $url]);

            return response()->json([
                'success' => true,
                'message' => 'Avatar uploaded successfully',
                'data' => [
                    'avatar_url' => $url
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to upload avatar: ' . $e->getMessage()
            ], 500);
        }
    }
}
