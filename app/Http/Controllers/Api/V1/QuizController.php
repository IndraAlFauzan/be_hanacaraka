<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\SubmitQuizRequest;
use App\Http\Resources\V1\QuizResource;
use App\Models\Quiz;
use App\Models\QuizQuestion;
use App\Models\QuizResult;
use App\Services\GamificationService;
use App\Services\ProgressService;
use App\Services\QuizService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class QuizController extends Controller
{
    public function __construct(
        protected GamificationService $gamificationService,
        protected ProgressService $progressService,
        protected QuizService $quizService
    ) {}

    /**
     * Get quiz by stage ID for mobile app
     */
    public function show(int $stageId): JsonResponse
    {
        $quiz = Quiz::with('questions')
            ->where('stage_id', $stageId)
            ->firstOrFail();

        return response()->json([
            'success' => true,
            'data' => new QuizResource($quiz),
        ]);
    }

    /**
     * Submit quiz answers and calculate score
     */
    public function submit(SubmitQuizRequest $request, int $quizId): JsonResponse
    {
        $quiz = Quiz::with(['questions', 'stage'])->findOrFail($quizId);
        $user = $request->user();
        $answers = $request->validated()['answers'];

        // Calculate score
        $result = $this->calculateQuizResult($quiz, $answers);

        // Get attempt number
        $attemptNumber = QuizResult::where('user_id', $user->id)
            ->where('quiz_id', $quizId)
            ->count() + 1;

        // Save result
        $quizResult = QuizResult::create([
            'user_id' => $user->id,
            'quiz_id' => $quizId,
            'score' => $result['score'],
            'is_passed' => $result['is_passed'],
            'answers' => $result['processed_answers'],
            'attempt_number' => $attemptNumber,
        ]);

        $response = [
            'result_id' => $quizResult->id,
            'score' => $result['score'],
            'is_passed' => $result['is_passed'],
            'correct_answers' => $result['correct_count'],
            'total_questions' => $result['total_questions'],
            'xp_earned' => 0,
        ];

        // Award XP and update progress if passed
        if ($result['is_passed']) {
            $this->gamificationService->addXP($user->id, $quiz->stage->xp_reward);
            $response['xp_earned'] = $quiz->stage->xp_reward;
            $this->progressService->completeStage($user->id, $quiz->stage_id);
        }

        return response()->json([
            'success' => true,
            'data' => $response,
        ]);
    }

    /**
     * Store new quiz with questions (Admin only)
     */
    public function store(Request $request): JsonResponse
    {
        $request->validate([
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
            'questions.*.order_index' => 'nullable|integer',
        ]);

        try {
            DB::beginTransaction();

            $quiz = Quiz::create([
                'stage_id' => $request->stage_id,
                'title' => $request->title,
                'passing_score' => $request->passing_score ?? 60,
            ]);

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
                    'order_index' => $questionData['order_index'] ?? $index,
                ]);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Quiz created successfully',
                'data' => new QuizResource($quiz->load('questions')),
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to create quiz: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Update quiz (Admin only)
     */
    public function update(Request $request, int $id): JsonResponse
    {
        $request->validate([
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
            'questions.*.order_index' => 'nullable|integer',
        ]);

        try {
            DB::beginTransaction();

            $quiz = Quiz::findOrFail($id);
            $quiz->update($request->only(['title', 'passing_score']));

            if ($request->has('questions')) {
                $newQuestionIds = collect($request->questions)->pluck('id')->filter();
                QuizQuestion::where('quiz_id', $quiz->id)
                    ->whereNotIn('id', $newQuestionIds)
                    ->delete();

                foreach ($request->questions as $index => $questionData) {
                    if (isset($questionData['id'])) {
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
                                'order_index' => $questionData['order_index'] ?? $index,
                            ]);
                    } else {
                        QuizQuestion::create([
                            'quiz_id' => $quiz->id,
                            'question_text' => $questionData['question_text'],
                            'question_image_url' => $questionData['question_image_url'] ?? null,
                            'option_a' => $questionData['option_a'],
                            'option_b' => $questionData['option_b'],
                            'option_c' => $questionData['option_c'],
                            'option_d' => $questionData['option_d'],
                            'correct_answer' => $questionData['correct_answer'],
                            'order_index' => $questionData['order_index'] ?? $index,
                        ]);
                    }
                }
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Quiz updated successfully',
                'data' => new QuizResource($quiz->load('questions')),
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to update quiz: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Delete quiz (Admin only)
     */
    public function destroy(int $id): JsonResponse
    {
        try {
            $quiz = Quiz::findOrFail($id);
            $this->quizService->deleteQuiz($quiz);

            return response()->json([
                'success' => true,
                'message' => 'Quiz deleted successfully',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete quiz: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Calculate quiz result from answers
     */
    private function calculateQuizResult(Quiz $quiz, array $answers): array
    {
        $correctCount = 0;
        $processedAnswers = [];

        foreach ($answers as $answer) {
            $question = $quiz->questions->firstWhere('id', $answer['question_id']);
            $isCorrect = $question && $question->correct_answer === $answer['selected_answer'];

            if ($isCorrect) {
                $correctCount++;
            }

            $processedAnswers[] = [
                'question_id' => $answer['question_id'],
                'selected_answer' => $answer['selected_answer'],
                'is_correct' => $isCorrect,
            ];
        }

        $totalQuestions = $quiz->questions->count();
        $score = $totalQuestions > 0 ? ($correctCount / $totalQuestions) * 100 : 0;
        $isPassed = $score >= $quiz->passing_score;

        return [
            'correct_count' => $correctCount,
            'total_questions' => $totalQuestions,
            'score' => $score,
            'is_passed' => $isPassed,
            'processed_answers' => $processedAnswers,
        ];
    }
}
