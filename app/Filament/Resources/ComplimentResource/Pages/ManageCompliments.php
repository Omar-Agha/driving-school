<?php

namespace App\Filament\Resources\ComplimentResource\Pages;

use App\Filament\Resources\ComplimentResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageCompliments extends ManageRecords
{
    protected static string $resource = ComplimentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
