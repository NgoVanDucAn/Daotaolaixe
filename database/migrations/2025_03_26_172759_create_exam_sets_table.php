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
        Schema::create('exam_sets', function (Blueprint $table) {
            $table->id();
            $table->string('name')->comment('tên bộ đề');
            $table->string('license_level')->comment('hạng bằng');
            $table->string('type')->comment('kiểu bộ đề: đề thi thử, đề ôn tập, câu hỏi ôn tập,etc...');
            $table->text('description')->nullable()->comment('mô tả về bộ đề');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exam_sets');
    }
};
