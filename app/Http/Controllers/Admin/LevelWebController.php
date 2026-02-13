<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreLevelRequest;
use App\Http\Requests\Admin\UpdateLevelRequest;
use App\Models\Level;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class LevelWebController extends Controller
{
    public function index(): View
    {
        $levels = Level::withCount('stages')
            ->orderBy('level_number')
            ->paginate(20);

        return view('admin.levels.index', compact('levels'));
    }

    public function create(): View
    {
        return view('admin.levels.create');
    }

    public function store(StoreLevelRequest $request): RedirectResponse
    {
        Level::create($request->validated());

        return redirect()
            ->route('admin.levels.index')
            ->with('success', 'Level berhasil ditambahkan!');
    }

    public function show(string $id): View
    {
        $level = Level::with('stages')->findOrFail($id);

        return view('admin.levels.show', compact('level'));
    }

    public function edit(string $id): View
    {
        $level = Level::with(['stages.materials', 'stages.quizzes'])->findOrFail($id);

        return view('admin.levels.edit', compact('level'));
    }

    public function update(UpdateLevelRequest $request, string $id): RedirectResponse
    {
        $level = Level::findOrFail($id);
        $level->update($request->validated());

        return redirect()
            ->route('admin.levels.index')
            ->with('success', 'Level berhasil diupdate!');
    }

    public function destroy(string $id): RedirectResponse
    {
        Level::findOrFail($id)->delete();

        return redirect()
            ->route('admin.levels.index')
            ->with('success', 'Level berhasil dihapus!');
    }
}
