<?php

namespace App\Services;

use App\Models\LeaderboardWeekly;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\DB;

class LeaderboardService
{
    /**
     * Get weekly leaderboard with Redis caching.
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

        // Try to get from cache
        $cached = Redis::get($cacheKey);
        if ($cached) {
            return json_decode($cached, true);
        }

        // Query database
        $leaderboard = DB::table('leaderboard_weekly')
            ->join('users', 'leaderboard_weekly.user_id', '=', 'users.id')
            ->where('leaderboard_weekly.week_start_date', $weekStartDate)
            ->select(
                'users.id as user_id',
                'users.name',
                'users.avatar_url',
                'leaderboard_weekly.total_xp',
                DB::raw('RANK() OVER (ORDER BY leaderboard_weekly.total_xp DESC) as rank')
            )
            ->orderBy('leaderboard_weekly.total_xp', 'desc')
            ->limit(10)
            ->get()
            ->toArray();

        $result = [
            'week_start_date' => $weekStartDate,
            'top_10' => $leaderboard,
        ];

        // Cache for 5 minutes (300 seconds)
        Redis::setex($cacheKey, 300, json_encode($result));

        return $result;
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
                'leaderboard_weekly.total_xp',
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
            'total_xp' => $userEntry->total_xp,
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

        // Try to get from cache
        $cached = Redis::get($cacheKey);
        if ($cached) {
            return json_decode($cached, true);
        }

        $leaderboard = User::where('role', 'pemain')
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

        // Cache for 5 minutes
        Redis::setex($cacheKey, 300, json_encode($leaderboard));

        return $leaderboard;
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
            Redis::del("leaderboard:weekly:{$weekStartDate}");
        }

        // Also invalidate all-time leaderboard
        $keys = Redis::keys("leaderboard:alltime:*");
        if (!empty($keys)) {
            Redis::del($keys);
        }
    }
}
