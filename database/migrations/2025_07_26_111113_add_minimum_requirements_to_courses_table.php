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
        Schema::table('courses', function (Blueprint $table) {
            $table->float('min_night_hours', 8, 2)->default(0)->after('required_km');
            $table->float('min_automatic_car_hours', 8, 2)->default(0)->after('required_km');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('courses', function (Blueprint $table) {
            $table->dropColumn([
                'min_night_hours',
                'min_automatic_car_hours',
            ]);
        });
    }
};
