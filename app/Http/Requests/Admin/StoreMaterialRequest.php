<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreMaterialRequest extends FormRequest
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
            'title' => 'required|string|max:255',
            'content_text' => 'nullable|string',
            'content_markdown' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,jpg,png|max:2048',
            'order_index' => 'required|integer|min:1',
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
            'title.required' => 'Judul materi harus diisi.',
            'title.max' => 'Judul maksimal 255 karakter.',
            'order_index.required' => 'Urutan harus diisi.',
            'order_index.min' => 'Urutan minimal 1.',
            'image.image' => 'File harus berupa gambar.',
            'image.mimes' => 'Format gambar harus JPEG, JPG, atau PNG.',
            'image.max' => 'Ukuran gambar maksimal 2MB.',
        ];
    }
}
