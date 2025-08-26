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
            $table->dropColumn('practice_field');
            $table->unsignedBigInteger('stadium_id')->nullable()->after('course_id');
            $table->foreign('stadium_id')->references('id')->on('stadiums')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('course_students', function (Blueprint $table) {
            $table->dropForeign(['stadium_id']);
            $table->dropColumn('stadium_id');
            $table->string('practice_field')->nullable();
        });
    }
};
