<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreStageRequest;
use App\Http\Requests\Admin\UpdateStageRequest;
use App\Models\Level;
use App\Models\Stage;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class StageWebController extends Controller
{
    public function index(Request $request): View
    {
        $query = Stage::with('level')
            ->withCount(['materials', 'quizzes', 'evaluations']);

        if ($request->filled('level_id')) {
            $query->where('level_id', $request->level_id);
        }

        $stages = $query->orderBy('level_id')
            ->orderBy('stage_number')
            ->paginate(20);

        $levels = Level::orderBy('level_number')->get();

        return view('admin.stages.index', compact('stages', 'levels'));
    }

    public function create(): View
    {
        $levels = Level::orderBy('level_number')->get();

        return view('admin.stages.create', compact('levels'));
    }

    public function store(StoreStageRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $validated['is_active'] = $request->has('is_active');

        Stage::create($validated);

        return redirect()
            ->route('admin.stages.index')
            ->with('success', 'Stage berhasil ditambahkan!');
    }

    public function edit(string $id): View
    {
        $stage = Stage::with(['materials', 'quizzes', 'evaluations'])->findOrFail($id);
        $levels = Level::orderBy('level_number')->get();

        return view('admin.stages.edit', compact('stage', 'levels'));
    }

    public function update(UpdateStageRequest $request, string $id): RedirectResponse
    {
        $stage = Stage::findOrFail($id);

        $validated = $request->validated();
        $validated['is_active'] = $request->has('is_active');

        $stage->update($validated);

        return redirect()
            ->route('admin.stages.index')
            ->with('success', 'Stage berhasil diperbarui!');
    }

    public function destroy(string $id): RedirectResponse
    {
        Stage::findOrFail($id)->delete();

        return redirect()
            ->route('admin.stages.index')
            ->with('success', 'Stage berhasil dihapus!');
    }
}
