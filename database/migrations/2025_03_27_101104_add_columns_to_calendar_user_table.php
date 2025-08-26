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
            $table->float('hours', 8, 3)->default(0)->comment('Số giờ dạy của giáo viên trong lịch học');
            $table->float('km', 8, 3)->default(0)->comment('Số km giáo viên đã di chuyển');
            $table->float('night_hours', 8, 3)->default(0)->comment('Số giờ dạy đêm của giáo viên');
            $table->float('auto_hours', 8, 3)->default(0)->comment('Số giờ giáo viên dạy tự động');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('calendar_user', function (Blueprint $table) {
            $table->dropColumn(['hours', 'km', 'night_hours', 'auto_hours']);
        });
    }
};
