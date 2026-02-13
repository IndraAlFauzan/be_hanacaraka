<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\StoreLevelRequest;
use App\Http\Resources\V1\LevelResource;
use App\Models\Level;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class LevelController extends Controller
{
    /**
     * List levels with unlock status for current user
     */
    public function index(Request $request): JsonResponse
    {
        $query = Level::query();

        if ($request->has('is_active')) {
            $query->where('is_active', $request->is_active);
        }

        $levels = $query->orderBy('level_number')->get();
        $user = $request->user();

        $levelsData = $levels->map(function ($level) use ($user) {
            return [
                'id' => $level->id,
                'level_number' => $level->level_number,
                'title' => $level->title,
                'description' => $level->description,
                'xp_required' => $level->xp_required,
                'is_active' => $level->is_active,
                'is_unlocked' => $user ? $user->total_xp >= $level->xp_required : false,
                'total_stages' => $level->stages()->count(),
            ];
        });

        return response()->json([
            'success' => true,
            'data' => $levelsData,
        ]);
    }

    /**
     * Show level detail with stages
     */
    public function show(int $id): JsonResponse
    {
        $level = Level::with('stages')->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => new LevelResource($level),
        ]);
    }

    /**
     * Store new level (Admin only)
     */
    public function store(StoreLevelRequest $request): JsonResponse
    {
        $level = Level::create($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Level created successfully',
            'data' => new LevelResource($level),
        ], 201);
    }

    /**
     * Update level (Admin only)
     */
    public function update(Request $request, int $id): JsonResponse
    {
        $level = Level::findOrFail($id);
        $level->update($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Level updated successfully',
            'data' => new LevelResource($level),
        ]);
    }

    /**
     * Delete level (Admin only)
     */
    public function destroy(int $id): JsonResponse
    {
        Level::findOrFail($id)->delete();

        return response()->json([
            'success' => true,
            'message' => 'Level deleted successfully',
        ]);
    }
}
