<?php

namespace App\Filament\School\Resources;

use App\Filament\School\Resources\JoinSchoolRequestResource\Pages;
use App\Filament\School\Resources\JoinSchoolRequestResource\RelationManagers;
use App\Models\JoinSchoolRequest;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class JoinSchoolRequestResource extends Resource
{
    protected static ?string $model = JoinSchoolRequest::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';





    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::withNullableStatus()->count();
    }

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
                TextColumn::make('student.first_name'),
                TextColumn::make('student.last_name'),
                TextColumn::make('student.user.email')->label('email')


            ])
            ->filters([
                //
            ])
            ->actions([
                Action::make("accept")
                    ->action(function (JoinSchoolRequest $record, array $data) {
                        $record->accept();
                        $student = $record->student;
                        $student->preferred_instructor_id = $data['instructor_id'];
                        $student->save();
                    })

                    ->modalHeading('Accepting Student')
                    ->modalDescription('Select an Preferred Instructor')

                    ->button()
                    ->form([
                        Forms\Components\Select::make('instructor_id')
                            ->label('Instructor')
                            ->options(school()->instructors->pluck('full_name', 'id'))
                            ->required(),
                    ])
                    // ->requiresConfirmation()
                    ->visible(fn (JoinSchoolRequest $record) => $record->status == null),

                Action::make("refuse")
                    ->action(fn (JoinSchoolRequest $record) => $record->refuse())
                    ->requiresConfirmation()
                    ->button()
                    ->color('danger')
                    ->visible(fn (JoinSchoolRequest $record) => $record->status == null)

            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->modifyQueryUsing(fn (Builder $query) => $query->withNullableStatus());
    }

    public static function canCreate(): bool
    {
        return false;
    }
    public static function canDelete(Model $record): bool
    {
        return false;
    }
    public static function canDeleteAny(): bool
    {
        return false;
    }
    public static function canEdit(Model $record): bool
    {
        return false;
    }



    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageJoinSchoolRequests::route('/'),
        ];
    }
}
