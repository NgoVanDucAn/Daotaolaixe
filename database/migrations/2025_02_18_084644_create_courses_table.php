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
        Schema::create('courses', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique()->comment('Mã khóa học');
            $table->unsignedBigInteger('curriculum_id')->nullable()->comment('Loại khóa học như cơ bản nâng cao');
            $table->string('number_bc')->nullable()->comment('Số hồ sơ quản lý khóa học, báo cáo');
            $table->date('date_bci')->nullable()->comment('Ngày bắt đầu báo cáo khóa học');
            $table->date('start_date')->nullable()->comment('Ngày bắt đầu khóa học');
            $table->date('end_date')->nullable()->comment('Ngày kết thúc khóa học');
            $table->integer('number_students')->nullable()->comment('Số lượng học viên');
            $table->string('decision_kg')->nullable()->comment('Lưu thông tin quyết định giấy tờ liên quan của khóa học');
            $table->integer('duration')->nullable()->comment('Tổng số giờ học của khóa học');
            $table->decimal('km', 8, 3)->default(0)->comment('số km cần để hoàn thành khóa học');
            $table->unsignedBigInteger('tuition_fee')->nullable()->comment('Học phí khóa học');
            $table->tinyInteger('status')->default(1)->comment('Trạng thái khóa học (active, inactive)');
            $table->timestamps();

            $table->foreign('curriculum_id')->references('id')->on('curriculums')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('courses');
    }
};
