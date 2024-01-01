<?php

namespace App\Filament\School\Widgets;

use App\Models\School;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Support\Facades\Auth;

class Students extends BaseWidget
{
    protected static ?int $sort = 3;
    public function table(Table $table): Table
    {
        $school = Auth::user()->school;
        return $table
            ->query(
                $school->students()->getQuery()
            )
            ->columns([
                TextColumn::make('first_name'),
                TextColumn::make('last_name'),
                TextColumn::make('user.email')


            ]);
    }
}
