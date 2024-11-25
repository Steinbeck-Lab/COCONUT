<?php

namespace App\Filament\Dashboard\Resources\OrganismResource\Pages;

use App\Filament\Dashboard\Resources\OrganismResource;
use App\Filament\Dashboard\Resources\OrganismResource\Widgets\OrganismStats;
use Filament\Resources\Pages\ViewRecord;

class ViewOrganism extends ViewRecord
{
    /**
     * The resource the record belongs to.
     */
    protected static string $resource = OrganismResource::class;

    /**
     * Returns an array of widgets to be displayed in the header.
     *
     * @return array An array of header widgets.
     */
    protected function getHeaderWidgets(): array
    {
        return [
            OrganismStats::class,
        ];
    }
}
