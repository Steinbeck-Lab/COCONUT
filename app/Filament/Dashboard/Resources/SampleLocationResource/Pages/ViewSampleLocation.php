<?php

namespace App\Filament\Dashboard\Resources\SampleLocationResource\Pages;

use App\Filament\Dashboard\Resources\SampleLocationResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewSampleLocation extends ViewRecord
{
    /**
     * The resource the record belongs to.
     */
    protected static string $resource = SampleLocationResource::class;

    /**
     * Returns an array of actions to be displayed in the header.
     *
     * @return array An array containing the edit action.
     */
    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
