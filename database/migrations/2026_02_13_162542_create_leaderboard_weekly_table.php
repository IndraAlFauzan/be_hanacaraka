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
        Schema::create('leaderboard_weekly', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->date('week_start_date');
            $table->integer('total_xp')->default(0);
            $table->integer('rank')->nullable();
            $table->timestamps();

            $table->unique(['user_id', 'week_start_date'], 'unique_user_week');
            $table->index(['week_start_date', 'total_xp'], 'idx_week_xp');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('leaderboard_weekly');
    }
};
