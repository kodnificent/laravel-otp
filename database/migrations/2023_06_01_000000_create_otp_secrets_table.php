<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kodnificent_laravel_otp_otp_secrets', function (Blueprint $table) {
            $table->id();
            $table->string('identifier', 64)->unique();
            $table->text('secret_key')->nullable();
            $table->text('at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kodnificent_laravel_otp_otp_secrets');
    }
};
