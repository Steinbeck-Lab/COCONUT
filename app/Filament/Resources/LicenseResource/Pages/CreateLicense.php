<?php

namespace App\Filament\Resources\LicenseResource\Pages;

use App\Filament\Resources\LicenseResource;
use Filament\Resources\Pages\CreateRecord;

class CreateLicense extends CreateRecord
{
    /**
     * The resource the record belongs to.
     */
    protected static string $resource = LicenseResource::class;
}
