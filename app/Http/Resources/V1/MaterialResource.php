<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MaterialResource extends JsonResource
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
            'title' => $this->title,
            'content_text' => $this->content_text,
            'content_markdown' => $this->content_markdown,
            'image_url' => $this->image_url,
            'order_index' => $this->order_index,
            'stage' => new StageResource($this->whenLoaded('stage')),
            'created_at' => $this->created_at?->toISOString(),
            'updated_at' => $this->updated_at?->toISOString(),
        ];
    }
}
