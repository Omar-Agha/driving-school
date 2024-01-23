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
        Schema::create('mobile_reset_passwords', function (Blueprint $table) {
            $table->id();
            $table->string('email');
            $table->string('code', 10);
            $table->string('token');
            $table->timestamp('created_at')->nullable();
            $table->timestamp('expires_at')->nullable();


        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mobile_reset_passwords');
    }
};
