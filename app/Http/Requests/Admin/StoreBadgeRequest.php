<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreBadgeRequest extends FormRequest
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
            'criteria_type' => 'required|string|max:50',
            'criteria_value' => 'nullable|integer|min:0',
            'xp_reward' => 'required|integer|min:0',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Nama badge wajib diisi',
            'icon_url.required' => 'URL ikon wajib diisi',
            'criteria_type.required' => 'Tipe kriteria wajib dipilih',
            'xp_reward.required' => 'XP reward wajib diisi',
        ];
    }
}
