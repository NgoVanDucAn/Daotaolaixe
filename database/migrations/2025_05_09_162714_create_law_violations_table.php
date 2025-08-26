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
        Schema::create('law_violations', function (Blueprint $table) {
            $table->id();
            $table->text('description');
            $table->text('entities');
            $table->text('fines');
            $table->text('additional_penalties')->nullable();
            $table->text('remedial')->nullable();
            $table->text('other_penalties')->nullable();
            $table->unsignedBigInteger('type_id');
            $table->unsignedBigInteger('topic_id');
            $table->text('image')->nullable();
            $table->text('keyword')->nullable();
            $table->foreign('type_id')->references('id')->on('law_vehicle_types')->onDelete('cascade');
            $table->foreign('topic_id')->references('id')->on('law_topics')->onDelete('cascade');
            $table->timestamps();
            $table->unsignedBigInteger('violation_no')->unique();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('law_violations');
    }
};
