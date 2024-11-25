<?php

namespace App\Filament\Dashboard\Resources\CollectionResource\Pages;

use App\Filament\Dashboard\Resources\CollectionResource;
use App\Filament\Dashboard\Resources\CollectionResource\Widgets\EntriesOverview;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCollection extends EditRecord
{
    /**
     * The resource the record belongs to.
     */
    protected static string $resource = CollectionResource::class;

    /**
     * Mutates the form data before creating a new record.
     *
     * Ensures that the image data is retained by setting it to the existing
     * record's image if no new image is provided in the form data.
     *
     * @param  array  $data  The form data to be mutated.
     * @return array The mutated form data.
     */
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        if (! $data['image']) {
            $data['image'] = $this->record->image;
        }

        return $data;
    }

    /**
     * Returns an array of widgets to be displayed in the header.
     *
     * @return array An array of header widgets.
     */
    protected function getHeaderWidgets(): array
    {
        return [
            EntriesOverview::class,
        ];
    }

    /**
     * Returns an array of actions to be displayed in the header.
     *
     * @return array An array of header actions.
     */
    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
