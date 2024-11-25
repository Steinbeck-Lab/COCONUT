<?php

namespace App\Filament\Dashboard\Resources\CollectionResource\Widgets;

use App\Models\Collection;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class EntriesOverview extends BaseWidget
{
    /**
     * The collection record
     */
    public ?Collection $record = null;

    /**
     * The widget's polling interval.
     */
    protected static ?string $pollingInterval = '10s';

    /**
     * Retrieves an array of statistics for the collection record.
     *
     * This method returns a list of statistics, each represented as a Stat object.
     * It provides counts for entries, passed entries, and failed entries associated
     * with the collection record.
     *
     * @return array An array of Stat objects representing various statistics.
     */
    protected function getStats(): array
    {
        return [
            Stat::make('Entries', $this->record->total_entries)
                ->description('Total count')
                ->color('primary'),
            Stat::make('Passed Entries', $this->record->successful_entries)
                ->description('Successful count')
                ->color('success'),
            Stat::make('Entries', $this->record->failed_entries)
                ->description('Failed entries')
                ->color('danger'),
        ];
    }
}
