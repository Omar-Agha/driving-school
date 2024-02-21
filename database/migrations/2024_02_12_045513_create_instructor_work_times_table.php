<?php

use App\Models\Instructor;
use App\Models\School;
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
        Schema::create('instructor_work_times', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->foreignIdFor(Instructor::class)->constrained()->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignIdFor(School::class)->constrained()->cascadeOnDelete()->cascadeOnUpdate();
            $table->integer("day");
            $table->time("start");
            $table->time("end");
            $table->boolean("is_break")->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('instructor_work_times');
    }
};
