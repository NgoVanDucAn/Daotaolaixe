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
        Schema::table('course_students', function (Blueprint $table) {
            $table->dropColumn(['theory_exam', 'practice_exam', 'graduation_exam', 'exam_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('course_students', function (Blueprint $table) {
            $table->boolean('theory_exam')->nullable()->comment('Trang thái thi lý thuyết');
            $table->boolean('practice_exam')->nullable()->comment('Trang thái thi thực hành');
            $table->boolean('graduation_exam')->nullable()->comment('Trang thái thi tốt nghiệp');
            $table->timestamp('exam_date')->nullable()->comment('Ngày thi');
        });
    }
};
