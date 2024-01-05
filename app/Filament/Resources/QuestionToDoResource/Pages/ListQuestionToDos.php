<?php

namespace App\Filament\Resources\QuestionToDoResource\Pages;

use App\Filament\Resources\QuestionToDoResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListQuestionToDos extends ListRecords
{
    protected static string $resource = QuestionToDoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
