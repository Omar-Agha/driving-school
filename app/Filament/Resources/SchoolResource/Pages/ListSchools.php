<?php

namespace App\Filament\Resources\SchoolResource\Pages;

use App\Filament\Resources\SchoolResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Pages\ListRecords\Tab;
use Illuminate\Database\Eloquent\Builder;

class ListSchools extends ListRecords
{
    protected static string $resource = SchoolResource::class;

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
            'Subscribed' => Tab::make()
                ->modifyQueryUsing(fn(Builder $query) => $query->has("activePackage")),
            'Not Subscribed' => Tab::make()
                ->modifyQueryUsing(fn(Builder $query) => $query->doesntHave("activePackage")),

        ];
    }
}