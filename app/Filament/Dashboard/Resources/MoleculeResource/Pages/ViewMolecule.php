<?php

namespace App\Filament\Dashboard\Resources\MoleculeResource\Pages;

use App\Filament\Dashboard\Resources\MoleculeResource;
use App\Filament\Dashboard\Resources\MoleculeResource\Widgets\MoleculeStats;
use Filament\Resources\Pages\ViewRecord;

class ViewMolecule extends ViewRecord
{
    /**
     * The resource that this record belongs to.
     */
    protected static string $resource = MoleculeResource::class;

    /**
     * Returns an array of widgets to be displayed in the header.
     *
     * @return array An array of header widgets.
     */
    protected function getHeaderWidgets(): array
    {
        return [
            MoleculeStats::class,
        ];
    }
}
