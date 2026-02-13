<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\StoreEvaluationRequest;
use App\Http\Resources\V1\EvaluationResource;
use App\Models\ChallengeResult;
use App\Models\Evaluation;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class EvaluationController extends Controller
{
    /**
     * Show evaluation for a stage with user attempt stats
     */
    public function show(int $stageId, Request $request): JsonResponse
    {
        $evaluation = Evaluation::where('stage_id', $stageId)->firstOrFail();
        $user = $request->user();

        $userAttempts = 0;
        $userBestScore = null;

        if ($user) {
            $results = ChallengeResult::where('user_id', $user->id)
                ->where('evaluation_id', $evaluation->id)
                ->get();
            $userAttempts = $results->count();
            $userBestScore = $results->max('similarity_score');
        }

        return response()->json([
            'success' => true,
            'data' => [
                'evaluation' => new EvaluationResource($evaluation),
                'user_attempts' => $userAttempts,
                'user_best_score' => $userBestScore,
            ],
        ]);
    }

    /**
     * Store new evaluation (Admin only)
     */
    public function store(StoreEvaluationRequest $request): JsonResponse
    {
        $evaluation = Evaluation::create($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Evaluation created successfully',
            'data' => new EvaluationResource($evaluation),
        ], 201);
    }
}
