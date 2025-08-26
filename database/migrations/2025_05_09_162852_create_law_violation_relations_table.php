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
        Schema::create('law_violation_relations', function (Blueprint $table) {
            $table->unsignedBigInteger('violation_id');
            $table->unsignedBigInteger('related_violation_id');
            $table->foreign('violation_id')->references('id')->on('law_violations')->onDelete('cascade');
            $table->foreign('related_violation_id')->references('id')->on('law_violations')->onDelete('cascade');
            $table->primary(['violation_id', 'related_violation_id']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('law_violation_relations');
    }
};
