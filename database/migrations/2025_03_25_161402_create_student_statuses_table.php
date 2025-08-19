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
        Schema::create('student_statuses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('students')->onDelete('cascade');
            $table->foreignId('course_id')->constrained('courses')->onDelete('cascade');
            $table->foreignId('learning_field_id')->constrained('learning_fields')->onDelete('cascade');
            $table->float('hours', 8, 3)->default(0)->comment('tổng số giờ học');
            $table->float('km', 8, 3)->default(0)->comment('số km chạy được');
            $table->float('night_hours', 8, 3)->default(0)->comment('số giờ chạy đêm');
            $table->float('auto_hours', 8, 3)->default(0)->comment('số giờ chạy tự động');
            $table->unsignedTinyInteger('status')->default(0)->comment('0: chưa hoàn thành, 1: đã hoàn thành, 2: đã bỏ');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_statuses');
    }
};
