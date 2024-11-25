<?php

namespace App\Filament\Dashboard\Resources\EntryResource\Pages;

use App\Filament\Dashboard\Resources\EntryResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListEntries extends ListRecords
{
    /**
     * The resource class.
     */
    protected static string $resource = EntryResource::class;

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
