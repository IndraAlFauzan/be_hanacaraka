<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Services\LeaderboardService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class LeaderboardController extends Controller
{
    public function __construct(
        protected LeaderboardService $leaderboardService
    ) {}

    /**
     * Get weekly leaderboard
     */
    public function weekly(Request $request): JsonResponse
    {
        $weekStartDate = $request->query('week_start_date');
        $leaderboard = $this->leaderboardService->getWeeklyLeaderboard($weekStartDate);

        $currentUserRank = null;
        if ($request->user()) {
            $currentUserRank = $this->leaderboardService->getUserWeeklyRank(
                $request->user()->id,
                $weekStartDate
            );
        }

        return response()->json([
            'success' => true,
            'data' => [
                'week_start_date' => $leaderboard['week_start_date'],
                'top_10' => $leaderboard['top_10'],
                'current_user_rank' => $currentUserRank,
            ],
        ]);
    }

    /**
     * Get all-time leaderboard
     */
    public function allTime(Request $request): JsonResponse
    {
        $limit = (int) $request->query('limit', 10);
        $leaderboard = $this->leaderboardService->getAllTimeLeaderboard($limit);

        return response()->json([
            'success' => true,
            'data' => $leaderboard,
        ]);
    }
}
