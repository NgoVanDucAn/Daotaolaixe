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
        Schema::create('notifications', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('type')->comment('Kiểu thông báo');
            $table->morphs('notifiable'); //Chỉ định thông báo liên quan đến đối tượng nào?
            $table->text('data')->comment('Dữ liệu thông báo');
            $table->timestamp('read_at')->nullable()->comment('Thời gian đọc thông báo');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
