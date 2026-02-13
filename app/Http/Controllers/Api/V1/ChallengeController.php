<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\SubmitDrawingRequest;
use App\Models\ChallengeResult;
use App\Models\Evaluation;
use App\Services\DrawingEvaluationService;
use App\Services\FileUploadService;
use App\Services\GamificationService;
use App\Services\ProgressService;
use Illuminate\Http\JsonResponse;

class ChallengeController extends Controller
{
    public function __construct(
        protected DrawingEvaluationService $drawingService,
        protected GamificationService $gamificationService,
        protected ProgressService $progressService,
        protected FileUploadService $fileUploadService
    ) {}

    /**
     * Submit user drawing for evaluation
     */
    public function submitDrawing(SubmitDrawingRequest $request, int $evaluationId): JsonResponse
    {
        $evaluation = Evaluation::with('stage')->findOrFail($evaluationId);
        $user = $request->user();

        // Get attempt number
        $attemptNumber = ChallengeResult::where('user_id', $user->id)
            ->where('evaluation_id', $evaluationId)
            ->count() + 1;

        // Upload file
        $file = $request->file('drawing_image');
        $userDrawingUrl = $this->fileUploadService->uploadImage(
            $file,
            'drawings',
            'drawing_' . $user->id . '_'
        );

        try {
            // Call ML service for evaluation
            $similarityScore = $this->drawingService->evaluateDrawing(
                $evaluation->reference_image_url,
                url($userDrawingUrl)
            );

            $isPassed = $this->drawingService->isPassed(
                $similarityScore,
                $evaluation->min_similarity_score
            );

            // Save result
            $result = ChallengeResult::create([
                'user_id' => $user->id,
                'evaluation_id' => $evaluationId,
                'user_drawing_url' => $userDrawingUrl,
                'similarity_score' => $similarityScore,
                'is_passed' => $isPassed,
                'attempt_number' => $attemptNumber,
            ]);

            $response = $this->buildResponse($result, $similarityScore, $isPassed);

            // Handle success rewards
            if ($isPassed) {
                $this->handleSuccess($user, $evaluation, $response);
            }

            return response()->json([
                'success' => true,
                'data' => $response,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Build base response array
     */
    private function buildResponse(ChallengeResult $result, float $similarityScore, bool $isPassed): array
    {
        return [
            'result_id' => $result->id,
            'similarity_score' => $similarityScore,
            'is_passed' => $isPassed,
            'xp_earned' => 0,
            'level_up' => false,
            'new_badges' => [],
            'next_stage_unlocked' => null,
        ];
    }

    /**
     * Handle success rewards
     */
    private function handleSuccess($user, Evaluation $evaluation, array &$response): void
    {
        // Add XP
        $xpResult = $this->gamificationService->addXP($user->id, $evaluation->stage->xp_reward);
        $response['xp_earned'] = $evaluation->stage->xp_reward;
        $response['level_up'] = $xpResult['level_up'];
        $response['new_badges'] = $xpResult['new_badges'];

        // Complete stage
        $progressResult = $this->progressService->completeStage($user->id, $evaluation->stage_id);
        $response['next_stage_unlocked'] = $progressResult['next_stage_unlocked'];
    }
}
