<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('quiz_results', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('quiz_id')->constrained('quizzes')->onDelete('cascade');
            $table->integer('score');
            $table->boolean('is_passed');
            $table->json('answers'); // Format: [{"question_id": 1, "selected_answer": "a", "is_correct": true}]
            $table->integer('attempt_number')->default(1);
            $table->timestamp('created_at')->useCurrent();

            $table->index(['user_id', 'quiz_id'], 'idx_user_quiz');
            $table->index('created_at', 'idx_created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quiz_results');
    }
};
