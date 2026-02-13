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
        Schema::table('quiz_questions', function (Blueprint $table) {
            $table->string('option_a_image_url')->nullable()->after('option_a');
            $table->string('option_b_image_url')->nullable()->after('option_b');
            $table->string('option_c_image_url')->nullable()->after('option_c');
            $table->string('option_d_image_url')->nullable()->after('option_d');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('quiz_questions', function (Blueprint $table) {
            $table->dropColumn(['option_a_image_url', 'option_b_image_url', 'option_c_image_url', 'option_d_image_url']);
        });
    }
};
