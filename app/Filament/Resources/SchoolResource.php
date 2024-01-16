<?php

namespace App\Filament\Resources;

use App\Enums\UserRoleEnum;
use App\Filament\Resources\SchoolResource\Pages;
use App\Filament\Resources\SchoolResource\Pages\EditSchool;
use App\Helpers\Utilities;
use App\Models\School;
use Filament\Forms;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Grid;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\CheckboxColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Phpsa\FilamentPasswordReveal\Password;
use Filament\Infolists;
use Filament\Infolists\Infolist;


class SchoolResource extends Resource
{
    protected static ?string $model = School::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $recordTitleAttribute = 'school_name';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Grid::make(12)->schema([
                    Card::make(__("dashboard.essential_data"))->schema([
                        Forms\Components\FileUpload::make('avatar')
                            ->required()
                            ->image()
                            ->avatar(),
                        Forms\Components\TextInput::make('school_name')
                            ->required()
                            ->maxLength(255),
                    Forms\Components\TextInput::make('phone_number')
                        ->required()
                        ->maxLength(255),
                    Forms\Components\TextInput::make('address')
                        ->required()
                        ->maxLength(255),

                        Forms\Components\Hidden::make('code')
                            ->default(Utilities::generateRandomCode())
                            ->hiddenOn(EditSchool::class),

                    ])->columnSpan(7),
                    Card::make(__("dashboard.credential_data"))
                        ->relationship('user')
                        ->schema([

                            Forms\Components\Hidden::make('role')
                                ->default(UserRoleEnum::SCHOOL),
                            Forms\Components\TextInput::make('email')
                                ->required()
                                ->unique()
                                ->email()
                                ->readOnlyOn(EditSchool::class),


                            Forms\Components\TextInput::make('username')
                                ->required()
                                ->unique()
                                ->maxLength(255)
                                ->readOnlyOn(EditSchool::class),

                            Password::make('password')
                                ->required()
                                ->password()
                                ->hiddenOn(EditSchool::class),
                            Password::make('password_confirmation')
                                ->required()
                                ->password()
                                ->same('password')
                                ->hiddenOn(EditSchool::class)

                        ])
                        ->columnSpan(5)
                        ->hiddenOn(EditSchool::class)
                ])

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\ImageColumn::make('avatar'),
                Tables\Columns\TextColumn::make('school_name')
                    ->searchable(isIndividual: true),
                TextColumn::make('active_package_exists')->exists('activePackage')
                    ->badge()
                    ->color(fn (School $record): string => match ($record->activePackage()->exists()) {
                        true => "success",
                        false => "danger",
                    })
                    ->label("Subscription")
                    ->state(fn (School $record): string => match ($record->activePackage()->exists()) {
                        true => "Subscriped",
                        false => "Not Subscriped",
                    })
                    ->tooltip(fn (School $record): string => $record->activePackage()->exists() ? $record->activePackage->first()->title : "")

            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\ViewAction::make(),

                // Tables\Actions\DeleteAction::make(),
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
            'index' => Pages\ListSchools::route('/'),
            'create' => Pages\CreateSchool::route('/create'),
            'edit' => Pages\EditSchool::route('/{record}/edit'),
            'view' => Pages\ViewSchool::route('/{record}'),

        ];
    }
}
