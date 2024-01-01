<?php

namespace App\Filament\School\Resources\SchoolLessonResource\Pages;

use App\Filament\School\Resources\SchoolLessonResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSchoolLessons extends ListRecords
{
    protected static string $resource = SchoolLessonResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
