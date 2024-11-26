<?php

namespace App\Filament\Widgets;

use App\Models\Citation;
use App\Models\Collection;
use App\Models\Molecule;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class ApplicationOverview extends BaseWidget
{
    /**
     * It provides stats for the overall application.
     *
     * @return array An array of Stat objects representing various statistics.
     */
    protected function getStats(): array
    {
        return [
            Stat::make('Collections', Collection::count())
                ->description('Total count')
                ->color('primary'),
            Stat::make('Molecules', Molecule::count())
                ->description('Total count')
                ->color('primary'),
            Stat::make('Citations', Citation::count())
                ->description('Total count')
                ->color('primary'),
        ];
    }
}
