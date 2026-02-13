<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Stage extends Model
{
    use HasFactory;

    protected $fillable = [
        'level_id',
        'stage_number',
        'title',
        'xp_reward',
        'is_active',
    ];

    protected $casts = [
        'level_id' => 'integer',
        'stage_number' => 'integer',
        'xp_reward' => 'integer',
        'is_active' => 'boolean',
    ];

    /**
     * Get the level that owns the stage.
     */
    public function level(): BelongsTo
    {
        return $this->belongsTo(Level::class);
    }

    /**
     * Get the materials for the stage.
     */
    public function materials(): HasMany
    {
        return $this->hasMany(Material::class)->orderBy('order_index');
    }

    /**
     * Get the quizzes for the stage.
     */
    public function quizzes(): HasMany
    {
        return $this->hasMany(Quiz::class);
    }

    /**
     * Get the evaluations for the stage.
     */
    public function evaluations(): HasMany
    {
        return $this->hasMany(Evaluation::class);
    }

    /**
     * Get the progress records for the stage.
     */
    public function progress(): HasMany
    {
        return $this->hasMany(UserProgress::class);
    }
}
