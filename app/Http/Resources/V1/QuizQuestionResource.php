<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class QuizQuestionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'question_text' => $this->question_text,
            'question_image_url' => $this->question_image_url,
            'option_a' => $this->option_a,
            'option_a_image_url' => $this->option_a_image_url,
            'option_b' => $this->option_b,
            'option_b_image_url' => $this->option_b_image_url,
            'option_c' => $this->option_c,
            'option_c_image_url' => $this->option_c_image_url,
            'option_d' => $this->option_d,
            'option_d_image_url' => $this->option_d_image_url,
            'order_index' => $this->order_index,
        ];
    }

    /**
     * Transform the resource for quiz taking (hide correct answer)
     *
     * @return array<string, mixed>
     */
    public function toArrayForQuiz(): array
    {
        return [
            'id' => $this->id,
            'question_text' => $this->question_text,
            'question_image_url' => $this->question_image_url,
            'option_a' => $this->option_a,
            'option_a_image_url' => $this->option_a_image_url,
            'option_b' => $this->option_b,
            'option_b_image_url' => $this->option_b_image_url,
            'option_c' => $this->option_c,
            'option_c_image_url' => $this->option_c_image_url,
            'option_d' => $this->option_d,
            'option_d_image_url' => $this->option_d_image_url,
        ];
    }
}
