<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Evaluation;
use App\Models\Stage;
use Illuminate\Http\Request;

class EvaluationWebController extends Controller
{
    public function index(Request $request)
    {
        $query = Evaluation::with(['stage.level'])
            ->withCount('submissions');

        if ($request->filled('stage_id')) {
            $query->where('stage_id', $request->stage_id);
        }

        $evaluations = $query->orderBy('stage_id')->paginate(20);
        $stages = Stage::with('level')->orderBy('level_id')->orderBy('stage_number')->get();

        return view('admin.evaluations.index', compact('evaluations', 'stages'));
    }

    public function create()
    {
        $stages = Stage::with('level')->orderBy('level_id')->orderBy('stage_number')->get();
        return view('admin.evaluations.create', compact('stages'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'stage_id' => 'required|exists:stages,id',
            'character_target' => 'required|string|max:10',
            'reference_image_url' => 'required|url|max:255',
            'min_similarity_score' => 'required|numeric|min:0|max:100',
        ]);

        Evaluation::create($validated);
        return redirect()->route('admin.evaluations.index')
            ->with('success', 'Evaluasi berhasil ditambahkan!');
    }

    public function edit(string $id)
    {
        $evaluation = Evaluation::with(['stage.level', 'submissions.user'])->findOrFail($id);
        $stages = Stage::with('level')->orderBy('level_id')->orderBy('stage_number')->get();
        return view('admin.evaluations.edit', compact('evaluation', 'stages'));
    }

    public function update(Request $request, string $id)
    {
        $evaluation = Evaluation::findOrFail($id);

        $validated = $request->validate([
            'stage_id' => 'required|exists:stages,id',
            'character_target' => 'required|string|max:10',
            'reference_image_url' => 'required|url|max:255',
            'min_similarity_score' => 'required|numeric|min:0|max:100',
        ]);

        $evaluation->update($validated);
        return redirect()->route('admin.evaluations.index')
            ->with('success', 'Evaluasi berhasil diperbarui!');
    }

    public function destroy(string $id)
    {
        $evaluation = Evaluation::findOrFail($id);
        $evaluation->delete();

        return redirect()->route('admin.evaluations.index')
            ->with('success', 'Evaluasi berhasil dihapus!');
    }
}
