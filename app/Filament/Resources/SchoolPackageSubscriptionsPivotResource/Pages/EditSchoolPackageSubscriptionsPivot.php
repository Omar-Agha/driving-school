<?php

namespace App\Filament\Resources\SchoolPackageSubscriptionsPivotResource\Pages;

use App\Filament\Resources\SchoolPackageSubscriptionsPivotResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSchoolPackageSubscriptionsPivot extends EditRecord
{
    protected static string $resource = SchoolPackageSubscriptionsPivotResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
