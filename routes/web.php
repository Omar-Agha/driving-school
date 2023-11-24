<?php

use App\Models\Event;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::get('/full-calender', function (Request $request) {
    if (request()->ajax()) {
        $data = Event::whereDate('start', '>=', request('start'))
            ->whereDate('end', '<=', request('end'))
            ->get(['id', 'name', 'start', 'end']);
        return response()->json($data);
    }
    return view('test3');
});

Route::get('/api-full-calender', function (Request $request) {



    $data = Event::query()
        ->whereDate('start', '>=', request()->date('startStr'))
        ->whereDate('end', '<=', request()->date('endStr'))
        // ->whereDate('start', '>=', Carbon::create(2023, 10, 1))
        // ->whereDate('end', '<=', Carbon::create(2023, 12, 10))

        ->get()
        ->map(
            fn (Event $event) =>
            [
                'id' => $event->id,
                'title' => $event->name,
                'start' => $event->start,
                'end' => $event->end,
                // 'url' => EventResource::getUrl(name: 'view', parameters: ['record' => $event]),
                'shouldOpenUrlInNewTab' => true
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
