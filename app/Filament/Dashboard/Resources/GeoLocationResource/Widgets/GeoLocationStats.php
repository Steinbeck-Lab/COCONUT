<?php

namespace App\Filament\Dashboard\Resources\GeoLocationResource\Widgets;

use App\Models\GeoLocation;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class GeoLocationStats extends BaseWidget
{
    /**
     * The record to use for the widget.
     *
     * @var string
     */
    public ?GeoLocation $record = null;

    /**
     * Retrieves an array of statistics for the geo location record.
     *
     * This method returns a list of statistics, each represented as a Stat object.
     * It provides counts for molecules and organisms associated with the geo location record.
     *
     * @return array An array of Stat objects representing various statistics.
     */
    protected function getStats(): array
    {
        return [
            Stat::make('Total Molecules', Cache::rememberForever('stats.geo_locations'.$this->record->id.'molecules.count', function () {
                return DB::table('geo_location_molecule')->selectRaw('count(*)')->whereRaw('geo_location_id='.$this->record->id)->get()[0]->count;
            })),
            Stat::make('Total Organisms', Cache::rememberForever('stats.geo_locations'.$this->record->id.'organisms.count', function () {
                return DB::table('geo_location_molecule')->selectRaw('count(*)')->whereRaw('geo_location_id='.$this->record->id)->Join('molecule_organism', 'geo_location_molecule.molecule_id', '=', 'molecule_organism.molecule_id')->get()[0]->count;
            })),
        ];
    }
}
