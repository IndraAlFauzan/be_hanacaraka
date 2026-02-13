<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Material extends Model
{
    use HasFactory;

    protected $fillable = [
        'stage_id',
        'title',
        'content_text',
        'content_markdown',
        'image_url',
        'order_index',
    ];

    protected $casts = [
        'stage_id' => 'integer',
        'order_index' => 'integer',
    ];

    /**
     * Get the stage that owns the material.
     */
    public function stage(): BelongsTo
    {
        return $this->belongsTo(Stage::class);
    }
}
