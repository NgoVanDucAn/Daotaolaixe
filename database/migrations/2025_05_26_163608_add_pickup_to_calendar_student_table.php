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
            $table->boolean('pickup')->default(false)->after('exam_number');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('calendar_student', function (Blueprint $table) {
            $table->dropColumn('pickup');
        });
    }
};
