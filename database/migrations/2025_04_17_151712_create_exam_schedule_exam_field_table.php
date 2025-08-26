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
        Schema::create('exam_schedule_exam_field', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('exam_schedule_id');
            $table->unsignedBigInteger('exam_field_id');
            $table->timestamps();

            $table->foreign('exam_schedule_id')->references('id')->on('exam_schedules')->onDelete('cascade');
            $table->foreign('exam_field_id')->references('id')->on('exam_fields')->onDelete('cascade');
            $table->unique(['exam_schedule_id', 'exam_field_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exam_schedule_exam_field');
    }
};
