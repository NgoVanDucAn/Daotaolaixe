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
        Schema::table('driving_sessions', function (Blueprint $table) {
            $table->decimal('distance', 8, 3)->comment('quãng đường chạy')->change();
            $table->string('start_lat', 50)->nullable()->comment('vĩ độ bắt đầu')->change();
            $table->string('start_lng', 50)->nullable()->comment('kinh độ bắt đầu')->change();
            $table->string('end_lat', 50)->nullable()->comment('vĩ độ kết thúc')->change();
            $table->string('end_lng', 50)->nullable()->comment('kinh độ kết thúc')->change();
            $table->unsignedBigInteger('trainee_id')->comment('id của học viên trên hệ thống sát hạch')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('driving_sessions', function (Blueprint $table) {
            $table->decimal('distance', 8, 2)->comment('quãng đường chạy')->change();
            $table->decimal('start_lat', 10, 7)->nullable()->comment('vĩ độ bắt đầu')->change();
            $table->decimal('start_lng', 10, 7)->nullable()->comment('kinh độ bắt đầu')->change();
            $table->decimal('end_lat', 10, 7)->nullable()->comment('vĩ độ kết thúc')->change();
            $table->decimal('end_lng', 10, 7)->nullable()->comment('kinh độ kết thúc')->change();
            $table->string('trainee_id')->comment('id của học viên trên hệ thống sát hạch')->change();
        });
    }
};
