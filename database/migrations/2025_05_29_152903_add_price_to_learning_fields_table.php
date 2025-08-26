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
        Schema::table('learning_fields', function (Blueprint $table) {
            $table->unsignedDecimal('price', 8, 2)->default(0)->comment('giá thành giảng dạy trên giờ')->after('name');
            $table->tinyInteger('teaching_mode')->default(0)->comment('0: 1 kèm 1, 1: Dạy nhiều người')->after('price');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('learning_fields', function (Blueprint $table) {
            $table->dropColumn(['price', 'teaching_mode']);
        });
    }
};
