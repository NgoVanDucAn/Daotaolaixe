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
        Schema::table('rankings', function (Blueprint $table) {
            $table->float('min_km', 8, 3)->default(0)->after('vehicle_type');
            $table->float('min_hours', 8, 2)->default(0)->after('vehicle_type');
            $table->float('min_night_hours', 8, 2)->default(0)->after('vehicle_type');
            $table->float('min_automatic_car_hours', 8, 2)->default(0)->after('vehicle_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('rankings', function (Blueprint $table) {
            $table->dropColumn([
                'min_km',
                'min_hours',
                'min_night_hours',
                'min_automatic_car_hours',
            ]);
        });
    }
};
