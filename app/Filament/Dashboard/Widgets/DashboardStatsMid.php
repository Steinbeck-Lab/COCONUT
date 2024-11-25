<?php

namespace App\Filament\Dashboard\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Cache;

class DashboardStatsMid extends BaseWidget
{
    /**
     * Sort order of the widget.
     */
    protected static ?int $sort = 1;

    /**
     * Widget column span.
     */
    protected int|string|array $columnSpan = 'full';

    /**
     * Retrieves an array of statistics for the application.
     *
     * This method returns a list of statistics, each represented as a Stat object.
     * It provides counts for molecules, non-stereo molecules, and stereo molecules.
     *
     * @return array An array of Stat objects representing various statistics.
     */
    protected function getStats(): array
    {
        return [
            Stat::make('Total Molecules', Cache::get('stats.molecules')),
            Stat::make('Total Non-Stereo Molecules', Cache::get('stats.molecules.non_stereo')),
            Stat::make('Total Stereo Molecules', Cache::get('stats.molecules.stereo'))
                ->description(
                    'Total parent molecules: '.Cache::get('stats.molecules.parent')
                )
                ->color('primary'),
        ];
    }
}
