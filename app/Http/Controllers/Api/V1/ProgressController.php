<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\UserProgress;
use App\Services\ProgressService;
use Illuminate\Http\JsonResponse;

class ProgressController extends Controller
{
    public function __construct(
        protected ProgressService $progressService
    ) {}

    /**
     * Get current authenticated user's progress summary
     * GET /progress
     */
    public function myProgress(): JsonResponse
    {
        $userId = auth()->id();

        $summary = $this->progressService->getUserProgressSummary($userId);

        // Get completed stages list
        $stagesProgress = UserProgress::with('stage.level')
            ->where('user_id', $userId)
            ->where('status', 'completed')
            ->get()
            ->map(fn($progress) => [
                'stage_id' => $progress->stage_id,
                'stage_title' => $progress->stage->title,
                'level_id' => $progress->stage->level_id,
                'status' => $progress->status,
                'completed_at' => $progress->completed_at,
            ]);

        return response()->json([
            'success' => true,
            'data' => array_merge($summary, ['stages' => $stagesProgress]),
        ]);
    }

    /**
     * Get detailed progress per level with all stages
     * GET /progress/levels
     */
    public function levelsProgress(): JsonResponse
    {
        $userId = auth()->id();
        $levelsProgress = $this->progressService->getDetailedLevelProgress($userId);

        return response()->json([
            'success' => true,
            'data' => $levelsProgress,
        ]);
    }

    /**
     * Get progress for a specific level
     * GET /progress/levels/{levelId}
     */
    public function levelProgress(int $levelId): JsonResponse
    {
        $userId = auth()->id();
        $levelProgress = $this->progressService->getLevelProgress($userId, $levelId);

        return response()->json([
            'success' => true,
            'data' => $levelProgress,
        ]);
    }

    /**
     * Get user progress by ID (Admin only)
     * GET /admin/users/{userId}/progress
     */
    public function show(int $userId): JsonResponse
    {
        $summary = $this->progressService->getUserProgressSummary($userId);
        $levelsProgress = $this->progressService->getDetailedLevelProgress($userId);

        return response()->json([
            'success' => true,
            'data' => array_merge($summary, ['levels' => $levelsProgress]),
        ]);
    }
}
