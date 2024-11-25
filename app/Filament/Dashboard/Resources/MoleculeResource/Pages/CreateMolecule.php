<?php

namespace App\Filament\Dashboard\Resources\MoleculeResource\Pages;

use App\Filament\Dashboard\Resources\MoleculeResource;
use Filament\Resources\Pages\CreateRecord;

class CreateMolecule extends CreateRecord
{
    /**
     * The resource the record belongs to.
     */
    protected static string $resource = MoleculeResource::class;
}
