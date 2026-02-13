<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StageResource extends JsonResource
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
            'level_id' => $this->level_id,
            'title' => $this->title,
            'description' => $this->description,
            'stage_number' => $this->stage_number,
            'level' => new LevelResource($this->whenLoaded('level')),
            'materials' => MaterialResource::collection($this->whenLoaded('materials')),
            'quizzes' => QuizResource::collection($this->whenLoaded('quizzes')),
            'evaluations' => EvaluationResource::collection($this->whenLoaded('evaluations')),
            'created_at' => $this->created_at?->toISOString(),
            'updated_at' => $this->updated_at?->toISOString(),
        ];
    }
}
