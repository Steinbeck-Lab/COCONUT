<?php

namespace App\Filament\Dashboard\Resources\SampleLocationResource\Pages;

use App\Filament\Dashboard\Resources\SampleLocationResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSampleLocation extends EditRecord
{
    /**
     * The resource the record belongs to.
     */
    protected static string $resource = SampleLocationResource::class;

    /**
     * Returns an array of actions to be displayed in the header.
     *
     * @return array An array containing view and delete actions.
     */
    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
