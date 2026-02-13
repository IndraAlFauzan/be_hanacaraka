<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreQuizRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
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
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'stage_id.required' => 'Stage harus dipilih.',
            'stage_id.exists' => 'Stage tidak valid.',
            'passing_score.required' => 'Passing score harus diisi.',
            'passing_score.min' => 'Passing score minimal 0.',
            'passing_score.max' => 'Passing score maksimal 100.',
            'questions.required' => 'Minimal harus ada 1 pertanyaan.',
            'questions.min' => 'Minimal harus ada 1 pertanyaan.',
            'questions.*.question_text.required' => 'Teks pertanyaan harus diisi.',
            'questions.*.option_a.required' => 'Pilihan A harus diisi.',
            'questions.*.option_b.required' => 'Pilihan B harus diisi.',
            'questions.*.option_c.required' => 'Pilihan C harus diisi.',
            'questions.*.option_d.required' => 'Pilihan D harus diisi.',
            'questions.*.correct_answer.required' => 'Jawaban benar harus dipilih.',
            'questions.*.correct_answer.in' => 'Jawaban benar harus A, B, C, atau D.',
            'questions.*.question_image.image' => 'File harus berupa gambar.',
            'questions.*.question_image.mimes' => 'Format gambar harus JPEG, JPG, atau PNG.',
            'questions.*.question_image.max' => 'Ukuran gambar maksimal 2MB.',
        ];
    }
}
