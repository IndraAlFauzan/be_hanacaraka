<?php

namespace App\Services;

use App\Models\Badge;
use App\Models\User;

class BadgeService
{
    /**
     * Get all badges
     */
    public function getAllBadges()
    {
        return Badge::orderBy('criteria_type')
            ->orderBy('criteria_value')
            ->get();
    }

    /**
     * Get user's earned badges with full details
     */
    public function getUserBadges(int $userId): array
    {
        $user = User::with(['badges' => function ($query) {
            $query->orderBy('user_badges.earned_at', 'desc');
        }])->findOrFail($userId);

        $totalAvailable = Badge::count();

        $earnedBadges = $user->badges->map(fn($badge) => [
            'id' => $badge->id,
            'name' => $badge->name,
            'description' => $badge->description,
            'icon_url' => $badge->icon_path ? asset('storage/' . $badge->icon_path) : null,
            'requirement_type' => $badge->criteria_type,
            'requirement_value' => $badge->criteria_value,
            'earned_at' => $badge->pivot->earned_at,
        ]);

        return [
            'earned_badges' => $earnedBadges,
            'total_earned' => $earnedBadges->count(),
            'total_available' => $totalAvailable,
        ];
    }
}
