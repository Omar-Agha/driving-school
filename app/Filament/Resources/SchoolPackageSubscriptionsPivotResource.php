<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SchoolPackageSubscriptionsPivotResource\Pages;
use App\Filament\Resources\SchoolPackageSubscriptionsPivotResource\RelationManagers;
use App\Filament\Resources\SchoolResource\Pages\ViewSchool;
use App\Models\SchoolPackageSubscriptionsPivot;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SchoolPackageSubscriptionsPivotResource extends Resource
{
    protected static ?string $model = SchoolPackageSubscriptionsPivot::class;
    public static ?string $label = 'subscriptions';

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                // Forms\Components\TextInput::make('package_id')
                //     ->required()
                //     ->numeric(),
                // Forms\Components\TextInput::make('school_id')
                //     ->required()
                //     ->numeric(),
                // Forms\Components\TextInput::make('cost')
                //     ->required()
                //     ->numeric()
                //     ->prefix('$'),
                // Forms\Components\TextInput::make('duration')
                //     ->required()
                //     ->numeric(),
                // Forms\Components\DateTimePicker::make('starts_at')
                //     ->required(),
                // Forms\Components\DateTimePicker::make('expires_at'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('starts_at')
                    ->since()
                    ->sortable()
                    ->tooltip(fn(SchoolPackageSubscriptionsPivot $record) => $record->starts_at->toDateString()),

                Tables\Columns\TextColumn::make('expires_at')
                    ->since()
                    ->sortable()

                    ->tooltip(fn(SchoolPackageSubscriptionsPivot $record) => $record->expires_at->toDateString()),

                Tables\Columns\TextColumn::make('school.school_name')
                    ->numeric()
                    ->sortable()
                    ->url(fn(SchoolPackageSubscriptionsPivot $record) => ViewSchool::getUrl(['record' => $record->school_id])),

                Tables\Columns\TextColumn::make('package.title')
                    ->numeric()
                    ->sortable()
                    ->url(fn(SchoolPackageSubscriptionsPivot $record) => ViewSchool::getUrl(['record' => $record->package_id])),


                Tables\Columns\TextColumn::make('cost')
                    ->money()
                    ->sortable(),

                Tables\Columns\TextColumn::make('status')
                    ->badge()

                    ->color(fn(string $state): string => match ($state) {
                        'active' => 'success',
                        'expired' => 'danger',
                    })


            ])
            ->filters([
                //
            ])
            ->actions([
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function canCreate(): bool
    {
        return false;
    }



    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSchoolPackageSubscriptionsPivots::route('/'),
            'create' => Pages\CreateSchoolPackageSubscriptionsPivot::route('/create'),
            // 'edit' => Pages\EditSchoolPackageSubscriptionsPivot::route('/{record}/edit'),
        ];
    }
}