<?php

namespace App\Filament\School\Resources\InstructorWorkTimeResource\Pages;

use App\Filament\School\Resources\InstructorWorkTimeResource;
use App\Models\Instructor;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;
use Illuminate\Database\Eloquent\Model;
use Filament\Resources\Components\Tab;
use Illuminate\Database\Eloquent\Builder;

class ManageInstructorWorkTimes extends ManageRecords
{
    protected static string $resource = InstructorWorkTimeResource::class;


    public Model | int | string | null $instructor;

    public function getHeading(): string
    {
        return "Work Time For: " . Instructor::find(1)->full_name;
    }


    public function getTabs(): array
    {
        return [
            'work' => Tab::make()
                ->modifyQueryUsing(fn (Builder $query) => $query->where('is_break', false)),

            'break' => Tab::make()
                ->modifyQueryUsing(fn (Builder $query) => $query->where('is_break', true)),
        ];
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->mutateFormDataUsing(function (array $data): array {
                    $data['school_id'] = school()->id;
                    $data['instructor_id'] = $this->instructor;

                    return $data;
                }),
        ];
    }
}
