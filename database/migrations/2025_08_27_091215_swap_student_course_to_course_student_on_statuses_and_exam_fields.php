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
        // --- student_statuses ---
        Schema::table('student_statuses', function (Blueprint $table) {
            // Thêm cột mới
            if (!Schema::hasColumn('student_statuses', 'id_course_student')) {
                $table->unsignedBigInteger('id_course_student')->after('id');
                $table->foreign('id_course_student', 'fk_statuses_id_course_student')
                    ->references('id')->on('course_students')
                    ->onUpdate('cascade')->onDelete('cascade');
            }

        });

        // --- student_exam_fields ---
        Schema::table('student_exam_fields', function (Blueprint $table) {

            if (!Schema::hasColumn('student_exam_fields', 'id_course_student')) {
                $table->unsignedBigInteger('id_course_student')->after('id');
                $table->foreign('id_course_student', 'fk_exam_fields_id_course_student')
                    ->references('id')->on('course_students')
                    ->onUpdate('cascade')->onDelete('cascade');
            }

        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Phục hồi cột cũ (không backfill dữ liệu)
        Schema::table('student_statuses', function (Blueprint $table) {
            // Bỏ FK/index mới
            if (Schema::hasColumn('student_statuses', 'id_course_student')) {
                $table->dropForeign('fk_statuses_id_course_student');
            }

            $table->dropColumn('id_course_student');
        });

        Schema::table('student_exam_fields', function (Blueprint $table) {
            if (Schema::hasColumn('student_exam_fields', 'id_course_student')) {
                $table->dropForeign('fk_exam_fields_id_course_student');
            }
            $table->dropColumn('id_course_student');
        });
    }
};
