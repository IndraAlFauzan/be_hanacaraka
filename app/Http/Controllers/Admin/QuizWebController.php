<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreQuizRequest;
use App\Http\Requests\Admin\UpdateQuizRequest;
use App\Models\Quiz;
use App\Models\Stage;
use App\Services\QuizService;
use Illuminate\Http\Request;

class QuizWebController extends Controller
{
    protected QuizService $quizService;

    public function __construct(QuizService $quizService)
    {
        $this->quizService = $quizService;
    }

    /**
     * Display a listing of quizzes.
     */
    public function index(Request $request)
    {
        $query = Quiz::with(['stage.level'])
            ->withCount('questions');

        if ($request->filled('stage_id')) {
            $query->where('stage_id', $request->stage_id);
        }

        $quizzes = $query->orderBy('stage_id')->paginate(20);
        $stages = Stage::with('level')->orderBy('level_id')->orderBy('stage_number')->get();

        return view('admin.quizzes.index', compact('quizzes', 'stages'));
    }

    /**
     * Show the form for creating a new quiz.
     */
    public function create()
    {
        $stages = Stage::with('level')->orderBy('level_id')->orderBy('stage_number')->get();
        return view('admin.quizzes.create', compact('stages'));
    }

    /**
     * Store a newly created quiz in storage.
     */
    public function store(StoreQuizRequest $request)
    {
        $this->quizService->createQuiz($request->validated(), $request);

        return redirect()->route('admin.quizzes.index')
            ->with('success', 'Kuis berhasil ditambahkan!');
    }

    /**
     * Display the specified quiz.
     */
    public function show(string $id)
    {
        $quiz = Quiz::with(['stage.level', 'questions'])->findOrFail($id);
        return view('admin.quizzes.show', compact('quiz'));
    }

    /**
     * Show the form for editing the specified quiz.
     */
    public function edit(string $id)
    {
        $quiz = Quiz::with(['stage.level', 'questions'])->findOrFail($id);
        $stages = Stage::with('level')->orderBy('level_id')->orderBy('stage_number')->get();
        return view('admin.quizzes.edit', compact('quiz', 'stages'));
    }

    /**
     * Update the specified quiz in storage.
     */
    public function update(UpdateQuizRequest $request, string $id)
    {
        $quiz = Quiz::findOrFail($id);

        $this->quizService->updateQuiz($quiz, $request->validated(), $request);

        return redirect()->route('admin.quizzes.index')
            ->with('success', 'Kuis berhasil diperbarui!');
    }

    /**
     * Remove the specified quiz from storage.
     */
    public function destroy(string $id)
    {
        $quiz = Quiz::with('questions')->findOrFail($id);

        $this->quizService->deleteQuiz($quiz);

        return redirect()->route('admin.quizzes.index')
            ->with('success', 'Kuis berhasil dihapus!');
    }
}
