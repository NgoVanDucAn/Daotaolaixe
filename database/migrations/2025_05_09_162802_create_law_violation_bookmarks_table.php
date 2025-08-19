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
        Schema::create('law_violation_bookmarks', function (Blueprint $table) {
            $table->unsignedBigInteger('violation_id');
            $table->unsignedBigInteger('bookmark_id');
            $table->foreign('violation_id')->references('id')->on('law_violations')->onDelete('cascade');
            $table->foreign('bookmark_id')->references('id')->on('law_bookmarks')->onDelete('cascade');
            $table->primary(['violation_id', 'bookmark_id']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('law_violation_bookmarks');
    }
};
