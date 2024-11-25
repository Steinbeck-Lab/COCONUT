<?php

namespace App\Filament\Dashboard\Resources\GeoLocationResource\Pages;

use App\Filament\Dashboard\Resources\GeoLocationResource;
use Filament\Resources\Pages\CreateRecord;

class CreateGeoLocation extends CreateRecord
{
    /**
     * The resource the record belongs to.
     */
    protected static string $resource = GeoLocationResource::class;
}
