<?php

namespace App\Filament\Dashboard\Resources\MoleculeResource\Pages;

use App\Filament\Dashboard\Resources\MoleculeResource;
use App\Models\Molecule;
use Archilex\AdvancedTables\AdvancedTables;
use Archilex\AdvancedTables\Components\PresetView;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Contracts\Pagination\CursorPaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class ListMolecules extends ListRecords
{
    use AdvancedTables;

    protected static string $resource = MoleculeResource::class;

    public $searchResults = null;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('substructureSearch')
                ->label('Substructure Search')
                ->modalHeading('Draw a Substructure')
                ->modalContent(view('components.openchemlib-editor'))
                ->form([
                    TextInput::make('structure-smiles')
                        ->label('SMILES Structure')
                        ->required()
                        ->maxLength(255),
                ])
                ->button()
                ->modalSubmitActionLabel('Search')
                ->action(function (array $data): void {
                    $smiles = $data['structure-smiles'];
                    $this->searchResults = collect(DB::select("SELECT id 
                                                            FROM mols 
                                                            WHERE m@> mol_from_smiles('{$smiles}')::mol 
                                                            ORDER BY tanimoto_sml(morganbv_fp(mol_from_smiles('{$smiles}')::mol), morganbv_fp(m::mol)) DESC
                                                            ;"))->pluck('id');
                    $this->getTableQuery();
                }),
            Actions\CreateAction::make(),
        ];
    }

    protected function getTableQuery(): Builder
    {
        if ($this->searchResults) {
            return Molecule::query()
                ->select('id', 'name', 'identifier', 'canonical_smiles', 'standard_inchi', 'status', 'active', 'synonyms')
                ->whereIntegerInRaw('id', $this->searchResults)
                ->with(['properties' => function ($query) {
                    $query->select('exact_molecular_weight', 'np_likeness');
                }]);
        } else {
            return Molecule::query();
        }
    }

    public function getPresetViews(): array
    {
        return [
            'active' => PresetView::make()
                ->modifyQueryUsing(fn ($query) => $query->where('active', true))
                ->favorite()
                ->badge(Molecule::query()->where('active', true)->count())
                ->preserveAll()
                ->default(),
            'revoked' => PresetView::make()
                ->modifyQueryUsing(fn ($query) => $query->where('active', false))
                ->favorite()
                ->badge(Molecule::query()->where('active', false)->count())
                ->preserveAll(),
        ];
    }
}
