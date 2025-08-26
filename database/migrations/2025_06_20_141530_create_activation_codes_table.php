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
        Schema::create('activation_codes', function (Blueprint $table) {
            $table->id();
            $table->string('device_mobile_id')->nullable()->comment('ID thiết bị đã kích hoạt');
            $table->string('device_web_id')->nullable()->comment('ID thiết bị đã kích hoạt');
            $table->string('activation_code')->unique()->comment('mã kích hoạt');
            $table->string('buyer_name')->comment('tên người mua');
            $table->unsignedInteger('pakage_time')->comment('số ngày được sử dụng tính từ thời gian kích hoạt, nếu là 1 thì tính là gói vĩnh viễn, nếu là số khác thì tính là số ngày');
            $table->dateTime('activated_at')->nullable()->comment('ngày kích hoạt');
            $table->dateTime('expires_at')->nullable()->comment('ngày hết hạn');
            $table->unsignedInteger('status')->default(0)->comment('trạng thái kích hoạt: 0 là chưa kích hoạt, 1 là đã kích hoạt');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activation_codes');
    }
};
