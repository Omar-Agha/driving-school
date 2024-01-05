<?php

namespace App\Http\Controllers;

use App\Http\Resources\DefaultResource;

use App\Http\Resources\JoinSchoolRequestResource;
use App\Models\Event;
use App\Models\JoinSchoolRequest;
use App\Models\School;
use App\Models\Student;
use Carbon\Carbon;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    public function requestJoinSchool()
    {

        request()->validate(['code' => 'required']);
        $code = request('code');
        /** @var Student */
        $student = auth()->user()->student;
        $school = School::whereCode($code)->first();
        if ($school == null) return abort(404, 'invalid School code');
        if ($school->id == $student->school_id) abort(400, 'student is already in the school');
        $joinRequest = JoinSchoolRequest::whereSchoolId($school->id)->whereStudentId($student->id)->whereNull('status')->first();

        if ($joinRequest != null) abort(400, 'you already sent request');
        $request = $student->requestJoinSchool($school->id, $student->id);

        return DefaultResource::make($request);
    }


    public function getJoinSchoolRequests()
    {
        /** @var Student */
        $student = auth()->user()->student;
        $requests = $student->load('joinSchoolRequest.school')->joinSchoolRequest;
        return JoinSchoolRequestResource::collection($requests);
    }

    public function getUpcomingStudentAppointments()
    {

        /** @var Student */
        $student = auth()->user()->student;
        return DefaultResource::make($student->appointments->where('start', '>=', Carbon::now())->load(['lesson', 'vehicle', 'instructor']));
    }

    public function getPreviousStudentAppointments()
    {
        /** @var Student */
        $student = auth()->user()->student;
        return DefaultResource::make($student->appointments->where('start', '<', Carbon::now())->load(['lesson', 'vehicle', 'instructor']));
    }

    public function getStudentAppointment(Event $appointment)
    {
        return DefaultResource::make($appointment->load(['student', 'instructor', 'vehicle', 'lesson']));
    }
}
