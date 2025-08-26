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
        Schema::table('calendars', function (Blueprint $table) {
            $table->date('date')->nullable();
            $table->tinyInteger('time')->unsigned()->nullable()->comment('1 = sáng, 2 = chiều, 3 = đêm nếu có');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('calendars', function (Blueprint $table) {
            $table->dropColumn(['date', 'time']);
        });
    }
};
