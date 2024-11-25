<?php

namespace App\Filament\Dashboard\Resources\EntryResource\Pages;

use App\Filament\Dashboard\Resources\EntryResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditEntry extends EditRecord
{
    /**
     * Get the resource that this page belongs to.
     *
     * @return string
     */
    protected static string $resource = EntryResource::class;

    /**
     * Returns an array of actions to be displayed in the header.
     *
     * @return array An array containing view and delete actions.
     */
    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
