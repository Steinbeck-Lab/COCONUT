<?php

namespace App\Filament\Resources\LicenseResource\Pages;

use App\Filament\Resources\LicenseResource;
use App\Filament\Resources\LicenseResource\Widgets\LicenseOverview;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListLicenses extends ListRecords
{
    /**
     * The resource the list records belongs to.
     */
    protected static string $resource = LicenseResource::class;

    /**
     * Returns an array of widgets to be displayed in the header.
     *
     * @return array An array containing the LicenseOverview widget class.
     */
    protected function getHeaderWidgets(): array
    {
        return [
            LicenseOverview::class,
        ];
    }

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
