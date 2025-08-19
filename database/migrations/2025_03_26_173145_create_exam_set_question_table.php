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
        Schema::create('exam_set_question', function (Blueprint $table) {
            $table->id();
            $table->foreignId('exam_set_id')->constrained('exam_sets')->onDelete('cascade');
            $table->foreignId('question_id')->constrained('quizzes')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exam_set_question');
    }
};
