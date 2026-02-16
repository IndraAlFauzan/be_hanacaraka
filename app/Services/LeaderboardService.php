<?php

namespace App\Services;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class LeaderboardService
{
    /**
     * Cache TTL in seconds (5 minutes).
     */
    private const CACHE_TTL = 300;

    /**
     * Get weekly leaderboard with caching.
     *
     * @param string|null $weekStartDate
     * @return array
     */
    public function getWeeklyLeaderboard(?string $weekStartDate = null): array
    {
        if (!$weekStartDate) {
            $weekStartDate = Carbon::now()->startOfWeek()->toDateString();
        }

        $cacheKey = "leaderboard:weekly:{$weekStartDate}";

        return Cache::remember($cacheKey, self::CACHE_TTL, function () use ($weekStartDate) {
            $leaderboard = DB::table('leaderboard_weekly')
                ->join('users', 'leaderboard_weekly.user_id', '=', 'users.id')
                ->where('leaderboard_weekly.week_start_date', $weekStartDate)
                ->select(
                    'users.id as user_id',
                    'users.name',
                    'users.avatar_url',
                    'leaderboard_weekly.total_xp as weekly_xp',
                    DB::raw('RANK() OVER (ORDER BY leaderboard_weekly.total_xp DESC) as `rank`')
                )
                ->orderBy('leaderboard_weekly.total_xp', 'desc')
                ->limit(10)
                ->get()
                ->toArray();

            return [
                'week_start_date' => $weekStartDate,
                'top_10' => $leaderboard,
            ];
        });
    }

    /**
     * Get current user's rank in weekly leaderboard.
     *
     * @param int $userId
     * @param string|null $weekStartDate
     * @return array|null
     */
    public function getUserWeeklyRank(int $userId, ?string $weekStartDate = null): ?array
    {
        if (!$weekStartDate) {
            $weekStartDate = Carbon::now()->startOfWeek()->toDateString();
        }

        $userEntry = DB::table('leaderboard_weekly')
            ->join('users', 'leaderboard_weekly.user_id', '=', 'users.id')
            ->where('leaderboard_weekly.week_start_date', $weekStartDate)
            ->where('leaderboard_weekly.user_id', $userId)
            ->select(
                'leaderboard_weekly.total_xp as weekly_xp',
                DB::raw('(
                    SELECT COUNT(*) + 1 
                    FROM leaderboard_weekly as lw 
                    WHERE lw.week_start_date = leaderboard_weekly.week_start_date 
                    AND lw.total_xp > leaderboard_weekly.total_xp
                ) as rank')
            )
            ->first();

        if (!$userEntry) {
            return null;
        }

        return [
            'rank' => $userEntry->rank,
            'weekly_xp' => $userEntry->weekly_xp,
        ];
    }

    /**
     * Get all-time leaderboard (top users by total XP).
     *
     * @param int $limit
     * @return array
     */
    public function getAllTimeLeaderboard(int $limit = 10): array
    {
        $cacheKey = "leaderboard:alltime:{$limit}";

        return Cache::remember($cacheKey, self::CACHE_TTL, function () use ($limit) {
            return User::where('role', 'pemain')
                ->orderBy('total_xp', 'desc')
                ->limit($limit)
                ->select('id', 'name', 'avatar_url', 'total_xp', 'current_level')
                ->get()
                ->map(function ($user, $index) {
                    return [
                        'rank' => $index + 1,
                        'user_id' => $user->id,
                        'name' => $user->name,
                        'avatar_url' => $user->avatar_url,
                        'total_xp' => $user->total_xp,
                        'current_level' => $user->current_level,
                    ];
                })
                ->toArray();
        });
    }

    /**
     * Invalidate leaderboard cache.
     *
     * @param string|null $weekStartDate
     * @return void
     */
    public function invalidateCache(?string $weekStartDate = null): void
    {
        if ($weekStartDate) {
            Cache::forget("leaderboard:weekly:{$weekStartDate}");
        }

        // Invalidate current week
        $currentWeek = Carbon::now()->startOfWeek()->toDateString();
        Cache::forget("leaderboard:weekly:{$currentWeek}");

        // Invalidate all-time leaderboard for common limits
        foreach ([10, 20, 50, 100] as $limit) {
            Cache::forget("leaderboard:alltime:{$limit}");
        }
    }

    /**
     * Update user's weekly XP.
     *
     * @param int $userId
     * @param int $xpEarned
     * @return void
     */
    public function updateWeeklyXP(int $userId, int $xpEarned): void
    {
        $weekStartDate = Carbon::now()->startOfWeek()->toDateString();

        DB::table('leaderboard_weekly')
            ->updateOrInsert(
                [
                    'user_id' => $userId,
                    'week_start_date' => $weekStartDate,
                ],
                [
                    'total_xp' => DB::raw("COALESCE(total_xp, 0) + {$xpEarned}"),
                    'updated_at' => now(),
                ]
            );

        // Invalidate cache
        $this->invalidateCache($weekStartDate);
    }
}
