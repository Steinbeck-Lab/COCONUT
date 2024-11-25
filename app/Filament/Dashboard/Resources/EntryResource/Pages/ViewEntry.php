<?php

namespace App\Filament\Dashboard\Resources\EntryResource\Pages;

use App\Filament\Dashboard\Resources\EntryResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewEntry extends ViewRecord
{
    /**
     * Get the resource the record belongs to.
     */
    protected static string $resource = EntryResource::class;

    /**
     * Returns an array of actions to be displayed in the header.
     *
     * @return array An array containing the edit action.
     */
    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
