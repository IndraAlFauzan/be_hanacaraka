<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Evaluation;
use App\Models\ChallengeResult;
use Illuminate\Http\Request;

class EvaluationController extends Controller
{
    public function show($stageId, Request $request)
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
                'id' => $evaluation->id,
                'stage_id' => $evaluation->stage_id,
                'character_target' => $evaluation->character_target,
                'reference_image_url' => $evaluation->reference_image_url,
                'min_similarity_score' => $evaluation->min_similarity_score,
                'user_attempts' => $userAttempts,
                'user_best_score' => $userBestScore,
            ],
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'stage_id' => 'required|exists:stages,id',
            'character_target' => 'required|string|max:10',
            'reference_image_url' => 'required|url',
            'min_similarity_score' => 'numeric|min:0|max:100',
        ]);

        $evaluation = Evaluation::create($request->all());
        return response()->json(['success' => true, 'data' => $evaluation], 201);
    }
}
