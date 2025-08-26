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
        Schema::table('student_exam_fields', function (Blueprint $table) {
            $table->unsignedInteger('type_exam')->nullable()->after('exam_field_id')->comment('Loại thi: 1 thi hết môn thực hành, 2 thi hết môn lý thuyết, 3 thi tốt nghiệp, 4 thi sát hạch');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('student_exam_fields', function (Blueprint $table) {
            $table->dropColumn('type_exam');
        });
    }
};
