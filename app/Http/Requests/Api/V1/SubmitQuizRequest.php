<?php

namespace App\Http\Requests\Api\V1;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class SubmitQuizRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'answers' => 'required|array|min:1',
            'answers.*.question_id' => 'required|exists:quiz_questions,id',
            'answers.*.selected_answer' => 'required|in:a,b,c,d',
        ];
    }

    public function messages(): array
    {
        return [
            'answers.required' => 'Jawaban wajib diisi',
            'answers.array' => 'Format jawaban tidak valid',
            'answers.min' => 'Minimal harus ada 1 jawaban',
            'answers.*.question_id.required' => 'ID pertanyaan wajib diisi',
            'answers.*.question_id.exists' => 'Pertanyaan tidak ditemukan',
            'answers.*.selected_answer.required' => 'Pilihan jawaban wajib diisi',
            'answers.*.selected_answer.in' => 'Pilihan jawaban harus a, b, c, atau d',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'success' => false,
            'message' => 'Validation error',
            'errors' => $validator->errors(),
        ], 422));
    }
}
