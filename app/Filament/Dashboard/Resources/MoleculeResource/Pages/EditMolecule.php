<?php

namespace App\Filament\Dashboard\Resources\MoleculeResource\Pages;

use App\Filament\Dashboard\Resources\MoleculeResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditMolecule extends EditRecord
{
    /**
     * The resource being edited.
     */
    protected static string $resource = MoleculeResource::class;

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
