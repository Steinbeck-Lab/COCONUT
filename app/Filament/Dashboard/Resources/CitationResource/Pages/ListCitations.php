<?php

namespace App\Filament\Dashboard\Resources\CitationResource\Pages;

use App\Filament\Dashboard\Resources\CitationResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCitations extends ListRecords
{
    /**
     * The resource that this resource collects.
     */
    protected static string $resource = CitationResource::class;

    /**
     * Actions that are shown in the header of the list page.
     */
    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
