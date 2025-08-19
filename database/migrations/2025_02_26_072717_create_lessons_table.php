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
        Schema::create('lessons', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('curriculum_id')->comment('Giáo trình id');
            $table->string('title')->comment('Tên bài học');
            $table->text('description')->nullable()->comment('Mô tả chi tiết về bài học');
            $table->unsignedInteger('sequence')->comment('Thứ tự của bài học trong giáo trình');
            $table->enum('visibility', ['private', 'public'])->default('private');
            $table->timestamps();

            $table->foreign('curriculum_id')->references('id')->on('curriculums')->onDelete('cascade');
            $table->unique(['curriculum_id', 'sequence']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lessons');
    }
};
