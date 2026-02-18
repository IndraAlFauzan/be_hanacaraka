<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'total_xp',
        'current_level',
        'streak_count',
        'last_activity_date',
        'daily_goal_xp',
        'avatar_url',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'last_activity_date' => 'date',
            'total_xp' => 'integer',
            'current_level' => 'integer',
            'streak_count' => 'integer',
            'daily_goal_xp' => 'integer',
        ];
    }

    /**
     * Get the user's progress records.
     */
    public function progress(): HasMany
    {
        return $this->hasMany(UserProgress::class);
    }

    /**
     * Get the user's challenge results.
     */
    public function challengeResults(): HasMany
    {
        return $this->hasMany(ChallengeResult::class);
    }

    /**
     * Get the user's quiz results.
     */
    public function quizResults(): HasMany
    {
        return $this->hasMany(QuizResult::class);
    }

    /**
     * Get the user's badges through the pivot table.
     */
    public function badges(): BelongsToMany
    {
        return $this->belongsToMany(Badge::class, 'user_badges')
            ->withPivot('earned_at')
            ->using(UserBadge::class);
    }

    /**
     * Get the user's weekly leaderboard entries.
     */
    public function weeklyLeaderboard(): HasMany
    {
        return $this->hasMany(LeaderboardWeekly::class);
    }

    /**
     * Check if user is admin.
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Check if user is player (pemain).
     */
    public function isPemain(): bool
    {
        return $this->role === 'pemain';
    }
}
