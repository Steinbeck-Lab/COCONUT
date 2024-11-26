<?php

namespace App\Filament\Resources\LicenseResource\Widgets;

use App\Models\License;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class LicenseOverview extends BaseWidget
{
    /**
     * It provides a count of licenses.
     *
     * @return array An array of Stat objects representing various statistics.
     */
    protected function getStats(): array
    {
        return [
            Stat::make('Licenses', License::count())
                ->description('Total count')
                ->color('primary'),
        ];
    }
}
