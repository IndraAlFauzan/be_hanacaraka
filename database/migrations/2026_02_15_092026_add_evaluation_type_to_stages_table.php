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
        Schema::table('stages', function (Blueprint $table) {
            // Evaluation type: 'drawing' (default), 'quiz', or 'both'
            $table->enum('evaluation_type', ['drawing', 'quiz', 'both'])
                ->default('drawing')
                ->after('xp_reward');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('stages', function (Blueprint $table) {
            $table->dropColumn('evaluation_type');
        });
    }
};
