<?php

namespace App\Filament\Dashboard\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Cache;

class DashboardStats extends BaseWidget
{
    /**
     * Sort order of the widget.
     */
    protected static ?int $sort = 2;

    /**
     * Get the number of columns to render the widget in.
     */
    public function getColumns(): int
    {
        return 4;
    }

    /**
     * Retrieves an array of statistics for the dashboard.
     *
     * This method returns a list of statistics, each represented as a Stat object.
     * It provides counts for collections, citations, organisms, geo locations, and reports.
     *
     * @return array An array of Stat objects representing various statistics.
     */
    protected function getStats(): array
    {
        return [
            Stat::make('Total Collections', Cache::get('stats.collections')),
            Stat::make('Total Citations', Cache::get('stats.citations')),

            Stat::make('Total Organisms', Cache::get('stats.organisms')),
            Stat::make('Total Geo Locations', Cache::get('stats.geo_locations')),
            Stat::make('Total Reports', Cache::get('stats.reports')),
        ];
    }
}
