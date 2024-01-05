<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\QuestionToDoController;
use App\Http\Controllers\SchoolController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\VideoController;

use App\Models\QuestionToDo;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;


use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::prefix('auth')->group(function () {
    Route::post('student/register', [AuthController::class, 'registerStudent']);

    Route::post('login', [AuthController::class, 'login']);
    Route::get('account-configuration', [AuthController::class, 'accountConfiguration'])->middleware('auth:sanctum');
});

Route::get("video", [VideoController::class, "index"])->name("video.index");
Route::get("video/{video}", [VideoController::class, "get"])->name('video.get');
Route::get("course", [CourseController::class, "index"])->name("course.index");

Route::prefix('school')->middleware('auth:api')->group(function () {
    Route::post("accept-student-request/{request}", [SchoolController::class, "acceptStudentRequest"])->name("school.accept-student-request");
    Route::post("refuse-student-request/{request}", [SchoolController::class, "refuseStudentRequest"])->name("school.refuse-student-request");
});


Route::prefix('student')->middleware('auth:sanctum')->group(function () {
    Route::post("request-join-school", [StudentController::class, "requestJoinSchool"])->name("student.request-join-school")->middleware('role:student');
    Route::get("request-join-school", [StudentController::class, "getJoinSchoolRequests"])->name("student.get-request-join-school")->middleware('role:student');
    Route::get("appointments-upcoming", [StudentController::class, "getUpcomingStudentAppointments"])->name("student.get-student-upcoming-appointments")->middleware('role:student');
    Route::get("appointments-previous", [StudentController::class, "getPreviousStudentAppointments"])->name("student.get-student-previous-appointments")->middleware('role:student');

    Route::get("appointments/{appointment}", [StudentController::class, "getStudentAppointment"])->name("student.get-student-appointments")->middleware('role:student');
});

Route::prefix('questions-to-do')->middleware('auth:sanctum')->group(function () {
    Route::get("groups", [QuestionToDoController::class, "getQuestionGroups"])->name("question_to_do.group");
    Route::get("questions", [QuestionToDoController::class, "getQuestion"])->name("question_to_do.questions");
    Route::post("answer/{question}", [QuestionToDoController::class, "answerQuestion"])->name("question_to_do.answer-question");
});



Route::get("test", function () {
    $user_id = 5;

    $groups =  QuestionToDo::withCount(['questionAnswer as answers_count' =>  function (Builder $query) use ($user_id) {
        $query->where('user_id', $user_id);
    }])->get();

    // return $groups;
    $output = [];
    foreach ($groups as $item) {
        $output[$item->group]['total'] = ($output[$item->group]['total'] ?? 0) + 1;
        $output[$item->group]['answers'] = ($output[$item->group]['answers'] ?? 0) + $item->answers_count;
    }
    return $output;

    return QuestionToDo::groupBy('group')->select('group', DB::raw('count(*) as total'))->get();
});
