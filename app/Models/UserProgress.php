<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserProgress extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'stage_id',
        'status',
        'last_accessed_at',
        'completed_at',
    ];

    protected $casts = [
        'user_id' => 'integer',
        'stage_id' => 'integer',
        'last_accessed_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    /**
     * Get the user that owns the progress.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the stage that owns the progress.
     */
    public function stage(): BelongsTo
    {
        return $this->belongsTo(Stage::class);
    }
}
