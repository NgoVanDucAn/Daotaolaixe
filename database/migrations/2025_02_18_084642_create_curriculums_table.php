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
        Schema::create('curriculums', function (Blueprint $table) {
            $table->id();
            $table->string('name')->comment('Tên giáo trình');
            $table->string('rank_name')->comment('Tên hạng giấy phép, ví dụ: B1, B2, C...');
            $table->string('title')->comment('Loại giáo trình: Cơ bản / Nâng cao');
            $table->text('description')->nullable()->comment('Mô tả về giáo trình: là nội dung hiển thị để giới thiệu về khóa học ở client');
            $table->timestamps();
        
            $table->unique(['rank_name', 'title']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('curriculums');
    }
};
