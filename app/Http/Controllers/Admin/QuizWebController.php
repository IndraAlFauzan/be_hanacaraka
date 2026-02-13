<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Quiz;
use App\Models\QuizQuestion;
use App\Models\Stage;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class QuizWebController extends Controller
{
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

    public function create()
    {
        $stages = Stage::with('level')->orderBy('level_id')->orderBy('stage_number')->get();
        return view('admin.quizzes.create', compact('stages'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'stage_id' => 'required|exists:stages,id',
            'title' => 'nullable|string|max:100',
            'passing_score' => 'required|integer|min:0|max:100',
            'questions' => 'required|array|min:1',
            'questions.*.question_text' => 'required|string',
            'questions.*.question_image' => 'nullable|image|mimes:jpeg,jpg,png|max:2048',
            'questions.*.option_a' => 'required|string',
            'questions.*.option_a_image' => 'nullable|image|mimes:jpeg,jpg,png|max:2048',
            'questions.*.option_b' => 'required|string',
            'questions.*.option_b_image' => 'nullable|image|mimes:jpeg,jpg,png|max:2048',
            'questions.*.option_c' => 'required|string',
            'questions.*.option_c_image' => 'nullable|image|mimes:jpeg,jpg,png|max:2048',
            'questions.*.option_d' => 'required|string',
            'questions.*.option_d_image' => 'nullable|image|mimes:jpeg,jpg,png|max:2048',
            'questions.*.correct_answer' => 'required|in:a,b,c,d',
        ]);

        $quiz = Quiz::create([
            'stage_id' => $validated['stage_id'],
            'title' => $validated['title'],
            'passing_score' => $validated['passing_score'],
        ]);

        foreach ($request->input('questions', []) as $index => $questionData) {
            $data = [
                'quiz_id' => $quiz->id,
                'question_text' => $questionData['question_text'],
                'option_a' => $questionData['option_a'],
                'option_b' => $questionData['option_b'],
                'option_c' => $questionData['option_c'],
                'option_d' => $questionData['option_d'],
                'correct_answer' => $questionData['correct_answer'],
            ];

            // Handle question image
            if ($request->hasFile("questions.{$index}.question_image")) {
                $data['question_image_url'] = $this->uploadImage($request->file("questions.{$index}.question_image"), 'question');
            }

            // Handle option images
            foreach (['a', 'b', 'c', 'd'] as $option) {
                $fieldName = "questions.{$index}.option_{$option}_image";
                if ($request->hasFile($fieldName)) {
                    $data["option_{$option}_image_url"] = $this->uploadImage($request->file($fieldName), "option_{$option}");
                }
            }

            QuizQuestion::create($data);
        }

        return redirect()->route('admin.quizzes.index')
            ->with('success', 'Kuis berhasil ditambahkan dengan ' . count($request->input('questions', [])) . ' pertanyaan!');
    }

    public function show(string $id)
    {
        $quiz = Quiz::with(['stage.level', 'questions'])->findOrFail($id);
        return view('admin.quizzes.show', compact('quiz'));
    }

    public function edit(string $id)
    {
        $quiz = Quiz::with(['stage.level', 'questions'])->findOrFail($id);
        $stages = Stage::with('level')->orderBy('level_id')->orderBy('stage_number')->get();
        return view('admin.quizzes.edit', compact('quiz', 'stages'));
    }

    public function update(Request $request, string $id)
    {
        $quiz = Quiz::findOrFail($id);

        $validated = $request->validate([
            'stage_id' => 'required|exists:stages,id',
            'title' => 'nullable|string|max:100',
            'passing_score' => 'required|integer|min:0|max:100',
            'questions' => 'required|array|min:1',
            'questions.*.question_text' => 'required|string',
            'questions.*.question_image' => 'nullable|image|mimes:jpeg,jpg,png|max:2048',
            'questions.*.option_a' => 'required|string',
            'questions.*.option_a_image' => 'nullable|image|mimes:jpeg,jpg,png|max:2048',
            'questions.*.option_b' => 'required|string',
            'questions.*.option_b_image' => 'nullable|image|mimes:jpeg,jpg,png|max:2048',
            'questions.*.option_c' => 'required|string',
            'questions.*.option_c_image' => 'nullable|image|mimes:jpeg,jpg,png|max:2048',
            'questions.*.option_d' => 'required|string',
            'questions.*.option_d_image' => 'nullable|image|mimes:jpeg,jpg,png|max:2048',
            'questions.*.correct_answer' => 'required|in:a,b,c,d',
        ]);

        $quiz->update([
            'stage_id' => $validated['stage_id'],
            'title' => $validated['title'],
            'passing_score' => $validated['passing_score'],
        ]);

        // Collect existing image URLs that should be kept
        $keepImages = [];
        foreach ($request->input('questions', []) as $index => $questionData) {
            if (isset($questionData['existing_question_image'])) {
                $keepImages[] = $questionData['existing_question_image'];
            }
            foreach (['a', 'b', 'c', 'd'] as $option) {
                if (isset($questionData["existing_option_{$option}_image"])) {
                    $keepImages[] = $questionData["existing_option_{$option}_image"];
                }
            }
        }

        // Delete old questions and only delete images that are not being kept
        foreach ($quiz->questions as $question) {
            if ($question->question_image_url && !in_array($question->question_image_url, $keepImages)) {
                $this->deleteImage($question->question_image_url);
            }
            foreach (['a', 'b', 'c', 'd'] as $option) {
                $imageField = "option_{$option}_image_url";
                if ($question->$imageField && !in_array($question->$imageField, $keepImages)) {
                    $this->deleteImage($question->$imageField);
                }
            }
        }
        $quiz->questions()->delete();

        // Add new questions with images
        foreach ($request->input('questions', []) as $index => $questionData) {
            $data = [
                'quiz_id' => $quiz->id,
                'question_text' => $questionData['question_text'],
                'option_a' => $questionData['option_a'],
                'option_b' => $questionData['option_b'],
                'option_c' => $questionData['option_c'],
                'option_d' => $questionData['option_d'],
                'correct_answer' => $questionData['correct_answer'],
            ];

            // Handle question image
            if ($request->hasFile("questions.{$index}.question_image")) {
                // Delete old image if exists and upload new one
                if (isset($questionData['existing_question_image'])) {
                    $this->deleteImage($questionData['existing_question_image']);
                }
                $data['question_image_url'] = $this->uploadImage($request->file("questions.{$index}.question_image"), 'question');
            } elseif (isset($questionData['existing_question_image'])) {
                // Keep existing image if no new upload
                $data['question_image_url'] = $questionData['existing_question_image'];
            }

            // Handle option images
            foreach (['a', 'b', 'c', 'd'] as $option) {
                $fieldName = "questions.{$index}.option_{$option}_image";
                if ($request->hasFile($fieldName)) {
                    // Delete old image if exists and upload new one
                    if (isset($questionData["existing_option_{$option}_image"])) {
                        $this->deleteImage($questionData["existing_option_{$option}_image"]);
                    }
                    $data["option_{$option}_image_url"] = $this->uploadImage($request->file($fieldName), "option_{$option}");
                } elseif (isset($questionData["existing_option_{$option}_image"])) {
                    // Keep existing image if no new upload
                    $data["option_{$option}_image_url"] = $questionData["existing_option_{$option}_image"];
                }
            }

            QuizQuestion::create($data);
        }

        return redirect()->route('admin.quizzes.index')
            ->with('success', 'Kuis berhasil diperbarui!');
    }

    public function destroy(string $id)
    {
        $quiz = Quiz::findOrFail($id);

        // Delete all associated images
        foreach ($quiz->questions as $question) {
            if ($question->question_image_url) {
                $this->deleteImage($question->question_image_url);
            }
            foreach (['a', 'b', 'c', 'd'] as $option) {
                $imageField = "option_{$option}_image_url";
                if ($question->$imageField) {
                    $this->deleteImage($question->$imageField);
                }
            }
        }

        $quiz->delete();

        return redirect()->route('admin.quizzes.index')
            ->with('success', 'Kuis berhasil dihapus!');
    }

    /**
     * Upload image for quiz question or option
     */
    private function uploadImage($file, $type = 'quiz')
    {
        try {
            $timestamp = time();
            $uuid = Str::uuid();
            $extension = $file->getClientOriginalExtension();
            $filename = "quiz_{$type}_{$timestamp}_{$uuid}.{$extension}";

            // Ensure directory exists
            $directory = storage_path('app/public/quizzes');
            if (!file_exists($directory)) {
                mkdir($directory, 0755, true);
            }

            // Full destination path
            $destinationPath = $directory . '/' . $filename;

            // Use native PHP move_uploaded_file
            $tmpPath = $file->getPathname();
            $moved = move_uploaded_file($tmpPath, $destinationPath);

            if (!$moved) {
                $moved = copy($tmpPath, $destinationPath);
            }

            if (!$moved) {
                throw new \Exception('Failed to move uploaded file');
            }

            // Verify file exists and has content
            if (!file_exists($destinationPath) || filesize($destinationPath) === 0) {
                throw new \Exception('File is empty or does not exist after upload');
            }

            return asset('storage/quizzes/' . $filename);
        } catch (\Exception $e) {
            \Log::error('Quiz image upload failed: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Delete image from storage
     */
    private function deleteImage($imageUrl)
    {
        try {
            $path = parse_url($imageUrl, PHP_URL_PATH);
            $filename = basename($path);
            $filePath = storage_path('app/public/quizzes/' . $filename);

            if (file_exists($filePath)) {
                unlink($filePath);
            }
        } catch (\Exception $e) {
            \Log::error('Failed to delete quiz image: ' . $e->getMessage());
        }
    }
}
