<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BadgeResource extends JsonResource
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
            'name' => $this->name,
            'description' => $this->description,
            'icon_url' => $this->icon_url,
            'requirement_type' => $this->requirement_type,
            'requirement_value' => $this->requirement_value,
            'earned_at' => $this->whenPivotLoaded('user_badges', function () {
                return $this->pivot->earned_at;
            }),
            'created_at' => $this->created_at?->toISOString(),
        ];
    }
}
