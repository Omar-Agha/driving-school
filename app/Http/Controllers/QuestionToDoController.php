<?php

namespace App\Http\Controllers;

use App\Http\Resources\DefaultResource;
use App\Models\QuestionToDo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class QuestionToDoController extends Controller
{
    public function getQuestionGroups()
    {
        return DefaultResource::make(QuestionToDo::groupBy('group')->select('group', DB::raw('count(*) as total'))->get());
    }

    public function getQuestion()
    {
        $group = request('group');
        return DefaultResource::make(QuestionToDo::whereGroup($group)->get());
    }
}
