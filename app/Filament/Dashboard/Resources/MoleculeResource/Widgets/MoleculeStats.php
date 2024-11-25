<?php

namespace App\Filament\Dashboard\Resources\MoleculeResource\Widgets;

use App\Models\Molecule;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class MoleculeStats extends BaseWidget
{
    /**
     * Molecule to show stats for.
     */
    public ?Molecule $record = null;

    /**
     * Retrieves an array of statistics for the molecule record.
     *
     * This method returns a list of statistics, each represented as a Stat object.
     * It provides counts for molecules and organisms associated with the molecule record.
     *
     * @return array An array of Stat objects representing various statistics.
     */
    protected function getStats(): array
    {
        return [
            Stat::make('Total Organisms', Cache::remember('stats.molecules'.$this->record->id.'organisms.count', 172800, function () {
                return DB::table('molecule_organism')->selectRaw('count(*)')->whereRaw('molecule_id='.$this->record->id)->get()[0]->count;
            })),
            Stat::make('Total Geo Locations', Cache::remember('stats.molecules'.$this->record->id.'geo_locations.count', 172800, function () {
                return DB::table('geo_location_molecule')->selectRaw('count(*)')->whereRaw('molecule_id='.$this->record->id)->get()[0]->count;
            })),
        ];
    }
}
