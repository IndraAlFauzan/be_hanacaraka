<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ChallengeResult extends Model
{
    use HasFactory;

    const UPDATED_AT = null; // Only created_at timestamp

    protected $fillable = [
        'user_id',
        'evaluation_id',
        'user_drawing_url',
        'similarity_score',
        'is_passed',
        'attempt_number',
    ];

    protected $casts = [
        'user_id' => 'integer',
        'evaluation_id' => 'integer',
        'similarity_score' => 'decimal:2',
        'is_passed' => 'boolean',
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
     * Get the evaluation that owns the result.
     */
    public function evaluation(): BelongsTo
    {
        return $this->belongsTo(Evaluation::class);
    }
}
