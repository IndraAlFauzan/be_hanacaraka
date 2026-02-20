<?php

namespace App\Services;

use App\Models\ChallengeResult;
use App\Models\Evaluation;
use App\Models\Stage;
use App\Models\User;
use Illuminate\Http\UploadedFile;

class DrawingService
{
    public function __construct(
        protected FileUploadService $fileUploadService,
        protected GamificationService $gamificationService,
        protected ProgressService $progressService
    ) {}

    /**
     * Process drawing submission and evaluate result.
     *
     * @param User $user
     * @param Stage $stage
     * @param UploadedFile $drawingImage
     * @param float $similarityScore
     * @return array
     */
    public function submitDrawing(
        User $user,
        Stage $stage,
        UploadedFile $drawingImage,
        float $similarityScore
    ): array {
        $evaluation = Evaluation::where('stage_id', $stage->id)->firstOrFail();

        // Get attempt number
        $attemptNumber = $this->getAttemptNumber($user->id, $evaluation->id);

        // Upload drawing image
        $userDrawingUrl = $this->fileUploadService->uploadImage(
            $drawingImage,
            'drawings',
            'drawing_' . $user->id . '_'
        );

        // Check if passed
        $isPassed = $similarityScore >= $evaluation->min_similarity_score;

        // Save challenge result
        $result = ChallengeResult::create([
            'user_id' => $user->id,
            'evaluation_id' => $evaluation->id,
            'user_drawing_url' => $userDrawingUrl,
            'similarity_score' => $similarityScore,
            'is_passed' => $isPassed,
            'attempt_number' => $attemptNumber,
        ]);

        // Calculate rewards if passed
        $additionalData = $this->calculateRewards($user, $stage, $isPassed);

        return [
            'result' => $result,
            'additional_data' => $additionalData,
        ];
    }

    /**
     * Get attempt number for user's challenge.
     *
     * @param int $userId
     * @param int $evaluationId
     * @return int
     */
    private function getAttemptNumber(int $userId, int $evaluationId): int
    {
        return ChallengeResult::where('user_id', $userId)
            ->where('evaluation_id', $evaluationId)
            ->count() + 1;
    }

    /**
     * Calculate rewards based on evaluation type and pass status.
     *
     * @param User $user
     * @param Stage $stage
     * @param bool $isPassed
     * @return array
     */
    private function calculateRewards(User $user, Stage $stage, bool $isPassed): array
    {
        $rewards = [
            'xp_earned' => 0,
            'level_up' => false,
            'stage_completed' => false,
            'new_badges' => [],
            'next_stage_unlocked' => null,
        ];

        if (!$isPassed) {
            return $rewards;
        }

        if ($stage->evaluation_type === 'drawing') {
            // Drawing is the ONLY evaluation - award full XP and complete stage
            $xpResult = $this->gamificationService->addXP($user->id, $stage->xp_reward);
            $rewards['xp_earned'] = $stage->xp_reward;
            $rewards['level_up'] = $xpResult['level_up'];
            $rewards['new_badges'] = $xpResult['new_badges'];

            $progressResult = $this->progressService->completeStage($user->id, $stage->id);
            $rewards['stage_completed'] = true;
            $rewards['next_stage_unlocked'] = $progressResult['next_stage_unlocked'];
        } elseif ($stage->evaluation_type === 'both') {
            // Stage requires BOTH drawing and quiz - award 50% XP, stage not completed yet
            $partialXp = (int) round($stage->xp_reward * 0.5);
            $xpResult = $this->gamificationService->addXP($user->id, $partialXp);
            $rewards['xp_earned'] = $partialXp;
            $rewards['level_up'] = $xpResult['level_up'];
            $rewards['new_badges'] = $xpResult['new_badges'];
            $rewards['stage_completed'] = false;
            $rewards['next_stage_unlocked'] = null;
        }

        return $rewards;
    }
}
