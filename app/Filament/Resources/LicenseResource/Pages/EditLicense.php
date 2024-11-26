<?php

namespace App\Filament\Resources\LicenseResource\Pages;

use App\Filament\Resources\LicenseResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditLicense extends EditRecord
{
    /**
     * The resource the record belongs to.
     */
    protected static string $resource = LicenseResource::class;

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
