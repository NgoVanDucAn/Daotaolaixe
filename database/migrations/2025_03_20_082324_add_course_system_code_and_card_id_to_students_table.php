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
        Schema::table('students', function (Blueprint $table) {
            $table->string('course_system_code')->nullable()->after('student_code')->comment('Mã khóa học trên hệ thống sát hạch');
            $table->string('card_id')->nullable()->after('course_system_code')->comment('Mã số thẻ được đính kèm khi thực hiện sát hạch');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            $table->dropColumn(['course_system_code', 'card_id']);
        });
    }
};
