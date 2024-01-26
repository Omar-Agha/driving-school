<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ComplimentResource\Pages;
use App\Filament\Resources\ComplimentResource\RelationManagers;
use App\Models\Compliment;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ComplimentResource extends Resource
{
    protected static ?string $model = Compliment::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('user.email')
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('user.username')
                    ->columnSpanFull(),
                // ->label(''),
                Forms\Components\TextInput::make('user.role')
                    // ->label('')
                    ->columnSpanFull(),
                Forms\Components\Textarea::make('body')
                    ->required()
                    ->maxLength(65535)
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.email')
                    ->label('User Email')
                    ->sortable(),
                TextColumn::make('user.role')
                    ->label('Role')
                    ->sortable(),
                TextColumn::make('body'),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),

            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([]),
            ]);
    }




    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageCompliments::route('/'),
        ];
    }

    public static function canCreate(): bool
    {
        return false;
    }
    public static function canDeleteAn(): bool
    {
        return false;
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Section::make("User Information")->schema([
                TextEntry::make('user.email')
                    ->columnSpanFull()
                    ->label('Email')
                    ,

                TextEntry::make('user.username')
                    ->columnSpanFull()
                    ->label('Username'),
                TextEntry::make('user.role')
                    ->label('Role')
                    ->columnSpanFull(),
                ]),
                

                TextEntry::make('body')
                ->label('content')
                    ->columnSpanFull(),
            ]);
    }
}
