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
        Schema::table('calendars', function (Blueprint $table) {
            $table->foreignId('learning_field_id')->nullable()->after('description')->constrained('learning_fields')->comment('Lĩnh vực học');
            $table->foreignId('exam_field_id')->nullable()->after('learning_field_id')->constrained('exam_fields')->comment('Lĩnh vực thi');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('calendars', function (Blueprint $table) {
            $table->dropForeign(['learning_field_id']);
            $table->dropColumn('learning_field_id');

            $table->dropForeign(['exam_field_id']);
            $table->dropColumn('exam_field_id');
        });
    }
};
