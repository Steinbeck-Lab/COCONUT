<?php

namespace App\Filament\Dashboard\Resources\CollectionResource\Pages;

use App\Filament\Dashboard\Resources\CollectionResource;
use App\Filament\Dashboard\Resources\CollectionResource\Widgets\CollectionStats;
use Filament\Resources\Pages\ViewRecord;

class ViewCollection extends ViewRecord
{
    /**
     * Returns an array of widgets to be displayed in the header.
     *
     * @return array An array of header widgets.
     */
    protected static string $resource = CollectionResource::class;

    /**
     * Returns an array of widgets to be displayed in the header.
     *
     * @return array An array of header widgets.
     */
    protected function getHeaderWidgets(): array
    {
        return [
            CollectionStats::class,
        ];
    }
}
