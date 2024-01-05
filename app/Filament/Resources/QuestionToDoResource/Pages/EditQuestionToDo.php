<?php

namespace App\Filament\Resources\QuestionToDoResource\Pages;

use App\Filament\Resources\QuestionToDoResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditQuestionToDo extends EditRecord
{
    protected static string $resource = QuestionToDoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
