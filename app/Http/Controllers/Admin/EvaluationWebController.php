<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreEvaluationRequest;
use App\Http\Requests\Admin\UpdateEvaluationRequest;
use App\Models\Evaluation;
use App\Models\Stage;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class EvaluationWebController extends Controller
{
    public function index(Request $request): View
    {
        $query = Evaluation::with(['stage.level'])
            ->withCount('submissions');

        if ($request->filled('stage_id')) {
            $query->where('stage_id', $request->stage_id);
        }

        $evaluations = $query->orderBy('stage_id')->paginate(20);
        $stages = Stage::with('level')
            ->orderBy('level_id')
            ->orderBy('stage_number')
            ->get();

        return view('admin.evaluations.index', compact('evaluations', 'stages'));
    }

    public function create(): View
    {
        $stages = Stage::with('level')
            ->orderBy('level_id')
            ->orderBy('stage_number')
            ->get();

        return view('admin.evaluations.create', compact('stages'));
    }

    public function store(StoreEvaluationRequest $request): RedirectResponse
    {
        Evaluation::create($request->validated());

        return redirect()
            ->route('admin.evaluations.index')
            ->with('success', 'Evaluasi berhasil ditambahkan!');
    }

    public function edit(string $id): View
    {
        $evaluation = Evaluation::with(['stage.level', 'submissions.user'])->findOrFail($id);
        $stages = Stage::with('level')
            ->orderBy('level_id')
            ->orderBy('stage_number')
            ->get();

        return view('admin.evaluations.edit', compact('evaluation', 'stages'));
    }

    public function update(UpdateEvaluationRequest $request, string $id): RedirectResponse
    {
        $evaluation = Evaluation::findOrFail($id);
        $evaluation->update($request->validated());

        return redirect()
            ->route('admin.evaluations.index')
            ->with('success', 'Evaluasi berhasil diperbarui!');
    }

    public function destroy(string $id): RedirectResponse
    {
        Evaluation::findOrFail($id)->delete();

        return redirect()
            ->route('admin.evaluations.index')
            ->with('success', 'Evaluasi berhasil dihapus!');
    }
}
