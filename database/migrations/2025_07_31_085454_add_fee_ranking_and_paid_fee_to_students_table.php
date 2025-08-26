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
        Schema::table('students', function (Blueprint $table) {
            $table->decimal('fee_ranking', 10, 2)->default(0.00)->after('ranking_id')->comment('tổng lệ phí với hạng bằng đăng ký');
            $table->decimal('paid_fee', 10, 2)->default(0.00)->after('ranking_id')->comment('lệ phí đã đóng với hạng bằng đăng ký');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            $table->dropColumn(['fee_ranking', 'paid_fee']);
        });
    }
};
