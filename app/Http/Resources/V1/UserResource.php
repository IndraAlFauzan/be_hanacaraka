<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
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
            'email' => $this->email,
            'role' => $this->role,
            'total_xp' => $this->total_xp,
            'current_level' => $this->current_level,
            'streak_count' => $this->streak_count,
            'last_activity_date' => $this->last_activity_date,
            'daily_goal_xp' => $this->daily_goal_xp,
            'avatar_url' => $this->avatar_url,
            'email_verified_at' => $this->email_verified_at,
            'badges' => BadgeResource::collection($this->whenLoaded('badges')),
            'badges_count' => $this->when(
                $this->relationLoaded('badges'),
                fn() => $this->badges->count()
            ),
            'completed_stages_count' => $this->when(
                $this->relationLoaded('progress'),
                fn() => $this->progress->where('status', 'completed')->count()
            ),
            'created_at' => $this->created_at?->toISOString(),
            'updated_at' => $this->updated_at?->toISOString(),
        ];
    }
}
