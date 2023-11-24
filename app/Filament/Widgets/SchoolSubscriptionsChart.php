<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;

class SchoolSubscriptionsChart extends ChartWidget
{
    protected static ?string $heading = 'Schools Subscriptions';

    protected static ?int $sort = 3;

    // protected array|int|string $columnSpan = 'full';

    protected function getData(): array
    {
        
        return [
            'datasets' => [
                [
                    'label' => 'Schools',
                    'data' => [70, 20],
                ],
            ],
            'labels' => ['subscribed', 'unsubscribed'],
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}