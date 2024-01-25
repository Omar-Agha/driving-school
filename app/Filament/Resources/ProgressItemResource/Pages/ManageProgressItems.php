<?php

namespace App\Filament\Resources\ProgressItemResource\Pages;

use App\Filament\Resources\ProgressItemResource;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ManageRecords;
use Filament\Resources\Components\Tab;
use Illuminate\Database\Eloquent\Builder;
use Symfony\Component\Console\Input\Input;

class ManageProgressItems extends ManageRecords
{
    protected static string $resource = ProgressItemResource::class;

    public ?string $activeTab = '';
    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->mutateFormDataUsing(function (array $data): array {

                    $data['type'] = $this->activeTab;



                    return $data;
                }),
        ];
    }

    public function getTabs(): array
    {

        return [
            0 => Tab::make("Module 1")
                ->modifyQueryUsing(fn (Builder $query) => $query->whereType(0)),

            1 => Tab::make('Module 2')
                ->modifyQueryUsing(fn (Builder $query) => $query->whereType(1)),

            2 => Tab::make('Module 3')
                ->modifyQueryUsing(fn (Builder $query) => $query->whereType(2)),

            3 => Tab::make('Module 4')
                ->modifyQueryUsing(fn (Builder $query) => $query->whereType(3)),

        ];
    }
}
