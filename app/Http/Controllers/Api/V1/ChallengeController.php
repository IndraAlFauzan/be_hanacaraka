<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\SubmitDrawingRequest;
use App\Models\ChallengeResult;
use App\Models\Evaluation;
use App\Models\Stage;
use App\Services\FileUploadService;
use App\Services\GamificationService;
use App\Services\ProgressService;
use Illuminate\Http\JsonResponse;

class ChallengeController extends Controller
{
    public function __construct(
        protected GamificationService $gamificationService,
        protected ProgressService $progressService,
        protected FileUploadService $fileUploadService
    ) {}

    /**
     * Submit user drawing for evaluation
     * Similarity score is calculated by TFLite model on mobile app
     */
    public function submitDrawing(SubmitDrawingRequest $request, int $stageId): JsonResponse
    {
        $stage = Stage::findOrFail($stageId);
        $evaluation = Evaluation::where('stage_id', $stageId)->firstOrFail();
        $user = $request->user();

        // Get similarity score from mobile app (calculated by TFLite)
        $similarityScore = (float) $request->validated()['similarity_score'];

        // Get attempt number
        $attemptNumber = ChallengeResult::where('user_id', $user->id)
            ->where('evaluation_id', $evaluation->id)
            ->count() + 1;

        // Upload file
        $file = $request->file('drawing_image');
        $userDrawingUrl = $this->fileUploadService->uploadImage(
            $file,
            'drawings',
            'drawing_' . $user->id . '_'
        );

        // Check if passed based on min_similarity_score from evaluation
        $isPassed = $similarityScore >= $evaluation->min_similarity_score;

        // Save result
        $result = ChallengeResult::create([
            'user_id' => $user->id,
            'evaluation_id' => $evaluation->id,
            'user_drawing_url' => $userDrawingUrl,
            'similarity_score' => $similarityScore,
            'is_passed' => $isPassed,
            'attempt_number' => $attemptNumber,
        ]);

        $response = $this->buildResponse($result, $similarityScore, $isPassed);

        // Handle success rewards if passed
        if ($isPassed) {
            $this->handleSuccess($user, $stage, $response);
        }

        return response()->json([
            'success' => true,
            'data' => $response,
        ]);
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
            'stage_completed' => false,
        ];
    }

    /**
     * Handle success rewards
     */
    private function handleSuccess($user, Stage $stage, array &$response): void
    {
        // Check if drawing is the main evaluation for this stage
        if ($stage->requiresDrawing() && !$stage->requiresQuiz()) {
            // Drawing is MAIN evaluation - complete stage and award XP
            $xpResult = $this->gamificationService->addXP($user->id, $stage->xp_reward);
            $response['xp_earned'] = $stage->xp_reward;
            $response['level_up'] = $xpResult['level_up'];
            $response['new_badges'] = $xpResult['new_badges'];

            $progressResult = $this->progressService->completeStage($user->id, $stage->id);
            $response['next_stage_unlocked'] = $progressResult['next_stage_unlocked'];
            $response['stage_completed'] = true;
        } elseif ($stage->evaluation_type === 'both') {
            // Stage requires BOTH drawing and quiz
            // Drawing passed - award partial XP (50%), stage completes after quiz
            $partialXp = (int) round($stage->xp_reward * 0.5);
            $xpResult = $this->gamificationService->addXP($user->id, $partialXp);
            $response['xp_earned'] = $partialXp;
            $response['level_up'] = $xpResult['level_up'];
            $response['new_badges'] = $xpResult['new_badges'];
            // Stage NOT completed yet - quiz required
            $response['stage_completed'] = false;
        }
        // If evaluation_type is 'quiz', drawing should not be submitted anyway
    }
}
