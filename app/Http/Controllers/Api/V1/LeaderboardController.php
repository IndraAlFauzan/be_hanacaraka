<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Services\LeaderboardService;
use Illuminate\Http\Request;

class LeaderboardController extends Controller
{
    protected $leaderboardService;

    public function __construct(LeaderboardService $leaderboardService)
    {
        $this->leaderboardService = $leaderboardService;
    }

    public function weekly(Request $request)
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

    public function allTime(Request $request)
    {
        $limit = $request->query('limit', 10);
        $leaderboard = $this->leaderboardService->getAllTimeLeaderboard($limit);

        return response()->json([
            'success' => true,
            'data' => $leaderboard,
        ]);
    }
}
