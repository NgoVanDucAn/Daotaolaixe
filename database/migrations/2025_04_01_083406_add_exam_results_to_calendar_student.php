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
            $table->float('score', 8, 3)->nullable()->after('student_id')->comment('số % đúng được tính dựa trên số câu đúng');
            $table->string('correct_answers', 10)->nullable()->after('score')->comment('số câu đúng dưới dạng: số câu đúng/số câu của bài thi');
            $table->tinyInteger('exam_status')->default(0)->after('correct_answers')->comment('trạng thái thi: 0: chưa thi, 1: đạt, 2: không đạt');
            $table->tinyInteger('attempt_number')->default(0)->after('exam_status')->comment('lưu lại đây là lần thi thứ mấy');
            $table->text('remarks')->nullable()->after('attempt_number')->comment('Nhận xét ghi vào đây');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('calendar_student', function (Blueprint $table) {
            $table->dropColumn(['score', 'correct_answers', 'exam_status', 'attempt_number', 'remarks']);
        });
    }
};
