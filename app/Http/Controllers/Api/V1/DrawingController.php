<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\StoreEvaluationRequest;
use App\Http\Requests\Api\V1\SubmitDrawingRequest;
use App\Http\Resources\V1\DrawingChallengeResource;
use App\Http\Resources\V1\DrawingResultResource;
use App\Models\ChallengeResult;
use App\Models\Evaluation;
use App\Models\Stage;
use App\Services\DrawingService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DrawingController extends Controller
{
    public function __construct(
        protected DrawingService $drawingService
    ) {}

    /**
     * Get drawing challenge for a stage
     * Includes reference image, min similarity score, and user attempt stats
     *
     * @param int $stageId
     * @param Request $request
     * @return JsonResponse
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

        // Add user stats to evaluation for resource
        $evaluation->user_attempts = $userAttempts;
        $evaluation->user_best_score = $userBestScore;

        return response()->json([
            'success' => true,
            'data' => new DrawingChallengeResource($evaluation),
        ], 200);
    }

    /**
     * Submit user drawing for evaluation
     * Similarity score is calculated by TFLite model on mobile app
     *
     * @param SubmitDrawingRequest $request
     * @param int $stageId
     * @return JsonResponse
     */
    public function submit(SubmitDrawingRequest $request, int $stageId): JsonResponse
    {
        $stage = Stage::findOrFail($stageId);
        $user = $request->user();
        $validated = $request->validated();

        $resultData = $this->drawingService->submitDrawing(
            user: $user,
            stage: $stage,
            drawingImage: $request->file('drawing_image'),
            similarityScore: (float) $validated['similarity_score']
        );

        $resource = (new DrawingResultResource($resultData['result']))
            ->setAdditionalData($resultData['additional_data']);

        return response()->json([
            'success' => true,
            'message' => 'Drawing submitted successfully',
            'data' => $resource,
        ], 201);
    }

    /**
     * Create new drawing challenge (Admin only)
     *
     * @param StoreEvaluationRequest $request
     * @return JsonResponse
     */
    public function store(StoreEvaluationRequest $request): JsonResponse
    {
        $evaluation = Evaluation::create($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Drawing challenge created successfully',
            'data' => new DrawingChallengeResource($evaluation),
        ], 201);
    }
}
