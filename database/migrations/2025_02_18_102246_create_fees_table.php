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
        Schema::create('fees', function (Blueprint $table) {
            $table->id();
            $table->timestamp('payment_date')->comment('Ngày thanh toán');
            $table->unsignedBigInteger('amount')->comment('Số tiền đã đóng');
            $table->unsignedBigInteger('student_id')->comment('Tài khoản học viên');
            $table->boolean('is_received')->default(false)->comment('Trạng thái thanh toán');
            $table->text('note')->nullable();
            $table->unsignedBigInteger('course_student_id')->comment('Khóa học người dùng tham gia');
            $table->timestamps();

            $table->foreign('student_id')->references('id')->on('students')->onDelete('cascade');
            $table->foreign('course_student_id')->references('id')->on('course_students')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fees');
    }
};
