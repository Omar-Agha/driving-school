<?php

namespace App\Filament\School\Pages;

use App\Models\Event;
use App\Models\School;
use Carbon\Carbon;
use Filament\Pages\Page;

class Agenda extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.school.pages.agenda';


    public $lessons;
    public $students;
    public $vehicles;
    public $instructors;

    public $car_types = ['automatic', 'manual'];


    public $eventInstructor;


    public function save($data)
    {


        $start_date = Carbon::create($data['start_date']);
        $start_date->setHour($data['time_hour'] % 12)->setMinute($data['time_min']);
        // $start_date->setHour(1)->setMinute($data['time_min']);

        $duration = explode(':', $data['duration']);
        $duration_hour = $duration[0];
        $duration_min = $duration[1];
        $end_date = $start_date->copy();
        $end_date->addHours($duration_hour)->addMinutes($duration_min);



        if ($data['id'] == null)
            $event = new Event();
        else
            $event = Event::find($data['id']);

        // $event = new Event();
        // $event->student()->
        // dd($data);
        $event->fill([
            'start' => $start_date,
            'end' => $end_date,
            'name' => 'new',
            'car_type' => $data['car_type'],
            'location' => $data['location'],
            'limit_time_to_cancel'=>$data['limit_time_to_cancel']

        ]);



        $event->student()->associate($data['student']);
        $event->vehicle()->associate($data['vehicle']);
        $event->instructor()->associate($data['instructor']);
        $event->lesson()->associate($data['lesson']);



        // dd($event->start);
        $event->save();
        return $event;
    }


    public function deleteEvent($id)
    {
        $event = Event::find($id);
        $event->delete();
        return true;
    }


    public function mount()
    {

        /** @var School */
        $school = auth()->user()->school;

        $this->lessons = $school->lessons;
        $this->students = $school->students;
        $this->vehicles = $school->vehicles;
        $this->instructors = $school->instructors;
    }
}
