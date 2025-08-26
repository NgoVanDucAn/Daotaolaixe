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
        Schema::table('student_exam_fields', function (Blueprint $table) {
            $table->tinyInteger('attempt_number')->default(0)->after('exam_field_id')->comment('Số lần đã thi');
            $table->tinyInteger('status')->default(0)->after('attempt_number')->comment('lưu lại trạng thái môn thi đó: 0 là chưa hoàn thành, 1 là hoàn thành');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('student_exam_fields', function (Blueprint $table) {
            $table->dropColumn(['attempt_number', 'status']);
        });
    }
};
