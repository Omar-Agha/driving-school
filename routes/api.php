<?php

use App\Enums\UserRoleEnum;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\SchoolController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\VideoController;
use App\Http\Requests\StudentRequest;
use App\Models\Event;
use App\Models\School;
use App\Models\SchoolPackageSubscriptionsPivot;
use App\Models\Student;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Benchmark;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Saade\FilamentFullCalendar\Data\EventData;
use Illuminate\Support\Number;

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
Route::get("course", [CourseController::class, "index"])->name("course.index");

Route::prefix('school')->middleware('auth:api')->group(function () {
    Route::post("accept-student-request/{request}", [SchoolController::class, "acceptStudentRequest"])->name("school.accept-student-request");
    Route::post("refuse-student-request/{request}", [SchoolController::class, "refuseStudentRequest"])->name("school.refuse-student-request");
});


Route::prefix('student')->middleware('auth:sanctum')->group(function () {
    Route::post("request-join-school", [StudentController::class, "requestJoinSchool"])->name("student.request-join-school")->middleware('role:student');
    Route::get("request-join-school", [StudentController::class, "getJoinSchoolRequests"])->name("student.get-request-join-school")->middleware('role:student');
    Route::get("appointments", [StudentController::class, "getStudentAppointments"])->name("student.get-student-appointments")->middleware('role:student');
    Route::get("appointments/{appointment}", [StudentController::class, "getStudentAppointment"])->name("student.get-student-appointments")->middleware('role:student');
});



Route::get("test", function () {
    return Event::all();
    return;
});
