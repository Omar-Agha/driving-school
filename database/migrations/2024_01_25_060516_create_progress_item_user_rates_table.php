<?php

use App\Models\Instructor;
use App\Models\ProgressItem;
use App\Models\Student;
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
        Schema::create('progress_item_user_rates', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->foreignIdFor(Student::class)->constrained()->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignIdFor(Instructor::class)->constrained()->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignIdFor(ProgressItem::class)->constrained()->cascadeOnDelete()->cascadeOnUpdate();
            $table->integer('rate');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('progress_item_user_rates');
    }
};
