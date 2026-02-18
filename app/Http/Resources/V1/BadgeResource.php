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
            'icon_url' => $this->icon_path ? asset('storage/' . $this->icon_path) : null,
            'requirement_type' => $this->criteria_type, // API alias for criteria_type
            'requirement_value' => $this->criteria_value, // API alias for 

        ];
    }
}
