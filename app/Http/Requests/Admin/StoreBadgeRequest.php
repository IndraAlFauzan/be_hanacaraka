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
            'icon' => 'required|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
            'criteria_type' => 'required|in:xp_milestone,streak,level_complete,custom',
            'criteria_value' => 'nullable|integer|min:0',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Nama badge wajib diisi',
            'icon.required' => 'Gambar ikon wajib diupload',
            'icon.image' => 'File harus berupa gambar',
            'icon.mimes' => 'Format gambar harus: jpeg, png, jpg, gif, svg, atau webp',
            'icon.max' => 'Ukuran gambar maksimal 2MB',
            'criteria_type.required' => 'Tipe kriteria wajib dipilih',
            'criteria_type.in' => 'Tipe kriteria harus salah satu dari: xp_milestone, streak, level_complete, custom',
        ];
    }
}
