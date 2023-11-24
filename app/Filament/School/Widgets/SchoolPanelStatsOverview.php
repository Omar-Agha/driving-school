<?php

namespace App\Filament\School\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class SchoolPanelStatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        $school = auth()->user()->school;
        $school->loadCount('instructors','vehicles');
        return [
            Stat::make('Instructors', $school->instructors_count)
                ->description("some addition description")
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('info')
                ->chart([7, 2, 10, 3, 15, 4, 17]),


            Stat::make('Students', '22')
                ->description('36 new student this month')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('primary')
                ->chart([7, 2, 10, 3, 15, 4, 17, 33]),

            Stat::make('vehicles', $school->vehicles_count)
                ->description('2 Active vehicles')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('primary')
                ->chart([7, 2, 10, 3, 15, 4, 17, 33]),

                

            Stat::make('Month Cash', '100$')
                ->description("Todays Cash 33$")
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('success')
                ->chart([7, 2, 10, 3, 15, 4, 17])
                



        ];
    }
}
