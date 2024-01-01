<?php

namespace App\Filament\School\Resources\SchoolLessonResource\Pages;

use App\Filament\School\Resources\SchoolLessonResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSchoolLesson extends EditRecord
{
    protected static string $resource = SchoolLessonResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
