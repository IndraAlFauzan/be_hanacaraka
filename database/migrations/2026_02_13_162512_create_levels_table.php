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
        Schema::create('levels', function (Blueprint $table) {
            $table->id();
            $table->integer('level_number')->unique();
            $table->string('title', 100);
            $table->text('description')->nullable();
            $table->integer('xp_required'); // XP kumulatif untuk unlock level ini
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            
            $table->index('level_number', 'idx_level_number');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('levels');
    }
};
