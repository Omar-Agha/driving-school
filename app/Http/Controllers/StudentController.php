<?php

namespace App\Http\Controllers;

use App\Http\Resources\DefaultResource;

use App\Http\Resources\JoinSchoolRequestResource;
use App\Models\Event;
use App\Models\JoinSchoolRequest;
use App\Models\ProgressItem;
use App\Models\School;
use App\Models\Student;
use Carbon\Carbon;
use DateTime;
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


    public function cancelSchoolJoinRequest(JoinSchoolRequest $request)
    {


        /** @var Student */
        $student = auth()->user()->student;
        if ($request->student_id != $student->id)
            return response(['message' => 'you must be the owner of the request'], 401);
        if ($request->status != null)
            return response(['message' => 'request is not pending'], 406);
        $request->status = 'canceled';
        $request->save();

        return DefaultResource::make(['message' => 'success']);
    }


    public function getJoinSchoolRequests()
    {
        /** @var Student */
        $student = auth()->user()->student;
        $requests = $student->load('joinSchoolRequest.school')->joinSchoolRequest;
        return JoinSchoolRequestResource::collection($requests);
    }


    public function getStudentNextAppointment()
    {
        /** @var Student */
        $student = auth()->user()->student;
        $nextAppointment  = $student->appointments
            ->where('start', '>=', Carbon::now())
            ->sortBy('start')
            ->load(['lesson', 'vehicle', 'instructor'])
            ->first();


        $nextAppointment->date =
            Carbon::parse($nextAppointment->start)->format('Y-m-d');




        return DefaultResource::make($nextAppointment);
    }

    public function getUpcomingStudentAppointments()
    {

        /** @var Student */
        $student = auth()->user()->student;
        $appointments  = $student->appointments
            ->where('start', '>=', Carbon::now())
            ->load(['lesson', 'vehicle', 'instructor']);


        $appointments->map(function ($item) {
            $item->date = Carbon::parse($item->start)->format('Y-m-d');
        });


        return DefaultResource::collection($appointments);
    }

    public function getPreviousStudentAppointments()
    {
        /** @var Student */
        $student = auth()->user()->student;

        $appointments = $student->appointments->where('start', '<', Carbon::now())->load(['lesson', 'vehicle', 'instructor']);

        $appointments->map(function ($item) {
            $item->date = Carbon::parse($item->start)->format('Y-m-d');
        });
        return DefaultResource::collection($appointments);
    }

    public function getStudentAppointment(Event $appointment)
    {
        return DefaultResource::make($appointment->load(['student', 'instructor', 'vehicle', 'lesson']));
    }


    public function cancelAppointment(Event $appointment)
    {
        $student = auth()->user()->student;
        if ($appointment->student_id != $student->id) abort(403, 'student is not the owner of the appointment');

        $diff_hours = now()->diffInHours($appointment->start, false);
        $cancellationTimeLimit = $appointment->limit_time_to_cancel;
        if ($diff_hours < 0)         return DefaultResource::make(['message' => 'success']);;
        if ($diff_hours < $cancellationTimeLimit) abort(402, "cant be canceled");


        $appointment->cancel();

        return DefaultResource::make(['message' => 'success']);
    }


    public function updateAccount()
    {

        /** @var Student */
        $student = auth()->user()->student;

        $student->update(request()->all());

        return DefaultResource::make(['message' => $student]);
    }

    
}
