<?php

namespace App\Filament\Dashboard\Resources\SampleLocationResource\Pages;

use App\Filament\Dashboard\Resources\SampleLocationResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSampleLocations extends ListRecords
{
    /**
     * The resource the list records belongs to.
     */
    protected static string $resource = SampleLocationResource::class;

    /**
     * Returns an array of actions to be displayed in the header.
     *
     * @return array An array containing the create action.
     */
    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
