<?php

namespace App\Filament\School\Resources\InstructorResource\Pages;

use App\Filament\School\Resources\InstructorResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewInstructor extends ViewRecord
{
    protected static string $resource = InstructorResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
