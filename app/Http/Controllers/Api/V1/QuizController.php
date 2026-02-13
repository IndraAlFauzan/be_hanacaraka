<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Quiz;
use App\Models\QuizQuestion;
use App\Models\QuizResult;
use App\Services\GamificationService;
use App\Services\ProgressService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class QuizController extends Controller
{
    protected $gamificationService;
    protected $progressService;

    public function __construct(GamificationService $gamificationService, ProgressService $progressService)
    {
        $this->gamificationService = $gamificationService;
        $this->progressService = $progressService;
    }

    public function show($stageId)
    {
        $quiz = Quiz::with('questions')->where('stage_id', $stageId)->firstOrFail();
        $questions = $quiz->questions->map(function ($q) {
            return [
                'id' => $q->id,
                'question_text' => $q->question_text,
                'question_image_url' => $q->question_image_url,
                'option_a' => $q->option_a,
                'option_b' => $q->option_b,
                'option_c' => $q->option_c,
                'option_d' => $q->option_d,
            ];
        });

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $quiz->id,
                'title' => $quiz->title,
                'passing_score' => $quiz->passing_score,
                'questions' => $questions,
            ],
        ]);
    }

    public function submit(Request $request, $quizId)
    {
        $request->validate([
            'answers' => 'required|array',
            'answers.*.question_id' => 'required|exists:quiz_questions,id',
            'answers.*.selected_answer' => 'required|in:a,b,c,d',
        ]);

        $quiz = Quiz::with(['questions', 'stage'])->findOrFail($quizId);
        $user = $request->user();
        $answers = $request->answers;

        // Calculate score
        $correctCount = 0;
        $processedAnswers = [];

        foreach ($answers as $answer) {
            $question = $quiz->questions->firstWhere('id', $answer['question_id']);
            $isCorrect = $question->correct_answer === $answer['selected_answer'];
            if ($isCorrect) $correctCount++;

            $processedAnswers[] = [
                'question_id' => $answer['question_id'],
                'selected_answer' => $answer['selected_answer'],
                'is_correct' => $isCorrect,
            ];
        }

        $totalQuestions = $quiz->questions->count();
        $score = ($correctCount / $totalQuestions) * 100;
        $isPassed = $score >= $quiz->passing_score;

        // Get attempt number
        $attemptNumber = QuizResult::where('user_id', $user->id)
            ->where('quiz_id', $quizId)
            ->count() + 1;

        // Save result
        $result = QuizResult::create([
            'user_id' => $user->id,
            'quiz_id' => $quizId,
            'score' => $score,
            'is_passed' => $isPassed,
            'answers' => $processedAnswers,
            'attempt_number' => $attemptNumber,
        ]);

        $response = [
            'result_id' => $result->id,
            'score' => $score,
            'is_passed' => $isPassed,
            'correct_answers' => $correctCount,
            'total_questions' => $totalQuestions,
            'xp_earned' => 0,
        ];

        if ($isPassed) {
            $xpResult = $this->gamificationService->addXP($user->id, $quiz->stage->xp_reward);
            $response['xp_earned'] = $quiz->stage->xp_reward;
            $progressResult = $this->progressService->completeStage($user->id, $quiz->stage_id);
        }

        return response()->json(['success' => true, 'data' => $response]);
    }

    /**
     * Store new quiz with questions (Admin only)
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'stage_id' => 'required|exists:stages,id|unique:quizzes,stage_id',
            'title' => 'nullable|string|max:100',
            'passing_score' => 'integer|between:0,100',
            'questions' => 'required|array|min:1',
            'questions.*.question_text' => 'required|string',
            'questions.*.question_image_url' => 'nullable|url',
            'questions.*.option_a' => 'required|string',
            'questions.*.option_b' => 'required|string',
            'questions.*.option_c' => 'required|string',
            'questions.*.option_d' => 'required|string',
            'questions.*.correct_answer' => 'required|in:a,b,c,d',
            'questions.*.order_index' => 'nullable|integer'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            DB::beginTransaction();

            // Create quiz
            $quiz = Quiz::create([
                'stage_id' => $request->stage_id,
                'title' => $request->title,
                'passing_score' => $request->passing_score ?? 60
            ]);

            // Create questions
            foreach ($request->questions as $index => $questionData) {
                QuizQuestion::create([
                    'quiz_id' => $quiz->id,
                    'question_text' => $questionData['question_text'],
                    'question_image_url' => $questionData['question_image_url'] ?? null,
                    'option_a' => $questionData['option_a'],
                    'option_b' => $questionData['option_b'],
                    'option_c' => $questionData['option_c'],
                    'option_d' => $questionData['option_d'],
                    'correct_answer' => $questionData['correct_answer'],
                    'order_index' => $questionData['order_index'] ?? $index
                ]);
            }

            DB::commit();

            $quiz->load('questions');

            return response()->json([
                'success' => true,
                'message' => 'Quiz created successfully',
                'data' => $quiz
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to create quiz: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update existing quiz (Admin only)
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'sometimes|string|max:100',
            'passing_score' => 'sometimes|integer|between:0,100',
            'questions' => 'sometimes|array|min:1',
            'questions.*.id' => 'nullable|exists:quiz_questions,id',
            'questions.*.question_text' => 'required|string',
            'questions.*.question_image_url' => 'nullable|url',
            'questions.*.option_a' => 'required|string',
            'questions.*.option_b' => 'required|string',
            'questions.*.option_c' => 'required|string',
            'questions.*.option_d' => 'required|string',
            'questions.*.correct_answer' => 'required|in:a,b,c,d',
            'questions.*.order_index' => 'nullable|integer'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            DB::beginTransaction();

            $quiz = Quiz::findOrFail($id);

            // Update quiz
            $quiz->update($request->only(['title', 'passing_score']));

            // Update questions if provided
            if ($request->has('questions')) {
                // Delete old questions not in update
                $newQuestionIds = collect($request->questions)->pluck('id')->filter();
                QuizQuestion::where('quiz_id', $quiz->id)
                    ->whereNotIn('id', $newQuestionIds)
                    ->delete();

                // Update or create questions
                foreach ($request->questions as $index => $questionData) {
                    if (isset($questionData['id'])) {
                        // Update existing
                        QuizQuestion::where('id', $questionData['id'])
                            ->where('quiz_id', $quiz->id)
                            ->update([
                                'question_text' => $questionData['question_text'],
                                'question_image_url' => $questionData['question_image_url'] ?? null,
                                'option_a' => $questionData['option_a'],
                                'option_b' => $questionData['option_b'],
                                'option_c' => $questionData['option_c'],
                                'option_d' => $questionData['option_d'],
                                'correct_answer' => $questionData['correct_answer'],
                                'order_index' => $questionData['order_index'] ?? $index
                            ]);
                    } else {
                        // Create new
                        QuizQuestion::create([
                            'quiz_id' => $quiz->id,
                            'question_text' => $questionData['question_text'],
                            'question_image_url' => $questionData['question_image_url'] ?? null,
                            'option_a' => $questionData['option_a'],
                            'option_b' => $questionData['option_b'],
                            'option_c' => $questionData['option_c'],
                            'option_d' => $questionData['option_d'],
                            'correct_answer' => $questionData['correct_answer'],
                            'order_index' => $questionData['order_index'] ?? $index
                        ]);
                    }
                }
            }

            DB::commit();

            $quiz->load('questions');

            return response()->json([
                'success' => true,
                'message' => 'Quiz updated successfully',
                'data' => $quiz
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to update quiz: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete quiz (Admin only)
     */
    public function destroy($id)
    {
        try {
            $quiz = Quiz::findOrFail($id);
            $quiz->delete(); // Cascade delete questions via model relationship

            return response()->json([
                'success' => true,
                'message' => 'Quiz deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete quiz: ' . $e->getMessage()
            ], 500);
        }
    }
}
