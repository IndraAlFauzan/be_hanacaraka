<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Level extends Model
{
    use HasFactory;

    protected $fillable = [
        'level_number',
        'title',
        'description',
        'xp_required',
        'is_active',
    ];

    protected $casts = [
        'level_number' => 'integer',
        'xp_required' => 'integer',
        'is_active' => 'boolean',
    ];

    /**
     * Get the stages for the level.
     */
    public function stages(): HasMany
    {
        return $this->hasMany(Stage::class)->orderBy('stage_number');
    }
}
