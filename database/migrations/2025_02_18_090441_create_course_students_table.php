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
        Schema::create('course_students', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained()->onDelete('cascade');
            $table->foreignId('course_id')->constrained()->onDelete('cascade');
            $table->timestamp('contract_date')->nullable()->comment('Ngày ký hợp đồng');
            $table->boolean('theory_exam')->nullable()->comment('Trang thái thi lý thuyết');
            $table->boolean('practice_exam')->nullable()->comment('Trang thái thi thực hành');
            $table->boolean('graduation_exam')->nullable()->comment('Trang thái thi tốt nghiệp');
            $table->timestamp('graduation_date')->nullable()->comment('Ngày tốt nghiệp');
            $table->foreignId('teacher_id')->nullable();
            $table->string('practice_field', 255)->nullable()->comment('Địa điểm thực hành');
            $table->text('note')->nullable()->comment('Ghi chú thông tin đặc biệt liên quan đến khóa học và học viên');
            $table->timestamp('health_check_date')->nullable()->comment('Ngày khám sức khỏe');
            $table->foreignId('sale_id')->nullable();
            $table->timestamp('exam_date')->nullable()->comment('Ngày thi');
            $table->float('hours')->default(0)->comment('Số giờ đã học');
            $table->decimal('km', 8, 2)->default(0);
            $table->tinyInteger('status')->default(1)->comment('Trạng thái khóa học (active, inactive)');
            $table->bigInteger('tuition_fee')->nullable()->comment('Học phí khóa học');
            $table->date('start_date')->nullable()->comment('Ngày khai giảng');
            $table->date('end_date')->nullable()->comment('Ngày bế giảng');
            $table->date('cabin_learning_date')->nullable()->comment('Ngày học cabin');
            $table->foreignId('exam_field_id')->nullable()->constrained('exam_fields');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('course_users');
    }
};
