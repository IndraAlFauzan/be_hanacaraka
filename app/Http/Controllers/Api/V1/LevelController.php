<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Level;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class LevelController extends Controller
{
    public function index(Request $request)
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
        return response()->json(['success' => true, 'data' => $levelsData]);
    }

    public function show($id)
    {
        $level = Level::with('stages')->findOrFail($id);
        return response()->json([
            'success' => true,
            'data' => [
                'id' => $level->id,
                'level_number' => $level->level_number,
                'title' => $level->title,
                'description' => $level->description,
                'xp_required' => $level->xp_required,
                'is_active' => $level->is_active,
                'stages' => $level->stages,
            ],
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'level_number' => 'required|integer|unique:levels,level_number',
            'title' => 'required|string|max:100',
            'description' => 'nullable|string',
            'xp_required' => 'required|integer|min:0',
            'is_active' => 'boolean',
        ]);
        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }
        $level = Level::create($request->all());
        return response()->json(['success' => true, 'message' => 'Level created', 'data' => $level], 201);
    }

    public function update(Request $request, $id)
    {
        $level = Level::findOrFail($id);
        $validator = Validator::make($request->all(), [
            'level_number' => 'integer|unique:levels,level_number,' . $id,
            'title' => 'string|max:100',
            'xp_required' => 'integer|min:0',
        ]);
        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }
        $level->update($request->all());
        return response()->json(['success' => true, 'data' => $level]);
    }

    public function destroy($id)
    {
        Level::findOrFail($id)->delete();
        return response()->json(['success' => true, 'message' => 'Level deleted']);
    }
}
