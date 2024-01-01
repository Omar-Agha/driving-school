<?php

use App\Models\Event;
use App\Models\Instructor;
use App\Models\SchoolLesson;
use App\Models\Student;
use App\Models\Vehicle;
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
        Event::truncate();
        Schema::table('events', function (Blueprint $table) {
            $table->foreignIdFor(Student::class)->constrained()->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignIdFor(Vehicle::class)->constrained()->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignIdFor(Instructor::class)->constrained()->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignIdFor(SchoolLesson::class)->constrained()->cascadeOnDelete()->cascadeOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $table->dropForeignIdFor(Student::class);
            $table->dropForeignIdFor(Vehicle::class);
            $table->dropForeignIdFor(Instructor::class);
            $table->dropForeignIdFor(SchoolLesson::class);
        });
    }
};
