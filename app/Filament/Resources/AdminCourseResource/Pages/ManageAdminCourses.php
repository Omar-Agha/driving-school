<?php

namespace App\Filament\Resources\AdminCourseResource\Pages;

use App\Filament\Resources\AdminCourseResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageAdminCourses extends ManageRecords
{
    protected static string $resource = AdminCourseResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
