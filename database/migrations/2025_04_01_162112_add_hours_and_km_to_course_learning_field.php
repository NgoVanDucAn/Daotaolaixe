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
        Schema::table('course_learning_field', function (Blueprint $table) {
            $table->float('hours', 8, 3)->default(0)->comment('Số giờ cần phải chạy cho môn học này của khóa học')->after('learning_field_id');
            $table->float('km', 8, 3)->default(0)->comment('Số km cần phải chạy cho môn học này của khóa học')->after('hours');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('course_learning_field', function (Blueprint $table) {
            $table->dropColumn('hours');
            $table->dropColumn('km');
        });
    }
};
