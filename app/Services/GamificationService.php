<?php

namespace App\Services;

use App\Models\User;
use App\Models\Badge;
use App\Models\UserBadge;
use App\Models\Level;
use App\Models\LeaderboardWeekly;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class GamificationService
{
    /**
     * Add XP to user and handle level up logic.
     *
     * @param int $userId
     * @param int $xpAmount
     * @return array
     */
    public function addXP(int $userId, int $xpAmount): array
    {
        DB::beginTransaction();
        try {
            $user = User::findOrFail($userId);
            $oldLevel = $user->current_level;
            $oldXP = $user->total_xp;

            // Add XP
            $user->total_xp += $xpAmount;

            // Update streak
            $this->updateStreak($user);

            // Check level up
            $newLevel = $this->calculateLevel($user->total_xp);
            $leveledUp = false;

            if ($newLevel > $oldLevel) {
                $user->current_level = $newLevel;
                $leveledUp = true;

                // Award level completion badge
                $this->checkAndAwardLevelBadge($user, $newLevel);
            }

            $user->save();

            // Update weekly leaderboard
            $this->updateWeeklyLeaderboard($userId, $xpAmount);

            // Check XP milestone badges
            $newBadges = $this->checkAndAwardXPBadges($user);

            DB::commit();

            return [
                'old_xp' => $oldXP,
                'new_xp' => $user->total_xp,
                'xp_gained' => $xpAmount,
                'level_up' => $leveledUp,
                'old_level' => $oldLevel,
                'new_level' => $user->current_level,
                'new_badges' => $newBadges,
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Calculate user level based on total XP.
     *
     * @param int $totalXP
     * @return int
     */
    private function calculateLevel(int $totalXP): int
    {
        $level = Level::where('xp_required', '<=', $totalXP)
            ->orderBy('xp_required', 'desc')
            ->first();

        return $level ? $level->level_number : 1;
    }

    /**
     * Update user streak.
     *
     * @param User $user
     * @return void
     */
    private function updateStreak(User $user): void
    {
        $today = Carbon::today();
        $lastActivity = $user->last_activity_date ? Carbon::parse($user->last_activity_date) : null;

        if (!$lastActivity) {
            // First activity
            $user->streak_count = 1;
            $user->last_activity_date = $today;
        } elseif ($lastActivity->isSameDay($today)) {
            // Already active today, no change
            return;
        } elseif ($lastActivity->isSameDay($today->copy()->subDay())) {
            // Consecutive day
            $user->streak_count++;
            $user->last_activity_date = $today;

            // Check streak badges
            $this->checkAndAwardStreakBadge($user);
        } else {
            // Streak broken
            $user->streak_count = 1;
            $user->last_activity_date = $today;
        }
    }

    /**
     * Update weekly leaderboard.
     *
     * @param int $userId
     * @param int $xpAmount
     * @return void
     */
    private function updateWeeklyLeaderboard(int $userId, int $xpAmount): void
    {
        $weekStart = Carbon::now()->startOfWeek();

        LeaderboardWeekly::updateOrCreate(
            [
                'user_id' => $userId,
                'week_start_date' => $weekStart->toDateString(),
            ],
            [
                'total_xp' => DB::raw("total_xp + $xpAmount"),
            ]
        );
    }

    /**
     * Check and award XP milestone badges.
     *
     * @param User $user
     * @return array
     */
    private function checkAndAwardXPBadges(User $user): array
    {
        $milestones = Badge::where('criteria_type', 'xp_milestone')
            ->where('criteria_value', '<=', $user->total_xp)
            ->get();

        $newBadges = [];

        foreach ($milestones as $badge) {
            $hasEarned = UserBadge::where('user_id', $user->id)
                ->where('badge_id', $badge->id)
                ->exists();

            if (!$hasEarned) {
                UserBadge::create([
                    'user_id' => $user->id,
                    'badge_id' => $badge->id,
                    'earned_at' => now(),
                ]);

                $newBadges[] = [
                    'id' => $badge->id,
                    'name' => $badge->name,
                    'description' => $badge->description,
                    'icon_url' => $badge->icon_url,
                ];
            }
        }

        return $newBadges;
    }

    /**
     * Check and award streak badge.
     *
     * @param User $user
     * @return void
     */
    private function checkAndAwardStreakBadge(User $user): void
    {
        $streakBadges = Badge::where('criteria_type', 'streak')
            ->where('criteria_value', '<=', $user->streak_count)
            ->get();

        foreach ($streakBadges as $badge) {
            $hasEarned = UserBadge::where('user_id', $user->id)
                ->where('badge_id', $badge->id)
                ->exists();

            if (!$hasEarned) {
                UserBadge::create([
                    'user_id' => $user->id,
                    'badge_id' => $badge->id,
                    'earned_at' => now(),
                ]);
            }
        }
    }

    /**
     * Check and award level completion badge.
     *
     * @param User $user
     * @param int $levelNumber
     * @return void
     */
    private function checkAndAwardLevelBadge(User $user, int $levelNumber): void
    {
        $levelBadge = Badge::where('criteria_type', 'level_complete')
            ->where('criteria_value', $levelNumber)
            ->first();

        if ($levelBadge) {
            $hasEarned = UserBadge::where('user_id', $user->id)
                ->where('badge_id', $levelBadge->id)
                ->exists();

            if (!$hasEarned) {
                UserBadge::create([
                    'user_id' => $user->id,
                    'badge_id' => $levelBadge->id,
                    'earned_at' => now(),
                ]);
            }
        }
    }
}
