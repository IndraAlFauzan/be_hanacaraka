<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Level;
use Illuminate\Http\Request;

class LevelWebController extends Controller
{
    public function index()
    {
        $levels = Level::withCount('stages')
            ->orderBy('level_number')
            ->paginate(20);
        return view('admin.levels.index', compact('levels'));
    }

    public function create()
    {
        return view('admin.levels.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'level_number' => 'required|integer|unique:levels',
            'title' => 'required|string|max:100',
            'description' => 'nullable|string',
            'xp_required' => 'required|integer|min:0',
            'is_active' => 'boolean',
        ]);

        Level::create($validated);
        return redirect()->route('admin.levels.index')
            ->with('success', 'Level berhasil ditambahkan!');
    }

    public function show(string $id)
    {
        $level = Level::with('stages')->findOrFail($id);
        return view('admin.levels.show', compact('level'));
    }

    public function edit(string $id)
    {
        $level = Level::with(['stages.materials', 'stages.quizzes'])->findOrFail($id);
        return view('admin.levels.edit', compact('level'));
    }

    public function update(Request $request, string $id)
    {
        $level = Level::findOrFail($id);

        $validated = $request->validate([
            'level_number' => 'required|integer|unique:levels,level_number,' . $id,
            'title' => 'required|string|max:100',
            'description' => 'nullable|string',
            'xp_required' => 'required|integer|min:0',
            'is_active' => 'boolean',
        ]);

        $level->update($validated);
        return redirect()->route('admin.levels.index')
            ->with('success', 'Level berhasil diupdate!');
    }

    public function destroy(string $id)
    {
        $level = Level::findOrFail($id);
        $level->delete();
        return redirect()->route('admin.levels.index')
            ->with('success', 'Level berhasil dihapus!');
    }
}
