<?php

namespace App\Http\Controllers;

use App\Http\Resources\DefaultResource;

use App\Http\Resources\JoinSchoolRequestResource;
use App\Models\Event;
use App\Models\InstructorWorkTime;
use App\Models\JoinSchoolRequest;
use App\Models\ProgressItem;
use App\Models\School;
use App\Models\Student;
use Carbon\Carbon;
use DateTime;
use Illuminate\Http\Request;

class StudentController extends Controller
{

    function getPreferredInstructor()
    {
        return $this->sendSuccess(student()->preferredInstructor);
    }

    public function requestJoinSchool()
    {

        request()->validate(['code' => 'required', 'course_id' => 'required|exists:admin_courses,id']);
        $code = request('code');
        /** @var Student */
        $student = auth()->user()->student;
        $school = School::whereCode($code)->first();
        if ($school == null) return $this->sendError('invalid school code');
        if ($school->id == $student->school_id) return $this->sendError('student is already in a school');
        $joinRequest = JoinSchoolRequest::whereSchoolId($school->id)->whereStudentId($student->id)->whereNull('status')->first();

        if ($joinRequest != null) return $this->sendError('you have already sent a requests');
        $request = $student->requestJoinSchool($school->id, $student->id, request('course_id'));

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


    public function getPreferredInstructorWorkTime()
    {
        request()->validate(['year' => 'int|required', 'month' => 'int|required']);
        $student = student();
        $instructor_id = $student->preferred_instructor_id;
        $school_id = $student->school_id;

        $start_date = Carbon::create(request('year'), request('month'));
        $end_date = $start_date->copy()->addMonth();


        //get all month dates (from 1 to 31)
        $available_dates = collect();
        for ($date = $start_date->copy(); $date < $end_date; $date->addDay()) {
            $available_dates->add($date->copy());
        }


        //get instructor work & break time
        $instructor_time =  InstructorWorkTime::where('instructor_id', $instructor_id)->where('school_id', $school_id)->get();
        $work_time = collect($instructor_time)->filter(fn ($row) => !$row->is_break);
        $break_time = collect($instructor_time)->filter(fn ($row) => $row->is_break);


        //remove date that instructor doesn't work in it
        foreach ($available_dates as $date) {
            $day_number = $date->dayOfWeek;
            //if day_number is not exists in $work_time table then remove it fro available 
            if (!$work_time->contains(fn (InstructorWorkTime $x) => $x->day == $day_number)) {

                $available_dates = $available_dates->filter(fn (Carbon $x) => $x->notEqualTo($date))->flatten();
            }
        }


        //fill available time  
        $available_time = collect();
        foreach ($available_dates as $date) {
            $day_number = $date->dayOfWeek;
            $available_time[$date->format("Y-m-d")] = collect($work_time->where(fn (InstructorWorkTime $x) => $x->day == $day_number))->transform(fn ($x) => ['from' => $x->start, 'to' => $x->end]);
        }

        return [
            'available' => $available_dates->map(fn (Carbon $date) => $date->format('Y-m-d')),
            "available_time" => $available_time

        ];
    }
}
