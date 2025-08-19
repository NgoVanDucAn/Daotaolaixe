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
        Schema::create('vehicles', function (Blueprint $table) {
            $table->id();
            $table->string('license_plate')->unique()->comment('biển số');
            $table->string('model');
            $table->foreignId('ranking_id')->constrained()->onDelete('cascade');
            $table->string('type')->comment('loại xe: xe con, xe 7 chỗ, xe ? chỗ,...');
            $table->string('color')->comment('màu sắc của xe');
            $table->string('training_license_number')->unique()->nullable()->comment('số giấy phép tập lái');
            $table->year('manufacture_year')->comment('năm sản xuất');
            $table->text('description')->nullable()->comment('mô tả');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vehicles');
    }
};
