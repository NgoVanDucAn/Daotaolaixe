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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name', 50);
            $table->string('email', 255)->unique();
            $table->string('phone', 20)->nullable();
            $table->string('image')->nullable();
            $table->enum('gender', ['male', 'female', 'other'])->nullable();
            $table->date('dob')->nullable()->comment('Date of Birth');
            $table->string('identity_card', 20)->nullable()->comment('Identity Card Number');
            $table->string('address')->nullable();
            $table->enum('status', ['active', 'inactive'])->default('active')->comment('User status');
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
