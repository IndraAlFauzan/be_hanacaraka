<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EvaluationQuestionResource extends JsonResource
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
            'question_type' => $this->question_type,
            'question_text' => $this->question_text,
            'question_image_url' => $this->question_image_url,
            'answer_text' => $this->answer_text,
            'answer_image_url' => $this->answer_image_url,
            'order_index' => $this->order_index,
        ];
    }
}
