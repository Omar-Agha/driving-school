<?php

namespace App\Filament\School\Resources\JoinSchoolRequestResource\Pages;

use App\Filament\School\Resources\JoinSchoolRequestResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageJoinSchoolRequests extends ManageRecords
{
    protected static string $resource = JoinSchoolRequestResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
