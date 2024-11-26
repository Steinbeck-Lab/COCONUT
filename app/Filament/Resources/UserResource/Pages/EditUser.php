<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditUser extends EditRecord
{
    /**
     * The resource the record belongs to.
     */
    protected static string $resource = UserResource::class;

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
