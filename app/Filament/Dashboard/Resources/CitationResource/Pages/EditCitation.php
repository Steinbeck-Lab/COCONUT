<?php

namespace App\Filament\Dashboard\Resources\CitationResource\Pages;

use App\Filament\Dashboard\Resources\CitationResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCitation extends EditRecord
{
    /**
     * The resource being edited.
     */
    protected static string $resource = CitationResource::class;

    /**
     * Returns an array of actions to be displayed in the header.
     *
     * @return array An array of header actions.
     */
    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
