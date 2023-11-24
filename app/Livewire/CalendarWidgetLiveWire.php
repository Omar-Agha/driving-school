<?php

namespace App\Livewire;

use App\Models\Event;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Widgets\Widget;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Saade\FilamentFullCalendar\Actions\EditAction;
use Saade\FilamentFullCalendar\Actions\ViewAction;
use Saade\FilamentFullCalendar\Data\EventData;
use Saade\FilamentFullCalendar\Widgets\FullCalendarWidget;
use Saade\FilamentFullCalendar\Actions\CreateAction;

class CalendarWidgetLiveWire extends FullCalendarWidget
{
    public Model|string|null $model = Event::class;

    public function fetchEvents(array $fetchInfo): array
    {

        return Event::query()
            ->where('start', '>=', $fetchInfo['start'])
            ->where('end', '<=', $fetchInfo['end'])
            ->get()
            ->map(
                fn(Event $event) =>
                [
                    'id' => $event->id,
                    'title' => $event->id,
                    'start' => $event->start,
                    'end' => $event->end,
                    // 'url' => EventResource::getUrl(name: 'view', parameters: ['record' => $event]),
                    'shouldOpenUrlInNewTab' => true
                ]
            )
            ->all();
    }


    protected function viewAction(): Action
    {
        return ViewAction::make();
    }

    public function onEventClick(array $event): void
    {

        parent::onEventClick($event);

    }
    protected function modalActions(): array
    {
        return [
            EditAction::make()
                ->mountUsing(
                    function (Event $record, Form $form, array $arguments) {
                        $form->fill([
                            'name' => $record->name,
                            'start' => $arguments['event']['start'] ?? $record->start,
                            'end' => $arguments['event']['end'] ?? $record->end
                        ]);
                    }
                )
            ,

            DeleteAction::make(),
        ];
    }

    public function getFormSchema(): array
    {
        return [
            DateTimePicker::make('start')->required(),
            DateTimePicker::make('end')->required(),
            TextInput::make('name'),
        ];
    }


    protected function headerActions(): array
    {

        return [
            CreateAction::make()
                ->mountUsing(
                    function (Form $form, array $arguments) {

                        $form->fill([
                            'start' => $arguments['start'] ?? null,
                            'end' => $arguments['end'] ?? null
                        ]);
                    }
                )
        ];
    }
}