<?php

namespace App\Models;

use App\Enums\QuestionToDoAnswerEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class QuestionToDo extends Model
{
    use HasFactory;
    protected $guarded = ['id'];



    public function questionAnswer(): HasMany
    {
        return $this->hasMany(QuestionToDoAnswer::class, 'question_to_do_id');
    }



    public function questionToDoAnswer(): HasMany
    {
        return $this->hasMany(QuestionToDoAnswer::class);
    }


    public function answerQuestion(User $user, QuestionToDoAnswerEnum $answer)
    {
        QuestionToDoAnswer::updateOrInsert([
            'user_id' => $user->id,
            'question_to_do_id' => $this->id
        ], ['answer' => $answer]);
    }
}
