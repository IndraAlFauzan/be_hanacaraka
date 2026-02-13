<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class QuizQuestion extends Model
{
    use HasFactory;

    protected $fillable = [
        'quiz_id',
        'question_text',
        'question_image_url',
        'option_a',
        'option_a_image_url',
        'option_b',
        'option_b_image_url',
        'option_c',
        'option_c_image_url',
        'option_d',
        'option_d_image_url',
        'correct_answer',
        'order_index',
    ];

    protected $casts = [
        'quiz_id' => 'integer',
        'order_index' => 'integer',
    ];

    /**
     * Get the quiz that owns the question.
     */
    public function quiz(): BelongsTo
    {
        return $this->belongsTo(Quiz::class);
    }
}
