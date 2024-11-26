<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use Filament\Resources\Pages\CreateRecord;

class CreateUser extends CreateRecord
{
    /**
     * Get the resource for the class.
     *
     * */
    protected static string $resource = UserResource::class;
}
