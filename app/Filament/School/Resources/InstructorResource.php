<?php

namespace App\Filament\School\Resources;

use App\Enums\UserRoleEnum;
use App\Filament\School\Resources\InstructorResource\Pages;
use App\Filament\School\Resources\InstructorResource\Pages\EditInstructor;
use App\Filament\School\Resources\InstructorResource\RelationManagers;
use App\Models\Instructor;
use Filament\Forms;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Phpsa\FilamentPasswordReveal\Password;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class InstructorResource extends Resource
{
    protected static ?string $model = Instructor::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Grid::make(12)->schema([
                    Card::make(__("dashboard.essential_data"))->schema([
                        Hidden::make("school_id")->default(auth()->user()->school->id),
                        Forms\Components\FileUpload::make('avatar')
                            ->required()
                            ->image()
                            ->avatar(),
                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\DatePicker::make('date_of_birth')
                            ->required(),

                    ])->columnSpan(7),
                    Card::make(__("dashboard.credential_data"))
                        ->relationship('user')
                        ->schema([

                            Forms\Components\Hidden::make('role')
                                ->default(UserRoleEnum::INSTRUCTOR),

                            Forms\Components\TextInput::make('email')
                                ->required()
                                ->unique()
                                ->email()
                                ->readOnlyOn(EditInstructor::class),


                            Forms\Components\TextInput::make('username')
                                ->required()
                                ->unique()
                                ->maxLength(255)
                                ->readOnlyOn(EditInstructor::class),

                            Password::make('password')
                                ->required()
                                ->password()
                                ->hiddenOn(EditInstructor::class)
                            ,
                            Password::make('password_confirmation')
                                ->required()
                                ->password()
                                ->same('password')
                                ->hiddenOn(EditInstructor::class)

                        ])
                        ->columnSpan(5)
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
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\ImageColumn::make('avatar')
                    ->searchable(),
                Tables\Columns\TextColumn::make('date_of_birth')
                    ->date()
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
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
            'index' => Pages\ListInstructors::route('/'),
            'create' => Pages\CreateInstructor::route('/create'),
            'view' => Pages\ViewInstructor::route('/{record}'),
            'edit' => Pages\EditInstructor::route('/{record}/edit'),
        ];
    }
}
