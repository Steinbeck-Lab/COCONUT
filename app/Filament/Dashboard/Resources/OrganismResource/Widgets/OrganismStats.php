<?php

namespace App\Filament\Dashboard\Resources\OrganismResource\Widgets;

use App\Models\Organism;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class OrganismStats extends BaseWidget
{
    /**
     * Organism that the stats will be based on.
     */
    public ?Organism $record = null;

    /**
     * Retrieves an array of statistics for the organism record.
     *
     * This method returns a list of statistics, each represented as a Stat object.
     * It provides counts for molecules and geo locations associated with the organism record.
     *
     * @return array An array of Stat objects representing various statistics.
     */
    protected function getStats(): array
    {
        return [
            Stat::make('Total Molecules', Cache::remember('stats.organisms'.$this->record->id.'molecules.count', 172800, function () {
                return DB::table('molecule_organism')->selectRaw('count(*)')->whereRaw('organism_id='.$this->record->id)->get()[0]->count;
            })),
            Stat::make('Total Geo Locations', Cache::remember('stats.organisms'.$this->record->id.'geo_locations.count', 172800, function () {
                return DB::table('molecule_organism')->selectRaw('count(*)')->whereRaw('organism_id='.$this->record->id)->Join('geo_location_molecule', 'molecule_organism.molecule_id', '=', 'geo_location_molecule.molecule_id')->get()[0]->count;
            })),
        ];
    }
}
