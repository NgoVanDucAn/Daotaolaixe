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
            $table->boolean('wrong')->default(0)->after('question')->comment('câu hỏi hay bị sai');
            $table->boolean('mandatory')->default(0)->after('question')->comment('quy định câu hỏi là câu bắt buộc đúng hay không');
            $table->text('explanation')->nullable()->after('question')->comment('Nội dung giải thích về câu hỏi đó');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('quizzes', function (Blueprint $table) {
            $table->dropColumn(['mandatory', 'explanation']);
        });
    }
};
