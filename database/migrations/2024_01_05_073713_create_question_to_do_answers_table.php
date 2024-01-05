<?php

use App\Models\QuestionToDo;
use App\Models\User;
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
        Schema::create('question_to_do_answers', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->foreignIdFor(QuestionToDo::class)->constrained()->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignIdFor(User::class)->constrained()->cascadeOnDelete()->cascadeOnUpdate();
            $table->enum('answer',['yes','no','not-sure']);
            // $table->unique(['question_to_do_id','user_id']);

            // $table->primary(['question_to_do_id','user_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('question_to_do_answers');
    }
};
