<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateQuizRequest extends FormRequest
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
            'questions.*.existing_question_image' => 'nullable|string',
            'questions.*.option_a' => 'required|string',
            'questions.*.option_a_image' => 'nullable|image|mimes:jpeg,jpg,png|max:2048',
            'questions.*.existing_option_a_image' => 'nullable|string',
            'questions.*.option_b' => 'required|string',
            'questions.*.option_b_image' => 'nullable|image|mimes:jpeg,jpg,png|max:2048',
            'questions.*.existing_option_b_image' => 'nullable|string',
            'questions.*.option_c' => 'required|string',
            'questions.*.option_c_image' => 'nullable|image|mimes:jpeg,jpg,png|max:2048',
            'questions.*.existing_option_c_image' => 'nullable|string',
            'questions.*.option_d' => 'required|string',
            'questions.*.option_d_image' => 'nullable|image|mimes:jpeg,jpg,png|max:2048',
            'questions.*.existing_option_d_image' => 'nullable|string',
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
            'questions.required' => 'Minimal harus ada 1 pertanyaan.',
            'questions.*.question_text.required' => 'Teks pertanyaan harus diisi.',
            'questions.*.option_a.required' => 'Pilihan A harus diisi.',
            'questions.*.option_b.required' => 'Pilihan B harus diisi.',
            'questions.*.option_c.required' => 'Pilihan C harus diisi.',
            'questions.*.option_d.required' => 'Pilihan D harus diisi.',
            'questions.*.correct_answer.required' => 'Jawaban benar harus dipilih.',
        ];
    }
}
