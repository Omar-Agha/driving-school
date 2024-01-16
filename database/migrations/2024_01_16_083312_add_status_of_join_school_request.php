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
        Schema::table('join_school_requests', function (Blueprint $table) {
            $table->enum('status', ['refused', 'accepted', 'canceled'])->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('join_school_requests', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
};
