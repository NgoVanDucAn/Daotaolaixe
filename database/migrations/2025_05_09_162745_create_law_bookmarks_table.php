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
        Schema::create('law_bookmarks', function (Blueprint $table) {
            $table->id();
            $table->string('bookmark_code', 50);
            $table->unsignedBigInteger('bookmark_type_id');
            $table->text('bookmark_description');
            $table->foreign('bookmark_type_id')->references('id')->on('law_bookmark_types')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('law_bookmarks');
    }
};
