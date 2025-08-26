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
            $table->tinyInteger('level')->unsigned()->default(0)->after('type')->comment('Loại thi ví dụ: 1 là thi lý thuyết, 2 là thi thực hành');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('calendars', function (Blueprint $table) {
            $table->dropColumn('level');
        });
    }
};
