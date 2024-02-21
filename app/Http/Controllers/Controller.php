<?php

namespace App\Http\Controllers;

use App\Models\Instructor;
use App\Models\InstructorWorkTime;
use App\Models\School;
use App\Models\Student;
use App\Models\User;
use Carbon\Carbon;
use GuzzleHttp\Promise\Create;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Response;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;


    /**
     * Send a success response.
     *
     * @param mixed $data
     * @param string|null $message
     * @param int $code
     * @return \Illuminate\Http\JsonResponse
     */
    protected function sendSuccess($data, string $message = null, int $code = Response::HTTP_OK)
    {

        return response()->json([
            'success' => true,
            'code' => $code,
            'message' => $message,
            'data' => $data
        ], $code);
    }

    /**
     * Send an error response.
     *
     * @param string|null $message
     * @param int $code
     * @param array|null $errors
     * @return \Illuminate\Http\JsonResponse
     */
    protected function sendError(string $message = null, int $code = Response::HTTP_BAD_REQUEST, array $errors = null)
    {
        $response = [
            'success' => false,
            'code' => $code,
            'message' => $message,
            'data' => []
        ];

        if (!empty($errors)) {
            $response['errors'] = $errors;
        }

        return response()->json($response, $code);
    }


    public function test()
    {
        $instructor_id = 1;
        $school_id = 1;

        $start_date = Carbon::create(2024, 1); //from request
        $end_date = $start_date->copy()->addMonth();

        $available_dates = collect();

        for ($date = $start_date->copy(); $date < $end_date; $date->addDay()) {
            $available_dates->add($date->copy());
        }


        // return ;

        // return $available_dates;

        $instructor_time =  InstructorWorkTime::where('instructor_id', $instructor_id)->where('school_id', $school_id)->get();
        $work_time = collect($instructor_time)->filter(fn ($row) => !$row->is_break);
        $break_time = collect($instructor_time)->filter(fn ($row) => $row->is_break);


        //remove date that doesnot has work time record for that date
        foreach ($available_dates as $date) {
            $day_number = $date->dayOfWeek;
            //if day_number is not exists in $work_time table then remove it fro available 
            if (!$work_time->contains(fn (InstructorWorkTime $x) => $x->day == $day_number)) {

                $available_dates = $available_dates->filter(fn (Carbon $x) => $x->notEqualTo($date))->flatten();
            }
        }

        
        $available_time = collect();
        foreach ($available_dates as $date) {
            $day_number = $date->dayOfWeek;

            if ($work_time->where(fn (InstructorWorkTime $x) => $x->day == $day_number)) {
            }
            $available_time[$date->format("Y-m-d")] = collect($work_time->where(fn (InstructorWorkTime $x) => $x->day == $day_number))->transform(fn ($x) => ['from' => $x->start, 'to' => $x->end]);
        }

        return [
            'available' => $available_dates->map(fn (Carbon $date) => $date->format('Y-m-d')),
            "available_time" => $available_time

        ];
        return Student::with('preferredInstructor')->get();
        return school();
        return $this->sendError("Error my");

        return $this->sendSuccess(User::all(), "data", Response::HTTP_BAD_GATEWAY);
    }
}
