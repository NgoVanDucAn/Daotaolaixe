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
        Schema::create('driving_sessions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('student_id');
            $table->unsignedBigInteger('instructor_id');
            $table->uuid('session_id')->unique()->comment('id của phiên học');
            $table->timestamp('start_time')->comment('thời gian bắt đầu');
            $table->timestamp('end_time')->nullable()->comment('thời gian kết thúc');
            $table->integer('duration')->comment('thời gian chạy');
            $table->decimal('distance', 8, 2)->comment('quãng đường chạy');
            $table->decimal('start_lat', 10, 7)->nullable()->comment('vĩ độ bắt đầu');
            $table->decimal('start_lng', 10, 7)->nullable()->comment('kinh độ bắt đầu');
            $table->decimal('end_lat', 10, 7)->nullable()->comment('vĩ độ kết thúc');
            $table->decimal('end_lng', 10, 7)->nullable()->comment('kinh độ kết thúc');
            $table->string('trainee_id')->comment('id của học viên trên hệ thống sát hạch');
            $table->string('trainee_name')->comment('tên của học viên');
            $table->string('instructor_name')->comment('tên của giáo viên');
            $table->string('vehicle_plate')->comment('biển số xe');
            $table->string('ten_khoa_hoc')->comment('tên khóa học');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('driving_sessions');
    }
};
