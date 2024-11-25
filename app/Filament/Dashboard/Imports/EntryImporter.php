<?php

namespace App\Filament\Dashboard\Imports;

use App\Events\ImportedCSVProcessed;
use App\Models\Entry;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;
use Filament\Forms\Components\Checkbox;

class EntryImporter extends Importer
{
    protected static ?string $model = Entry::class;

    /**
     * Defines the columns that will be imported into the Entry model.
     */
    public static function getColumns(): array
    {
        return [
            ImportColumn::make('canonical_smiles'),
            ImportColumn::make('reference_id'),
            ImportColumn::make('name'),
            ImportColumn::make('doi'),
            ImportColumn::make('link'),
            ImportColumn::make('organism'),
            ImportColumn::make('organism_part'),
            ImportColumn::make('coconut_id'),
            ImportColumn::make('mol_filename'),
            ImportColumn::make('structural_comments'),
            ImportColumn::make('geo_location'),
            ImportColumn::make('location'),
        ];
    }

    /**
     * Provides options for the importer form. The importer form is
     * displayed when an import is initialized. The options provided
     * here are stored in the Import model and can be accessed in
     * the resolveRecord method.
     */
    public static function getOptionsFormComponents(): array
    {
        return [
            Checkbox::make('updateExisting')
                ->label('Update existing records'),
        ];
    }

    /**
     * Resolves or creates an Entry record based on the import options.
     *
     * If the 'updateExisting' option is enabled, the method attempts to find
     * an existing Entry using the canonical_smiles, reference_id, and
     * collection_id attributes. If no existing Entry is found, a new one is
     * initialized with these attributes.
     *
     * If the 'updateExisting' option is not enabled, a new Entry instance is
     * created with the specified collection_id.
     *
     * @return Entry|null The resolved or newly created Entry instance.
     */
    public function resolveRecord(): ?Entry
    {
        if ($this->options['updateExisting'] ?? false) {
            return Entry::firstOrNew([
                'canonical_smiles' => $this->data['canonical_smiles'],
                'reference_id' => $this->data['reference_id'],
                'collection_id' => $this->options['collection_id'],
            ]);
        }

        $entry = new Entry;
        $entry->collection_id = $this->options['collection_id'];

        return $entry;
    }

    /**
     * Gets the notification body for the completed import notification.
     *
     * Returns a string describing the outcome of the import. The string
     * includes the number of rows that were imported.
     *
     * @param  Import  $import  The Import instance.
     * @return string The notification body.
     */
    public static function getCompletedNotificationBody(Import $import): string
    {
        ImportedCSVProcessed::dispatch($import);

        $body = 'Your entry import has completed. '.number_format($import->total_rows).' '.str('row')->plural($import->total_rows).' imported.';

        return $body;
    }
}
