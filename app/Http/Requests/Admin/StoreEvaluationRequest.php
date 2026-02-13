<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreEvaluationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'stage_id' => 'required|exists:stages,id',
            'character_target' => 'required|string|max:10',
            'reference_image_url' => 'required|url|max:255',
            'min_similarity_score' => 'required|numeric|min:0|max:100',
        ];
    }

    public function messages(): array
    {
        return [
            'stage_id.required' => 'Stage wajib dipilih',
            'stage_id.exists' => 'Stage tidak ditemukan',
            'character_target.required' => 'Karakter target wajib diisi',
            'reference_image_url.required' => 'URL gambar referensi wajib diisi',
            'reference_image_url.url' => 'Format URL tidak valid',
            'min_similarity_score.required' => 'Skor minimum wajib diisi',
            'min_similarity_score.min' => 'Skor minimum tidak boleh kurang dari 0',
            'min_similarity_score.max' => 'Skor minimum tidak boleh lebih dari 100',
        ];
    }
}
