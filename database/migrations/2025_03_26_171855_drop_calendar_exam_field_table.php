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
        Schema::table('calendar_exam_field', function (Blueprint $table) {
            $table->dropForeign(['calendar_id']);
            $table->dropForeign(['exam_field_id']);
        });

        Schema::dropIfExists('calendar_exam_field');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::create('calendar_exam_field', function (Blueprint $table) {
            $table->id();
            $table->foreignId('calendar_id')->constrained()->onDelete('cascade');
            $table->foreignId('exam_field_id')->constrained()->onDelete('cascade');
            $table->string('status')->default('pending');
            $table->timestamps();
        });
    }
};
