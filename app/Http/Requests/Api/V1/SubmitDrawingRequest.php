<?php

namespace App\Http\Requests\Api\V1;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class SubmitDrawingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'drawing_image' => 'required|image|mimes:png,jpg,jpeg|max:2048',
            'similarity_score' => 'required|numeric|between:0,100',
        ];
    }

    public function messages(): array
    {
        return [
            'drawing_image.required' => 'Gambar wajib diunggah',
            'drawing_image.image' => 'File harus berupa gambar',
            'drawing_image.mimes' => 'Format gambar harus PNG, JPG, atau JPEG',
            'drawing_image.max' => 'Ukuran gambar maksimal 2MB',
            'similarity_score.required' => 'Similarity score wajib diisi',
            'similarity_score.numeric' => 'Similarity score harus berupa angka',
            'similarity_score.between' => 'Similarity score harus antara 0-100',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'success' => false,
            'message' => 'Validasi gagal',
            'errors' => $validator->errors(),
        ], 422));
    }
}
