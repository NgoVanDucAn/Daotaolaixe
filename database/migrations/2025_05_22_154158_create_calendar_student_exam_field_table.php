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
        Schema::create('calendar_student_exam_field', function (Blueprint $table) {
            $table->id();
            $table->foreignId('calendar_student_id')->constrained('calendar_student')->onDelete('cascade');
            $table->foreignId('exam_field_id')->constrained()->onDelete('cascade');
            $table->unsignedTinyInteger('attempt_number')->default(1);
            $table->string('answer_ratio')->nullable();
            $table->tinyInteger('exam_all_status')->unsigned()->nullable()->comment('trạng thái thi chung của lịch 0 = chưa thi, 1 = đỗ, 2 = không đỗ');
            $table->tinyInteger('exam_status')->unsigned()->nullable()->comment('trạng thái thi của môn 0 = chưa thi, 1 = đạt, 2 = không đạt');
            $table->string('remarks')->nullable();
            $table->timestamps();

            $table->unique(['calendar_student_id', 'exam_field_id'], 'calendar_student_exam_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('calendar_student_exam_field');
    }
};
