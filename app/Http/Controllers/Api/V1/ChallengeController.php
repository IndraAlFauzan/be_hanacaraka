<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Evaluation;
use App\Models\ChallengeResult;
use App\Services\DrawingEvaluationService;
use App\Services\GamificationService;
use App\Services\ProgressService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ChallengeController extends Controller
{
    protected $drawingService;
    protected $gamificationService;
    protected $progressService;

    public function __construct(
        DrawingEvaluationService $drawingService,
        GamificationService $gamificationService,
        ProgressService $progressService
    ) {
        $this->drawingService = $drawingService;
        $this->gamificationService = $gamificationService;
        $this->progressService = $progressService;
    }

    public function submitDrawing(Request $request, $evaluationId)
    {
        $request->validate([
            'drawing_image' => 'required|image|mimes:png,jpg,jpeg|max:2048',
        ]);

        $evaluation = Evaluation::with('stage')->findOrFail($evaluationId);
        $user = $request->user();

        // Get attempt number
        $attemptNumber = ChallengeResult::where('user_id', $user->id)
            ->where('evaluation_id', $evaluationId)
            ->count() + 1;

        // Upload file
        $file = $request->file('drawing_image');
        $filename = 'drawing_' . $user->id . '_' . time() . '_' . Str::random(10) . '.' . $file->extension();
        $path = $file->storeAs('public/drawings', $filename);
        $userDrawingUrl = Storage::url($path);

        try {
            // Call ML service
            $similarityScore = $this->drawingService->evaluateDrawing(
                $evaluation->reference_image_url,
                url($userDrawingUrl)
            );

            $isPassed = $this->drawingService->isPassed($similarityScore, $evaluation->min_similarity_score);

            // Save result
            $result = ChallengeResult::create([
                'user_id' => $user->id,
                'evaluation_id' => $evaluationId,
                'user_drawing_url' => $userDrawingUrl,
                'similarity_score' => $similarityScore,
                'is_passed' => $isPassed,
                'attempt_number' => $attemptNumber,
            ]);

            $response = [
                'result_id' => $result->id,
                'similarity_score' => $similarityScore,
                'is_passed' => $isPassed,
                'xp_earned' => 0,
                'level_up' => false,
                'new_badges' => [],
                'next_stage_unlocked' => null,
            ];

            if ($isPassed) {
                // Add XP
                $xpResult = $this->gamificationService->addXP($user->id, $evaluation->stage->xp_reward);
                $response['xp_earned'] = $evaluation->stage->xp_reward;
                $response['level_up'] = $xpResult['level_up'];
                $response['new_badges'] = $xpResult['new_badges'];

                // Complete stage
                $progressResult = $this->progressService->completeStage($user->id, $evaluation->stage_id);
                $response['next_stage_unlocked'] = $progressResult['next_stage_unlocked'];
            }

            return response()->json(['success' => true, 'data' => $response]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}
