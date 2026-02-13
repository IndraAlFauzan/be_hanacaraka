<?php

namespace App\Http\Controllers;

use App\Models\Badge;
use App\Models\User;
use Illuminate\Http\Request;

class BadgeController extends Controller
{
    /**
     * Get all badges
     */
    public function index()
    {
        $badges = Badge::where('is_active', true)
            ->orderBy('criteria_type')
            ->orderBy('criteria_value')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $badges
        ]);
    }

    /**
     * Get user's earned badges
     */
    public function userBadges($userId)
    {
        $authUser = auth()->user();

        // Only allow user to view their own badges or admin can view any
        if ($authUser->id != $userId && !$authUser->isAdmin()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 403);
        }

        $user = User::with(['badges' => function ($query) {
            $query->orderBy('user_badges.earned_at', 'desc');
        }])->findOrFail($userId);

        $earnedBadges = $user->badges->map(function ($badge) {
            return [
                'id' => $badge->id,
                'name' => $badge->name,
                'description' => $badge->description,
                'icon_url' => $badge->icon_url,
                'criteria_type' => $badge->criteria_type,
                'criteria_value' => $badge->criteria_value,
                'earned_at' => $badge->pivot->earned_at
            ];
        });

        return response()->json([
            'success' => true,
            'data' => [
                'user_id' => $user->id,
                'user_name' => $user->name,
                'total_badges' => $earnedBadges->count(),
                'badges' => $earnedBadges
            ]
        ]);
    }
}
