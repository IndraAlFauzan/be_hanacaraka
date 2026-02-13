<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Quiz extends Model
{
    use HasFactory;

    protected $fillable = [
        'stage_id',
        'title',
        'passing_score',
    ];

    protected $casts = [
        'stage_id' => 'integer',
        'passing_score' => 'integer',
    ];

    /**
     * Get the stage that owns the quiz.
     */
    public function stage(): BelongsTo
    {
        return $this->belongsTo(Stage::class);
    }

    /**
     * Get the questions for the quiz.
     */
    public function questions(): HasMany
    {
        return $this->hasMany(QuizQuestion::class)->orderBy('order_index');
    }

    /**
     * Get the results for the quiz.
     */
    public function results(): HasMany
    {
        return $this->hasMany(QuizResult::class);
    }
}
