<?php

namespace App\Services;

use App\Models\Quiz;
use App\Models\QuizQuestion;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class QuizService
{
    protected FileUploadService $fileUploadService;

    public function __construct(FileUploadService $fileUploadService)
    {
        $this->fileUploadService = $fileUploadService;
    }

    /**
     * Create a new quiz with questions
     *
     * @param array $data
     * @param Request $request
     * @return Quiz
     */
    public function createQuiz(array $data, Request $request): Quiz
    {
        $quiz = Quiz::create([
            'stage_id' => $data['stage_id'],
            'title' => $data['title'] ?? null,
            'passing_score' => $data['passing_score'],
        ]);

        $this->createQuestions($quiz, $request);

        return $quiz->load('questions');
    }

    /**
     * Update quiz with questions
     *
     * @param Quiz $quiz
     * @param array $data
     * @param Request $request
     * @return Quiz
     */
    public function updateQuiz(Quiz $quiz, array $data, Request $request): Quiz
    {
        $quiz->update([
            'stage_id' => $data['stage_id'],
            'title' => $data['title'] ?? null,
            'passing_score' => $data['passing_score'],
        ]);

        // Collect existing image URLs that should be kept
        $keepImages = $this->collectKeepImages($request);

        // Delete old questions and orphaned images
        $this->deleteOldQuestionsWithImages($quiz, $keepImages);

        // Create new questions
        $this->createQuestionsWithExisting($quiz, $request);

        return $quiz->load('questions');
    }

    /**
     * Delete quiz with all associated images
     *
     * @param Quiz $quiz
     * @return bool
     */
    public function deleteQuiz(Quiz $quiz): bool
    {
        // Delete all associated images
        foreach ($quiz->questions as $question) {
            $this->deleteQuestionImages($question);
        }

        return $quiz->delete();
    }

    /**
     * Create questions for a quiz
     */
    protected function createQuestions(Quiz $quiz, Request $request): void
    {
        foreach ($request->input('questions', []) as $index => $questionData) {
            $data = $this->prepareQuestionData($quiz->id, $questionData);

            // Handle question image
            if ($request->hasFile("questions.{$index}.question_image")) {
                $data['question_image_url'] = $this->fileUploadService->uploadImage(
                    $request->file("questions.{$index}.question_image"),
                    'quizzes',
                    'question'
                );
            }

            // Handle option images
            foreach (['a', 'b', 'c', 'd'] as $option) {
                $fieldName = "questions.{$index}.option_{$option}_image";
                if ($request->hasFile($fieldName)) {
                    $data["option_{$option}_image_url"] = $this->fileUploadService->uploadImage(
                        $request->file($fieldName),
                        'quizzes',
                        "option_{$option}"
                    );
                }
            }

            QuizQuestion::create($data);
        }
    }

    /**
     * Create questions with support for existing images
     */
    protected function createQuestionsWithExisting(Quiz $quiz, Request $request): void
    {
        foreach ($request->input('questions', []) as $index => $questionData) {
            $data = $this->prepareQuestionData($quiz->id, $questionData);

            // Handle question image
            if ($request->hasFile("questions.{$index}.question_image")) {
                // Delete old image if exists and upload new one
                if (isset($questionData['existing_question_image'])) {
                    $this->fileUploadService->deleteImage($questionData['existing_question_image'], 'quizzes');
                }
                $data['question_image_url'] = $this->fileUploadService->uploadImage(
                    $request->file("questions.{$index}.question_image"),
                    'quizzes',
                    'question'
                );
            } elseif (isset($questionData['existing_question_image'])) {
                $data['question_image_url'] = $questionData['existing_question_image'];
            }

            // Handle option images
            foreach (['a', 'b', 'c', 'd'] as $option) {
                $fieldName = "questions.{$index}.option_{$option}_image";
                if ($request->hasFile($fieldName)) {
                    if (isset($questionData["existing_option_{$option}_image"])) {
                        $this->fileUploadService->deleteImage($questionData["existing_option_{$option}_image"], 'quizzes');
                    }
                    $data["option_{$option}_image_url"] = $this->fileUploadService->uploadImage(
                        $request->file($fieldName),
                        'quizzes',
                        "option_{$option}"
                    );
                } elseif (isset($questionData["existing_option_{$option}_image"])) {
                    $data["option_{$option}_image_url"] = $questionData["existing_option_{$option}_image"];
                }
            }

            QuizQuestion::create($data);
        }
    }

    /**
     * Prepare base question data
     */
    protected function prepareQuestionData(int $quizId, array $questionData): array
    {
        return [
            'quiz_id' => $quizId,
            'question_text' => $questionData['question_text'],
            'option_a' => $questionData['option_a'],
            'option_b' => $questionData['option_b'],
            'option_c' => $questionData['option_c'],
            'option_d' => $questionData['option_d'],
            'correct_answer' => $questionData['correct_answer'],
        ];
    }

    /**
     * Collect image URLs that should be kept
     */
    protected function collectKeepImages(Request $request): array
    {
        $keepImages = [];

        foreach ($request->input('questions', []) as $questionData) {
            if (isset($questionData['existing_question_image'])) {
                $keepImages[] = $questionData['existing_question_image'];
            }
            foreach (['a', 'b', 'c', 'd'] as $option) {
                if (isset($questionData["existing_option_{$option}_image"])) {
                    $keepImages[] = $questionData["existing_option_{$option}_image"];
                }
            }
        }

        return $keepImages;
    }

    /**
     * Delete old questions and their orphaned images
     */
    protected function deleteOldQuestionsWithImages(Quiz $quiz, array $keepImages): void
    {
        foreach ($quiz->questions as $question) {
            if ($question->question_image_url && !in_array($question->question_image_url, $keepImages)) {
                $this->fileUploadService->deleteImage($question->question_image_url, 'quizzes');
            }
            foreach (['a', 'b', 'c', 'd'] as $option) {
                $imageField = "option_{$option}_image_url";
                if ($question->$imageField && !in_array($question->$imageField, $keepImages)) {
                    $this->fileUploadService->deleteImage($question->$imageField, 'quizzes');
                }
            }
        }

        $quiz->questions()->delete();
    }

    /**
     * Delete all images associated with a question
     */
    protected function deleteQuestionImages(QuizQuestion $question): void
    {
        if ($question->question_image_url) {
            $this->fileUploadService->deleteImage($question->question_image_url, 'quizzes');
        }

        foreach (['a', 'b', 'c', 'd'] as $option) {
            $imageField = "option_{$option}_image_url";
            if ($question->$imageField) {
                $this->fileUploadService->deleteImage($question->$imageField, 'quizzes');
            }
        }
    }
}
