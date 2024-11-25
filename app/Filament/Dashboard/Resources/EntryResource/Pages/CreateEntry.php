<?php

namespace App\Filament\Dashboard\Resources\EntryResource\Pages;

use App\Filament\Dashboard\Resources\EntryResource;
use Filament\Resources\Pages\CreateRecord;

class CreateEntry extends CreateRecord
{
    /**
     * The resource the record belongs to.
     */
    protected static string $resource = EntryResource::class;
}
