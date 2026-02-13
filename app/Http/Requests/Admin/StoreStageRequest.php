<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreStageRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'level_id' => 'required|exists:levels,id',
            'stage_number' => 'required|integer|min:1',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'xp_reward' => 'required|integer|min:0',
            'is_active' => 'boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'level_id.required' => 'Level wajib dipilih',
            'level_id.exists' => 'Level tidak ditemukan',
            'stage_number.required' => 'Nomor stage wajib diisi',
            'title.required' => 'Judul wajib diisi',
            'xp_reward.required' => 'XP reward wajib diisi',
        ];
    }
}
