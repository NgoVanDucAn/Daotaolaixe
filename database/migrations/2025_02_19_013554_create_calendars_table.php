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
        Schema::create('calendars', function (Blueprint $table) {
            $table->id();
            $table->string('type')->comment('Kiểu lịch exam: lịch học, kiểm tra, họp');
            $table->string('name')->comment('Tên lịch');
            $table->tinyInteger('status')->default(0)->comment('Trạng thái lịch: 0: chưa hoàn thành, 1: đang diễn ra, 2: đã hoàn thành');
            $table->enum('priority', ['Low', 'Normal', 'High', 'Urgent'])->default('Normal')->comment('Mức độ ưu tiên');
            $table->timestamp('date_start')->nullable()->comment('Ngày bắt đầu');
            $table->timestamp('date_end')->nullable()->comment('Ngày kết thúc');
            $table->integer('duration')->nullable()->comment('Thời lượng');
            $table->string('location')->nullable()->comment('Địa điểm');
            $table->text('description')->nullable()->comment('Mô tả');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('calendars');
    }
};
