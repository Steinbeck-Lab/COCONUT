<?php

namespace App\Filament\Dashboard\Resources\GeoLocationResource\Pages;

use App\Filament\Dashboard\Resources\GeoLocationResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditGeoLocation extends EditRecord
{
    /**
     * The resource the record belongs to.
     */
    protected static string $resource = GeoLocationResource::class;

    /**
     * Returns an array of actions to be displayed in the header.
     *
     * @return array An array containing the delete action.
     */
    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
