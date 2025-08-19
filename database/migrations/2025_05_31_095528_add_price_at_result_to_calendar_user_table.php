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
        Schema::table('calendar_user', function (Blueprint $table) {
            $table->unsignedDecimal('price_at_result', 8, 2)->nullable()->after('user_id')->comment('Giá giảng dạy tại thời điểm nhập kết quả');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('calendar_user', function (Blueprint $table) {
            $table->dropColumn('price_at_result');
        });
    }
};
