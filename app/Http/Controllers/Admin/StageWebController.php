<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Stage;
use App\Models\Level;
use Illuminate\Http\Request;

class StageWebController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Stage::with('level')
            ->withCount(['materials', 'quizzes', 'evaluations']);

        if ($request->filled('level_id')) {
            $query->where('level_id', $request->level_id);
        }

        $stages = $query->orderBy('level_id')->orderBy('stage_number')->paginate(20);
        $levels = Level::orderBy('level_number')->get();

        return view('admin.stages.index', compact('stages', 'levels'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $levels = Level::orderBy('level_number')->get();
        return view('admin.stages.create', compact('levels'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'level_id' => 'required|exists:levels,id',
            'stage_number' => 'required|integer|min:1',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'xp_reward' => 'required|integer|min:0',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');

        Stage::create($validated);
        return redirect()->route('admin.stages.index')
            ->with('success', 'Stage berhasil ditambahkan!');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $stage = Stage::with(['materials', 'quizzes', 'evaluations'])->findOrFail($id);
        $levels = Level::orderBy('level_number')->get();
        return view('admin.stages.edit', compact('stage', 'levels'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $stage = Stage::findOrFail($id);

        $validated = $request->validate([
            'level_id' => 'required|exists:levels,id',
            'stage_number' => 'required|integer|min:1',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'xp_reward' => 'required|integer|min:0',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');

        $stage->update($validated);
        return redirect()->route('admin.stages.index')
            ->with('success', 'Stage berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $stage = Stage::findOrFail($id);
        $stage->delete();

        return redirect()->route('admin.stages.index')
            ->with('success', 'Stage berhasil dihapus!');
    }
}
