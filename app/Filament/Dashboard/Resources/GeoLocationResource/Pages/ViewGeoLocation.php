<?php

namespace App\Filament\Dashboard\Resources\GeoLocationResource\Pages;

use App\Filament\Dashboard\Resources\GeoLocationResource;
use App\Filament\Dashboard\Resources\GeoLocationResource\Widgets\GeoLocationStats;
use Filament\Resources\Pages\ViewRecord;

class ViewGeoLocation extends ViewRecord
{
    /**
     * The resource the record belongs to.
     */
    protected static string $resource = GeoLocationResource::class;

    /**
     * Returns an array of widgets to be displayed in the header.
     *
     * @return array An array of header widgets.
     */
    protected function getHeaderWidgets(): array
    {
        return [
            GeoLocationStats::class,
        ];
    }
}
