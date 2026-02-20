<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DrawingResultResource extends JsonResource
{
    /**
     * Additional data to be merged with the resource.
     */
    protected array $additionalData = [];

    /**
     * Set additional data to be merged with the resource.
     *
     * @param  array  $data
     * @return $this
     */
    public function setAdditionalData(array $data): self
    {
        $this->additionalData = $data;
        return $this;
    }

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'result_id' => $this->id,
            'similarity_score' => $this->similarity_score,
            'user_drawing_url' => $this->user_drawing_url,
            'is_passed' => $this->is_passed,
            'xp_earned' => $this->additionalData['xp_earned'] ?? 0,
            'level_up' => $this->additionalData['level_up'] ?? false,
            'stage_completed' => $this->additionalData['stage_completed'] ?? false,
            'next_stage_unlocked' => $this->additionalData['next_stage_unlocked'] ?? null,
        ];
    }
}
