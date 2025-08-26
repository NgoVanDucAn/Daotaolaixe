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
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->string('student_code')->unique()->comment('Mã học viên');
            $table->string('name', 50);
            $table->string('email', 255)->unique();
            $table->string('phone', 20)->unique();
            $table->string('image')->nullable();
            $table->enum('gender', ['male', 'female', 'other'])->nullable();
            $table->date('dob')->nullable()->comment('Date of Birth');
            $table->string('identity_card', 20)->nullable()->comment('Identity Card Number');
            $table->string('address')->nullable();
            $table->enum('status', ['active', 'inactive'])->default('active')->comment('User status');
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->string('description')->nullable()->comment('Mô tả học viên, có thể lưu các thông tin khác như có quan tâm đến các bằng khác không, mức độ quan tâm');
            $table->timestamp('became_student_at')->nullable();
            $table->boolean('is_student')->default(false);
            $table->unsignedBigInteger('sale_support')->nullable()->comment('người chăm sóc khách hàng');
            $table->unsignedBigInteger('lead_source')->nullable()->comment('nguồn khách hàng');
            $table->unsignedBigInteger('converted_by')->nullable()->comment('người xác nhận đóng phí và chuyển đổi tài khoản khách hàng thành học viên');
            $table->timestamps();

            $table->foreign('sale_support')->references('id')->on('users')->onDelete('set null');
            $table->foreign('lead_source')->references('id')->on('lead_sources')->onDelete('set null');
            $table->foreign('converted_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
