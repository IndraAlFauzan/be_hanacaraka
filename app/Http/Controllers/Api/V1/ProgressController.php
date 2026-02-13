<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\UserProgress;
use App\Services\ProgressService;
use Illuminate\Http\Request;

class ProgressController extends Controller
{
    protected $progressService;

    public function __construct(ProgressService $progressService)
    {
        $this->progressService = $progressService;
    }

    public function show(Request $request, $userId)
    {
        if ($request->user()->id != $userId && !$request->user()->isAdmin()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $summary = $this->progressService->getUserProgressSummary($userId);
        $progress = UserProgress::with('stage.level')
            ->where('user_id', $userId)
            ->get()
            ->map(function ($p) {
                return [
                    'stage_id' => $p->stage_id,
                    'stage_title' => $p->stage->title,
                    'level_id' => $p->stage->level_id,
                    'status' => $p->status,
                    'completed_at' => $p->completed_at,
                ];
            });

        return response()->json([
            'success' => true,
            'data' => array_merge($summary, ['stages' => $progress]),
        ]);
    }
}
