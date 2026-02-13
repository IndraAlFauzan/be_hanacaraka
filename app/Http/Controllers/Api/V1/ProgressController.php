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
     */
    public function myProgress(): JsonResponse
    {
        $userId = auth()->id();

        $summary = $this->progressService->getUserProgressSummary($userId);

        $stagesProgress = UserProgress::with('stage.level')
            ->where('user_id', $userId)
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
     * Get user progress by ID (Admin only)
     */
    public function show(int $userId): JsonResponse
    {
        $summary = $this->progressService->getUserProgressSummary($userId);

        $stagesProgress = UserProgress::with('stage.level')
            ->where('user_id', $userId)
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
}
