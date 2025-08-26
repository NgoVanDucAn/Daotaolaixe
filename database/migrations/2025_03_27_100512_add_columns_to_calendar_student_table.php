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
        Schema::table('calendar_student', function (Blueprint $table) {
            $table->float('hours', 8, 3)->default(0)->comment('Tổng số giờ học');
            $table->float('km', 8, 3)->default(0)->comment('Số km chạy được');
            $table->float('night_hours', 8, 3)->default(0)->comment('Số giờ chạy đêm');
            $table->float('auto_hours', 8, 3)->default(0)->comment('Số giờ chạy tự động');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('calendar_student', function (Blueprint $table) {
            $table->dropColumn(['hours', 'km', 'night_hours', 'auto_hours']);
        });
    }
};
