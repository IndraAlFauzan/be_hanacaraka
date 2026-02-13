<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Stage;
use App\Services\ProgressService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class StageController extends Controller
{
    protected $progressService;

    public function __construct(ProgressService $progressService)
    {
        $this->progressService = $progressService;
    }

    public function index(Request $request)
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
        return response()->json(['success' => true, 'data' => $stagesData]);
    }

    public function show($id)
    {
        $stage = Stage::with(['level', 'materials', 'evaluations', 'quizzes.questions'])->findOrFail($id);
        return response()->json(['success' => true, 'data' => $stage]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'level_id' => 'required|integer|exists:levels,id',
            'stage_number' => 'required|integer',
            'title' => 'required|string|max:100',
            'xp_reward' => 'integer|min:1',
            'is_active' => 'boolean',
        ]);
        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }
        $stage = Stage::create($request->all());
        return response()->json(['success' => true, 'data' => $stage], 201);
    }

    public function update(Request $request, $id)
    {
        $stage = Stage::findOrFail($id);
        $stage->update($request->all());
        return response()->json(['success' => true, 'data' => $stage]);
    }

    public function destroy($id)
    {
        Stage::findOrFail($id)->delete();
        return response()->json(['success' => true, 'message' => 'Stage deleted']);
    }
}
