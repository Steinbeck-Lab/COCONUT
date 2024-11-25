<?php

namespace App\Filament\Dashboard\Resources\CitationResource\Pages;

use App\Filament\Dashboard\Resources\CitationResource;
use Filament\Resources\Pages\CreateRecord;

class CreateCitation extends CreateRecord
{
    /**
     * Get the page title.
     *
     * @return string
     */
    protected static string $resource = CitationResource::class;
}
