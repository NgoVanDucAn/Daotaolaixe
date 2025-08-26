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
            $table->unsignedBigInteger('card_id')->nullable()->comment('Mã số thẻ được đính kèm khi thực hiện sát hạch')->change();
        });
    }

    
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            $table->string('card_id')->nullable()->comment('Mã số thẻ được đính kèm khi thực hiện sát hạch')->change();
        });
    }
};
