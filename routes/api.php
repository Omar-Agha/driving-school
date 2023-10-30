<?php

use App\Enums\UserRoleEnum;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\VideoController;
use App\Http\Requests\StudentRequest;
use App\Models\Event;
use App\Models\School;
use App\Models\SchoolPackageSubscriptionsPivot;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Saade\FilamentFullCalendar\Data\EventData;

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
});

Route::get("video", [VideoController::class,"index"])->name("video.index");
Route::get("course", [CourseController::class, "index"])->name("course.index");


Route::get("test", function () {

    $lala = School::doesntHave("activePackage")->with("activePackage")->get();

    return School::all();
    // $lala = School::whereHas("packages")->with("packages")->get();



    return $lala;
    return 'jjj';
    return UserRoleEnum::toArray();
    // return EventData::make()
    //     ->id(1)
    //     ->start(Carbon::now())
    //     ->title("jjsfd");
    return Event::query()
        ->where('starts_at', '>=', Carbon::now()->subYear())
        ->where('ends_at', '<=', Carbon::now()->addYear())
        ->get()
        ->map(
            fn(Event $event) =>
            [
                'title' => $event->id,
                'start' => $event->starts_at,
                'end' => $event->ends_at,
                // 'url' => EventResource::getUrl(name: 'view', parameters: ['record' => $event]),
                'shouldOpenUrlInNewTab' => true
            ]
            // EventData::make()
            //     ->id(1)
            //     ->title("jj")
            //     ->start(Carbon::now())
            //     // ->end(Carbon::now())

        )
        ->all();
});