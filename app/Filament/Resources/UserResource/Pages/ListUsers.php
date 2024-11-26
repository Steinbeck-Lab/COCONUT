<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListUsers extends ListRecords
{
    /**
     * Get the resource for the class.
     */
    protected static string $resource = UserResource::class;

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
