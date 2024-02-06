<?php

namespace App\Filament\School\Resources;

use App\Filament\School\Resources\StudentResource\Pages;
use App\Filament\School\Resources\StudentResource\RelationManagers;
use App\Models\Student;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class StudentResource extends Resource
{
    protected static ?string $model = Student::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('avatar'),

                Tables\Columns\TextColumn::make('user.email')
                    ->sortable()
                    ->label('email'),
                Tables\Columns\TextColumn::make('preferredInstructor.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('first_name')
                    ->searchable()
                    ->label('full name'),



                Tables\Columns\TextColumn::make('phone_number')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->searchable(),
                Tables\Columns\TextColumn::make('date_of_birth')
                    ->date()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->sortable(),
                Tables\Columns\TextColumn::make('street_name')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->searchable(),
                Tables\Columns\TextColumn::make('house_name')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->searchable(),
                Tables\Columns\TextColumn::make('post_code')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->searchable(),
                Tables\Columns\TextColumn::make('city')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->searchable(),
                Tables\Columns\TextColumn::make('country')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->searchable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Action::make("Assign instructor")
                    ->button()
                    ->action(function (Student $record, array $data) {
                        $record->preferred_instructor_id = $data['instructor_id'];
                        $record->save();
                    })
                    ->modalHeading('Change Preferred Instructor')
                    ->modalDescription('Select an Preferred Instructor')

                    ->form([
                        Forms\Components\Select::make('instructor_id')
                            ->label('Instructor')
                            ->options(school()->instructors->pluck('full_name', 'id'))
                            ->default(fn (Student $record) => $record->preferred_instructor_id)
                            ->required(),
                    ])

            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([]),
            ])
            ->modifyQueryUsing(fn (Builder $query) => $query->where('school_id', school()->id));
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageStudents::route('/'),
        ];
    }
}
