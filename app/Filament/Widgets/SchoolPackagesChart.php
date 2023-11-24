<?php

namespace App\Filament\Widgets;

use Filament\Support\RawJs;
use Filament\Widgets\ChartWidget;


class SchoolPackagesChart extends ChartWidget
{
    protected static ?string $heading = 'Packages';
    protected static ?string $description = 'Total Amount of subscribed Packages';
    protected static ?int $sort = 4;

    public ?string $filter = 'today';



    protected function getData(): array
    {
        return [
            'datasets' => [
                [
                    'label' => 'Schools',
                    'data' => [20, 50,89,100],
                    'backgroundColor' => ['rgb(54, 162, 235)', 'rgb(255, 99, 132)','rgb(202, 161, 40)', 'rgb(12, 81, 197)']

                ],
            ],
            'labels' => ['Silver Package', 'Gold Package',"Diamond Package","Platinum Package"]
        ];
    }

    protected function getType(): string
    {
        return 'pie';
    }


    // protected function getOptions(): RawJs
    // {
    //     return RawJs::make(<<<JS
    //     {
    //         scales: {
    //             y: {
    //                 ticks: {
    //                     callback: (value) => '€' + value,
    //                 },
    //             },
    //         },
    //         tooltips: {
    //   enabled: false,
    // }
    //     }
    // JS);
    // }
}