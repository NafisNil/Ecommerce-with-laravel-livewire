<?php

namespace App\Livewire;

use Filament\Widgets\ChartWidget;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;
use App\Models\Order;
class RevenueChart extends ChartWidget
{
    protected ?string $heading = 'Revenue Chart';
    public ?string $filter = 'week';

    protected function getData(): array
    {
        $activeFilter = $this->filter ?? 'week';
        $data = Trend::model(Order::class)
            ->between(
                start: now()->subDays($activeFilter === 'week' ? 7 : 30),
                end: now(),
            )
            ->perWeek()
            ->sum('total');
     
        return [
            //
            'dataset' => [
                'label' => 'Level of Revenue',
                'data' => $data->map(fn (TrendValue $point) => $point->aggregate),
            ],
            'labels' => $data->map(fn (TrendValue $point) => $point->date),
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }

    protected function getFilters(): ?array
    {
        return [
            'week' => 'Last 7 Days',
            'month' => 'Last 30 Days',
        ];
    }
}
