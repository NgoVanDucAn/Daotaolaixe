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
        Schema::create('materials', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('lesson_id')->comment('id cho biết tài liệu thuộc bài học nào');
            $table->string('title')->nullable()->comment('Tiêu đề tài liệu');
            $table->string('type')->nullable()->comment('Loại tài liệu');
            $table->string('file_path')->nullable()->comment('Đường dẫn tới tài liệu');
            $table->string('url')->nullable()->comment('Link tới tài liệu nếu là tài liệu trực tuyến');
            $table->timestamps();

            $table->foreign('lesson_id')->references('id')->on('lessons')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('materials');
    }
};
