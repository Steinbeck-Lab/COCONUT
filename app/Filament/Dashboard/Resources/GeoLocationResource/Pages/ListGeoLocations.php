<?php

namespace App\Filament\Dashboard\Resources\GeoLocationResource\Pages;

use App\Filament\Dashboard\Resources\GeoLocationResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListGeoLocations extends ListRecords
{
    /**
     * The resource the list records belongs to.
     */
    protected static string $resource = GeoLocationResource::class;

    /**
     * Returns an array of actions to be displayed in the header.
     *
     * @return array An array of header actions.
     */
    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
