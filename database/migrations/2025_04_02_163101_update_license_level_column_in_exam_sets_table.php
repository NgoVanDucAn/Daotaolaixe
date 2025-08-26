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
        Schema::table('exam_sets', function (Blueprint $table) {
            $table->unsignedBigInteger('license_level')->nullable()->change();
            $table->foreign('license_level')->references('id')->on('rankings')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('exam_sets', function (Blueprint $table) {
            $table->dropForeign(['license_level']);
            $table->string('license_level')->nullable()->change();
        });
    }
};
