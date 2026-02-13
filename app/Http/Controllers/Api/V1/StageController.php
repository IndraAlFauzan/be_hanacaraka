<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\StoreStageRequest;
use App\Http\Resources\V1\StageResource;
use App\Models\Stage;
use App\Services\ProgressService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class StageController extends Controller
{
    public function __construct(
        protected ProgressService $progressService
    ) {}

    /**
     * List stages with user progress status
     */
    public function index(Request $request): JsonResponse
    {
        $query = Stage::with(['level', 'materials', 'evaluations', 'quizzes']);

        if ($request->has('level_id')) {
            $query->where('level_id', $request->level_id);
        }

        if ($request->has('is_active')) {
            $query->where('is_active', $request->is_active);
        }

        $stages = $query->orderBy('level_id')->orderBy('stage_number')->get();
        $user = $request->user();

        $stagesData = $stages->map(function ($stage) use ($user) {
            $isUnlocked = false;
            $status = 'locked';

            if ($user) {
                $isUnlocked = $this->progressService->isStageUnlocked($user->id, $stage->id);
                $progress = $user->progress()->where('stage_id', $stage->id)->first();
                $status = $progress ? $progress->status : 'locked';
            }

            return [
                'id' => $stage->id,
                'level_id' => $stage->level_id,
                'stage_number' => $stage->stage_number,
                'title' => $stage->title,
                'xp_reward' => $stage->xp_reward,
                'is_active' => $stage->is_active,
                'is_unlocked' => $isUnlocked,
                'status' => $status,
                'has_material' => $stage->materials()->exists(),
                'has_evaluation' => $stage->evaluations()->exists(),
                'has_quiz' => $stage->quizzes()->exists(),
            ];
        });

        return response()->json([
            'success' => true,
            'data' => $stagesData,
        ]);
    }

    /**
     * Show stage detail with all relationships
     */
    public function show(int $id): JsonResponse
    {
        $stage = Stage::with(['level', 'materials', 'evaluations', 'quizzes.questions'])
            ->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => new StageResource($stage),
        ]);
    }

    /**
     * Store new stage (Admin only)
     */
    public function store(StoreStageRequest $request): JsonResponse
    {
        $stage = Stage::create($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Stage created successfully',
            'data' => new StageResource($stage),
        ], 201);
    }

    /**
     * Update stage (Admin only)
     */
    public function update(Request $request, int $id): JsonResponse
    {
        $stage = Stage::findOrFail($id);
        $stage->update($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Stage updated successfully',
            'data' => new StageResource($stage),
        ]);
    }

    /**
     * Delete stage (Admin only)
     */
    public function destroy(int $id): JsonResponse
    {
        Stage::findOrFail($id)->delete();

        return response()->json([
            'success' => true,
            'message' => 'Stage deleted successfully',
        ]);
    }
}
