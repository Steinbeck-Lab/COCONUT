<?php

namespace App\Filament\Dashboard\Resources\OrganismResource\Pages;

use App\Filament\Dashboard\Resources\OrganismResource;
use App\Models\Organism;
use Archilex\AdvancedTables\AdvancedTables;
use Archilex\AdvancedTables\Components\PresetView;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListOrganisms extends ListRecords
{
    use AdvancedTables;

    /**
     * Get the resource for the class.
     */
    protected static string $resource = OrganismResource::class;

    /**
     * Returns an array of actions to be displayed in the header.
     *
     * @return array An array containing the create action.
     */
    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    /**
     * Returns an array of PresetView objects for the list page.
     *
     * The first preset view is set to default and will be used if no preset view is selected.
     *
     * @return array An array containing the preset views.
     */
    public function getPresetViews(): array
    {
        return [
            'organisms' => PresetView::make()
                ->modifyQueryUsing(fn ($query) => $query->where('molecule_count', '>', 0))
                ->favorite()
                ->badge(Organism::query()->where('molecule_count', '>', 0)->count())
                ->preserveAll()
                ->default(),
            'inactive entries' => PresetView::make()
                ->modifyQueryUsing(fn ($query) => $query->where('molecule_count', '<=', 0))
                ->favorite()
                ->badge(Organism::query()->where('molecule_count', '<=', 0)->count())
                ->preserveAll(),
        ];
    }
}
