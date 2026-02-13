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
        Schema::create('challenge_results', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('evaluation_id')->constrained('evaluations')->onDelete('cascade');
            $table->string('user_drawing_url');
            $table->decimal('similarity_score', 5, 2);
            $table->boolean('is_passed');
            $table->integer('attempt_number')->default(1);
            $table->timestamp('created_at')->useCurrent();
            
            $table->index(['user_id', 'evaluation_id'], 'idx_user_evaluation');
            $table->index('created_at', 'idx_created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('challenge_results');
    }
};
