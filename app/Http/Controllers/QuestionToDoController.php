<?php

namespace App\Http\Controllers;

use App\Enums\QuestionToDoAnswerEnum;
use App\Http\Resources\DefaultResource;
use App\Models\QuestionToDo;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Symfony\Component\Console\Input\Input;



class QuestionToDoController extends Controller
{
    public function getQuestionGroups()
    {

        $groups =  QuestionToDo::withCount(['questionAnswer as answers_count' =>  function ($query) {
            $query->where('user_id', auth()->user()->id);
        }])->get();

        $output = [];
        foreach ($groups as $item) {
            $output[$item->group]['total'] = ($output[$item->group]['total'] ?? 0) + 1;
            $output[$item->group]['answers'] = ($output[$item->group]['answers'] ?? 0) + $item->answers_count;
        }
        $output =
            collect($output)->map(function ($item, $key) {
                return [
                    'name' => $key,
                    'total' => $item['total'],
                    'answers' => $item['answers'],
                ];
            })->values()->all();


        return DefaultResource::collection($output);
        // return DefaultResource::make(QuestionToDo::groupBy('group')->select('group', DB::raw('count(*) as total'))->get());
    }

    public function getQuestion()
    {
        
        $group = request('group');
        $result =  QuestionToDo::with([
            'questionToDoAnswer'
            => function ($builder) {
                $builder->where('user_id', auth()->user()->id);
            }
        ])
        ->whereGroup($group)
        ->get()
            ->transform(function ($question) {
                $answer = $question->getRelation('questionToDoAnswer');
                $answerValue = null;
                if (count($answer) != 0)
                    $answerValue = $answer[0]->answer;

                $question->answer =  $answerValue;
                $question->unsetRelation('questionToDoAnswer');
                return $question;
            });
        return DefaultResource::make($result);
    }

    public function answerQuestion(QuestionToDo $question)
    {
        request()->validate([
            'answer' => ['required', Rule::enum(QuestionToDoAnswerEnum::class)]
        ]);

        $question->answerQuestion(auth()->user(), QuestionToDoAnswerEnum::from(request('answer')));

        return DefaultResource::make(['message' => 'success']);
    }
}
