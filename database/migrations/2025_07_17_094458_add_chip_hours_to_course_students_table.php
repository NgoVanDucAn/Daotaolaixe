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
        Schema::table('course_students', function (Blueprint $table) {
            $table->time('gifted_chip_hours')->default('00:00:00')->after('stadium_id')->comment('số giờ chip tặng');
            $table->time('reserved_chip_hours')->default('00:00:00')->after('stadium_id')->comment('số giờ đặt tặng');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('course_students', function (Blueprint $table) {
            $table->dropColumn(['gifted_chip_hours', 'reserved_chip_hours']);
        });
    }
};
