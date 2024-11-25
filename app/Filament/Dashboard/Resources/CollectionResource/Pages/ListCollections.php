<?php

namespace App\Filament\Dashboard\Resources\CollectionResource\Pages;

use App\Filament\Dashboard\Resources\CollectionResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCollections extends ListRecords
{
    /**
     * Get the resource for the class.
     *
     * @return string
     */
    protected static string $resource = CollectionResource::class;

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
