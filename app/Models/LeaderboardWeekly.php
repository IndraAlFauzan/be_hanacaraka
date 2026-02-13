<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LeaderboardWeekly extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'week_start_date',
        'total_xp',
        'rank',
    ];

    protected $casts = [
        'user_id' => 'integer',
        'week_start_date' => 'date',
        'total_xp' => 'integer',
        'rank' => 'integer',
    ];

    /**
     * Get the user that owns the leaderboard entry.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
