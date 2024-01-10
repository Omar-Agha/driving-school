<?php

use App\Models\Event;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return redirect()->route('filament.admin.pages.dashboard');
});

Route::get('/test', function (Request $request) {
    if (request()->ajax()) {
        $data = Event::whereDate('start', '>=', request('start'))
            ->whereDate('end', '<=', request('end'))
            ->get(['id', 'name', 'start', 'end']);
        return response()->json($data);
    }
    return view('test');
});

Route::get('/api-full-calender', function (Request $request) {



    // return Event::all();
    $data = Event::query()
        ->whereDate('start', '>=', request()->date('startStr'))
        ->whereDate('end', '<=', request()->date('endStr'))
        ->with(['student', 'instructor', 'vehicle', 'lesson'])
        ->get()
        ->map(
            fn (Event $event) =>
            [
                'id' => $event->id,
                'title' => $event->name,
                'start' => $event->start,
                'end' => $event->end,
                // 'url' => EventResource::getUrl(name: 'view', parameters: ['record' => $event]),
                'shouldOpenUrlInNewTab' => true,


                'instructor_id' => $event->instructor_id,
                'student_id' => $event->student_id,
                'vehicle_id' => $event->vehicle_id,
                'school_lesson_id' => $event->school_lesson_id,
                "student_name" => Str::replaceArray('?', [$event->student->first_name, $event->student->last_name], "? ?"),
                'instructor_name' => $event->instructor->name,
                'vehicle_name' => $event->vehicle->number_plate,
                'lesson_name' => $event->lesson->title,
                'car_type' => $event->car_type,
                'location' => "At " . $event->location . " location",
                'limit_time_to_cancel' => $event->limit_time_to_cancel

            ]
        )
        ->all();
    return response()->json($data);
});



Route::post('full-calender/action', function (Request $request) {


    if (request()->ajax()) {
        if (request('type') == 'add') {
            $event = Event::create(request()->all());

            return response()->json($event);
        }

        if (request('type') == 'update') {
            $event = Event::find(request('id'))->update(request()->all());

            return response()->json($event);
        }

        if (request('type') == 'delete') {
            $event = Event::find(request('id'))->delete();

            return response()->json($event);
        }
    }
});
