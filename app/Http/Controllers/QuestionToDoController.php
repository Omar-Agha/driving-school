<?php

namespace App\Http\Controllers;

use App\Enums\QuestionToDoAnswerEnum;
use App\Http\Resources\DefaultResource;
use App\Models\QuestionToDo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Symfony\Component\Console\Input\Input;
use Illuminate\Database\Eloquent\Builder;


class QuestionToDoController extends Controller
{
    public function getQuestionGroups()
    {
        $groups =  QuestionToDo::withCount(['questionAnswer as answers_count' =>  function (Builder $query) {
            $query->where('user_id', auth()->user()->id);
        }])->get();

        $output = [];
        foreach ($groups as $item) {
            $output[$item->group]['total'] = ($output[$item->group]['total'] ?? 0) + 1;
            $output[$item->group]['answers'] = ($output[$item->group]['answers'] ?? 0) + $item->answers_count;
        }
        return DefaultResource::make($output);
        // return DefaultResource::make(QuestionToDo::groupBy('group')->select('group', DB::raw('count(*) as total'))->get());
    }

    public function getQuestion()
    {
        $group = request('group');
        return DefaultResource::make(QuestionToDo::whereGroup($group)->get());
    }

    public function answerQuestion(QuestionToDo $question)
    {
        request()->validate([
            'answer' => ['required', Rule::enum(QuestionToDoAnswerEnum::class)]
        ]);

        $question->answer(auth()->user(), QuestionToDoAnswerEnum::from(request('answer')));

        return DefaultResource::make(['message' => 'success']);
    }
}
