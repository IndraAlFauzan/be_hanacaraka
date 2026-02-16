<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateBadgeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:50',
            'description' => 'nullable|string',
            'icon_url' => 'required|string|max:100',
            'criteria_type' => 'required|in:xp_milestone,streak,level_complete,custom',
            'criteria_value' => 'nullable|integer|min:0',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Nama badge wajib diisi',
            'icon_url.required' => 'URL ikon wajib diisi',
            'criteria_type.required' => 'Tipe kriteria wajib dipilih',
            'criteria_type.in' => 'Tipe kriteria harus salah satu dari: xp_milestone, streak, level_complete, custom',
        ];
    }
}
