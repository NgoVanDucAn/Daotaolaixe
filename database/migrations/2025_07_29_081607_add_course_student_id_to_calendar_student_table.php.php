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
        Schema::table('calendar_student', function (Blueprint $table) {
            $table->unsignedBigInteger('course_student_id')->after('calendar_id');
            $table->foreign('course_student_id')
                  ->references('id')
                  ->on('course_students')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('calendar_student', function (Blueprint $table) {
            $table->dropForeign(['course_student_id']);
            $table->dropColumn('course_student_id');
        });
    }
};
