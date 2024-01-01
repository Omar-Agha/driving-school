<?php

namespace App\Http\Controllers;

use App\Http\Resources\DefaultResource;
use App\Models\JoinSchoolRequest;
use Illuminate\Http\Request;

class SchoolController extends Controller
{
    public function acceptStudentRequest(JoinSchoolRequest $request)
    {
        $school = auth()->user()->school;
        if ($school == null) abort(403, "only school account is allowed");
        if ($school->id != $request->school_id) abort(403, "this school is not the owner of the request");

        $request->accept();
        return DefaultResource::make(['message' => "done"]);
    }


    public function refuseStudentRequest(JoinSchoolRequest $request)
    {
        $school = auth()->user()->school;
        if ($school == null) abort(403, "only school account is allowed");
        if ($school->id != $request->school_id) abort(403, "this school is not the owner of the request");

        $request->refuse();
        return DefaultResource::make(['message' => "done"]);
    }
}
