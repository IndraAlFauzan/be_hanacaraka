<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserBadge extends Pivot
{
    use HasFactory;

    /**
     * The table associated with the pivot model.
     */
    protected $table = 'user_badges';

    /**
     * Indicates if the model should be timestamped.
     * Table only has earned_at, no created_at/updated_at
     */
    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'badge_id',
        'earned_at',
    ];

    protected $casts = [
        'user_id' => 'integer',
        'badge_id' => 'integer',
        'earned_at' => 'datetime',
    ];

    /**
     * Get the user that owns the badge.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the badge.
     */
    public function badge(): BelongsTo
    {
        return $this->belongsTo(Badge::class);
    }
}
