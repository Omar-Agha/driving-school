<?php

namespace App\Filament\Pages;

use App\Livewire\CalendarWidgetLiveWire;
use Filament\Actions\Action;
use Filament\Pages\Page;

class SchedulePage extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected ?string $subheading = 'Custom Page Subheading';


    protected static string $view = 'filament.pages.schedule-page';


    //to hide the page from the menu
    public static function shouldRegisterNavigation(): bool
    {
        return true;
        // return auth()->user()->canManageSettings();
    }

    //to prevent user from access it if he is not allowed
    public function mount(): void
    {
        // abort_unless(auth()->user()->canManageSettings(), 403);
    }

    protected function getHeaderActions(): array
    {
        return [
            // Action::make('edit'),
            // Action::make('delete')
            //     ->requiresConfirmation()

        ];
    }


    protected function getHeaderWidgets(): array
    {
        return [
            CalendarWidgetLiveWire::class
            // StatsOverviewWidget::class
        ];
    }
    

    protected function getFooterWidgets(): array
    {
        return [
            // StatsOverviewWidget::class
        ];
    }


}