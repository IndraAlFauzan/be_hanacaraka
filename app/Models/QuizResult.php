<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class QuizResult extends Model
{
    use HasFactory;

    const UPDATED_AT = null; // Only created_at timestamp

    protected $fillable = [
        'user_id',
        'quiz_id',
        'score',
        'is_passed',
        'answers',
        'attempt_number',
    ];

    protected $casts = [
        'user_id' => 'integer',
        'quiz_id' => 'integer',
        'score' => 'integer',
        'is_passed' => 'boolean',
        'answers' => 'array',
        'attempt_number' => 'integer',
        'created_at' => 'datetime',
    ];

    /**
     * Get the user that owns the result.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the quiz that owns the result.
     */
    public function quiz(): BelongsTo
    {
        return $this->belongsTo(Quiz::class);
    }
}
