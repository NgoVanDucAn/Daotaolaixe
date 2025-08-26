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
        Schema::table('fees', function (Blueprint $table) {
            $table->dropForeign(['course_student_id']);
            $table->unsignedBigInteger('course_student_id')->nullable()->change();
            $table->foreign('course_student_id')
                ->references('id')
                ->on('course_students')
                ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('fees', function (Blueprint $table) {
            $table->dropForeign(['course_student_id']);
            $table->unsignedBigInteger('course_student_id')->nullable(false)->change();
            $table->foreign('course_student_id')
                ->references('id')
                ->on('course_students')
                ->onDelete('cascade');
        });
    }
};
