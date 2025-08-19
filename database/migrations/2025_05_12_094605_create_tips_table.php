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
        Schema::create('tips', function (Blueprint $table) {
            $table->id();
            $table->unsignedTinyInteger('tip_type');
            $table->unsignedBigInteger('quiz_set_id')->nullable();
            $table->unsignedBigInteger('page_id')->nullable();
            $table->text('content');
            $table->json('question');
            $table->timestamps();

            $table->foreign('quiz_set_id')->references('id')->on('quiz_sets')->onDelete('cascade');
            $table->foreign('page_id')->references('id')->on('pages')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tips');
    }
};
