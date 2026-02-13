<?php

namespace App\Http\Requests\Api\V1;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class UpdateUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'sometimes|string|max:100',
            'daily_goal_xp' => 'sometimes|integer|min:10|max:500',
            'avatar_url' => 'sometimes|url',
        ];
    }

    public function messages(): array
    {
        return [
            'name.max' => 'Nama maksimal 100 karakter',
            'daily_goal_xp.min' => 'Target XP harian minimal 10',
            'daily_goal_xp.max' => 'Target XP harian maksimal 500',
            'avatar_url.url' => 'Format URL avatar tidak valid',
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
