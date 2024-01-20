<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Models\User;
use Doctrine\DBAL\Schema\Column;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Support\Colors\Color;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('email'),
                TextColumn::make("username"),
                IconColumn::make('is_suspended')
                    ->label('is blocked')
                    ->icon(fn (bool $state): string => match ($state) {
                        true => 'heroicon-o-no-symbol',
                        false => 'heroicon-o-user',
                    })
                    ->color(fn (bool $state) => match ($state) {
                        true => Color::Red,
                        false => Color::Green
                    })

            ])
            ->filters([
                //
            ])
            ->actions([
                Action::make("Block")
                    ->color(Color::Red)
                    ->button()
                    ->requiresConfirmation()
                    ->action(function (User $record) {
                        $record->block();
                    })
                    ->visible(fn (User $record): bool => !$record->is_suspended),
                Action::make("Unblock")
                    ->color(Color::Green)
                    ->button()
                    ->requiresConfirmation()
                    ->visible(fn (User $record): bool => $record->is_suspended)
                    ->action(function (User $record) {
                        $record->unblock();
                    })


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

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            // 'create' => Pages\CreateUser::route('/create'),
            // 'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
    public static function canCreate(): bool
    {
        return false;
    }
}
