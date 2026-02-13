<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Evaluation extends Model
{
    use HasFactory;

    protected $fillable = [
        'stage_id',
        'character_target',
        'reference_image_url',
        'min_similarity_score',
    ];

    protected $casts = [
        'stage_id' => 'integer',
        'min_similarity_score' => 'decimal:2',
    ];

    /**
     * Get the stage that owns the evaluation.
     */
    public function stage(): BelongsTo
    {
        return $this->belongsTo(Stage::class);
    }

    /**
     * Get the challenge results for this evaluation.
     */
    public function results(): HasMany
    {
        return $this->hasMany(ChallengeResult::class);
    }
}
