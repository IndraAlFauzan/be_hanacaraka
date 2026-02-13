<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreLevelRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'level_number' => 'required|integer|unique:levels',
            'title' => 'required|string|max:100',
            'description' => 'nullable|string',
            'xp_required' => 'required|integer|min:0',
            'is_active' => 'boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'level_number.required' => 'Nomor level wajib diisi',
            'level_number.unique' => 'Nomor level sudah digunakan',
            'title.required' => 'Judul wajib diisi',
            'xp_required.required' => 'XP yang dibutuhkan wajib diisi',
            'xp_required.min' => 'XP tidak boleh negatif',
        ];
    }
}
