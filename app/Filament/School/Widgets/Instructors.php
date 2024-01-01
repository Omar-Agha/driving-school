<?php

namespace App\Filament\School\Widgets;

use Filament\Tables;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Support\Facades\Auth;

class Instructors extends BaseWidget
{
    protected static ?int $sort = 2;
    public function table(Table $table): Table
    {
        $school = Auth::user()->school;

        return $table
            ->query(
                $school->instructors()->getQuery()

            )
            ->columns([
                ImageColumn::make('avatar'),
                TextColumn::make("name")
            ]);
    }
}
