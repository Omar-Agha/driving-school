<?php

namespace App\Filament\School\Resources;

use App\Filament\School\Resources\InstructorWorkTimeResource\Pages;
use App\Filament\School\Resources\InstructorWorkTimeResource\RelationManagers;
use App\Models\InstructorWorkTime;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class InstructorWorkTimeResource extends Resource
{
    protected static ?string $model = InstructorWorkTime::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';


    static function shouldRegisterNavigation(): bool
    {
        return false;
    }

    public static function form(Form $form): Form
    {
        // InstructorWorkTimeResource::registerNavigationItems()
        return $form
            ->schema([


                Forms\Components\Select::make('day')
                    ->required()

                    ->options([
                        0 => 'Sun',
                        1 => 'Mon',
                        2 => 'Tus',
                        3 => 'Wed',
                        4 => 'Thurs',
                        5 => 'Fri',
                        6 => 'Sat',

                    ])
                    ->columnSpanFull(),
                Forms\Components\TimePicker::make('start')
                    ->required(),
                Forms\Components\TimePicker::make('end')
                    ->required(),
                Forms\Components\Toggle::make("is_break")->default(false)

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([



                Tables\Columns\TextColumn::make('day')
                    ->sortable()
                    ->getStateUsing(fn (InstructorWorkTime $record): string => match ($record->day) {
                        0 => 'Sun',
                        1 => 'Mon',
                        2 => 'Tus',
                        3 => 'Wed',
                        4 => 'Thurs',
                        5 => 'Fri',
                        6 => 'Sat',
                    }),


                Tables\Columns\TextColumn::make('start'),
                Tables\Columns\TextColumn::make('end'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }



    public static function getPages(): array
    {

        return [
            'index' => Pages\ManageInstructorWorkTimes::route('/'),
            'instructor-list' => Pages\ManageInstructorWorkTimes::route('/{instructor}'),

        ];
    }
}
