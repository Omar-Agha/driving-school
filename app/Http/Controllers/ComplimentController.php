<?php

namespace App\Http\Controllers;

use App\Models\Compliment;
use Illuminate\Http\Request;

class ComplimentController extends Controller
{
    public function create()
    {
        request()->validate(['body' => 'required']);
        $user = auth()->user();
        $data = Compliment::create(['user_id' => $user->id, 'body' => request('body')]);
        return $this->sendSuccess($data, 'created Successfully');
    }
}
