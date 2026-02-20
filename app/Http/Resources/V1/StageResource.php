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
            'stage_number' => $this->stage_number,
            'title' => $this->title,
            'xp_reward' => $this->xp_reward,
            'evaluation_type' => $this->evaluation_type,
            'is_active' => $this->is_active,
            'level' => new LevelResource($this->whenLoaded('level')),
            'materials' => MaterialResource::collection($this->whenLoaded('materials')),
            'quizzes' => QuizResource::collection($this->whenLoaded('quizzes')),
            'drawing_challenges' => DrawingChallengeResource::collection($this->whenLoaded('evaluations')),
            'created_at' => $this->created_at?->toISOString(),
            'updated_at' => $this->updated_at?->toISOString(),
        ];
    }
}
