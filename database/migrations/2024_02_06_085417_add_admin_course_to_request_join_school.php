<?php

use App\Models\AdminCourse;
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
            $table->foreignIdFor(AdminCourse::class)->nullable()->constrained()->nullOnDelete()->cascadeOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('join_school_requests', function (Blueprint $table) {
            $table->dropForeignIdFor(AdminCourse::class);
            $table->dropColumn('admin_course_id');
        });
    }
};
