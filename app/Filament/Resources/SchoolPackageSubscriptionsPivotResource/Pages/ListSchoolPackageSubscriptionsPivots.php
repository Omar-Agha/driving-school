<?php

namespace App\Filament\Resources\SchoolPackageSubscriptionsPivotResource\Pages;

use Illuminate\Database\Eloquent\Builder;

use App\Filament\Resources\SchoolPackageSubscriptionsPivotResource;
use Filament\Actions;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;

class ListSchoolPackageSubscriptionsPivots extends ListRecords
{
    protected static string $resource = SchoolPackageSubscriptionsPivotResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    public function getTabs(): array
    {
        return [
            'all' => Tab::make(),
            'active' => Tab::make()
                ->modifyQueryUsing(fn(Builder $query) => $query->active()),
            'expired' => Tab::make()
            ->modifyQueryUsing(fn(Builder $query) => $query->inactive()),

        ];
    }
}