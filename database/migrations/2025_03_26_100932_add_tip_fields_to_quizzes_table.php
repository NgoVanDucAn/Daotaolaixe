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
        Schema::table('quizzes', function (Blueprint $table) {
            $table->text('tip')->nullable()->comment('cách làm nhanh, nhận diện đáp án cho câu hỏi đó');
            $table->string('tip_image')->nullable()->comment('hình ảnh mô tả việc nhận diện đáp án đúng cho câu hỏi đó');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('quizzes', function (Blueprint $table) {
            $table->dropColumn('tip');
            $table->dropColumn('tip_image');
        });
    }
};
