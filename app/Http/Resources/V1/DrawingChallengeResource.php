<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DrawingChallengeResource extends JsonResource
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
            'stage_id' => $this->stage_id,
            'character_target' => $this->character_target,
            'title' => $this->title,
            'description' => $this->description,
            'reference_image_url' => $this->reference_image_url,
            'min_similarity_score' => (int) $this->min_similarity_score,
            'user_attempts' => isset($this->user_attempts) ? (int) $this->user_attempts : 0,
            'user_best_score' => isset($this->user_best_score) && $this->user_best_score !== null ? (float) $this->user_best_score : 0.0,
            'stage' => new StageResource($this->whenLoaded('stage')),

            // 'created_at' => $this->created_at?->toISOString(),
            // 'updated_at' => $this->updated_at?->toISOString(),
        ];
    }
}
