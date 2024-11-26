<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use Filament\Resources\Pages\ViewRecord;

class ViewUser extends ViewRecord
{
    /**
     * The resource that is being viewed.
     */
    protected static string $resource = UserResource::class;
}
