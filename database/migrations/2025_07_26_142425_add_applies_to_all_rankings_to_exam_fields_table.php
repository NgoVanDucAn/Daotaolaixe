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
        Schema::table('exam_fields', function (Blueprint $table) {
            $table->boolean('applies_to_all_rankings')->default(false)->after('is_practical');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('exam_fields', function (Blueprint $table) {
            $table->dropColumn('applies_to_all_rankings');
        });
    }
};
