<?php

namespace App\Services;

use App\Models\Badge;
use App\Models\User;

class BadgeService
{
    /**
     * Get all active badges
     */
    public function getAllActiveBadges()
    {
        return Badge::where('is_active', true)
            ->orderBy('criteria_type')
            ->orderBy('criteria_value')
            ->get();
    }

    /**
     * Get user's earned badges
     */
    public function getUserBadges(int $userId): array
    {
        $user = User::with(['badges' => function ($query) {
            $query->orderBy('user_badges.earned_at', 'desc');
        }])->findOrFail($userId);

        $earnedBadges = $user->badges->map(fn($badge) => [
            'id' => $badge->id,
            'name' => $badge->name,
            'description' => $badge->description,
            'icon_url' => $badge->icon_url,
            'criteria_type' => $badge->criteria_type,
            'criteria_value' => $badge->criteria_value,
            'earned_at' => $badge->pivot->earned_at,
        ]);

        return [
            'user_id' => $user->id,
            'user_name' => $user->name,
            'total_badges' => $earnedBadges->count(),
            'badges' => $earnedBadges,
        ];
    }
}
