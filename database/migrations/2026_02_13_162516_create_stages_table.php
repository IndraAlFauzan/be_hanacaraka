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
        Schema::create('stages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('level_id')->constrained('levels')->onDelete('cascade');
            $table->integer('stage_number');
            $table->string('title', 100);
            $table->integer('xp_reward')->default(10);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->unique(['level_id', 'stage_number'], 'unique_level_stage');
            $table->index(['level_id', 'stage_number'], 'idx_level_stage');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stages');
    }
};
