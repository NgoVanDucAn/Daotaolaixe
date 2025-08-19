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
        Schema::table('calendars', function (Blueprint $table) {
            $table->decimal('exam_fee', 10, 2)->nullable()->after('exam_field_id');
            $table->date('exam_fee_deadline')->nullable()->after('exam_fee');
        });

        Schema::table('calendar_student', function (Blueprint $table) {
            $table->string('exam_number')->nullable()->after('attempt_number');
            $table->timestamp('exam_fee_paid_at')->nullable()->after('exam_number');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('calendars', function (Blueprint $table) {
            $table->dropColumn(['exam_fee', 'exam_fee_deadline']);
        });

        Schema::table('calendar_student', function (Blueprint $table) {
            $table->dropColumn(['exam_number', 'exam_fee_paid_at']);
        });
    }
};
