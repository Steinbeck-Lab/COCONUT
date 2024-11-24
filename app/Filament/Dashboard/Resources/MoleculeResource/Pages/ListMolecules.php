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
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class ListMolecules extends ListRecords
{
    use AdvancedTables;

    protected static string $resource = MoleculeResource::class;

    public $searchResults = null;
    public $substructureSmiles = null;

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
                    $this->substructureSmiles = $data['structure-smiles'];
                    // $smiles = $data['structure-smiles'];
                    // $this->searchResults = collect(DB::select("SELECT id 
                    //                                         FROM mols 
                    //                                         WHERE m@> mol_from_smiles('{$smiles}')::mol 
                    //                                         ORDER BY tanimoto_sml(morganbv_fp(mol_from_smiles('{$smiles}')::mol), morganbv_fp(m::mol)) DESC
                    //                                         ;"))->pluck('id')->toArray();
                    $this->getTableQuery();
                }),
            Actions\CreateAction::make(),
        ];
    }

    protected function getTableQuery(): Builder
    {
        if ($this->substructureSmiles) {
            $query = Molecule::query()
            ->select('id', 'name', 'identifier', 'canonical_smiles', 'standard_inchi', 'status', 'active', 'synonyms');

        if ($this->substructureSmiles) {
            $query->whereIn('id', function($query) {
                $query->select('id')
                      ->from('mols')
                      ->whereRaw('m @> mol_from_smiles(?)::mol', [$this->substructureSmiles])
                      ->orderByRaw('tanimoto_sml(morganbv_fp(mol_from_smiles(?)::mol), morganbv_fp(m::mol)) DESC', 
                          [$this->substructureSmiles]);
            });
        }

        return $query->with(['properties' => function ($query) {
            $query->select('molecule_id', 'exact_molecular_weight', 'np_likeness');
        }]);
        }
        // if ($this->searchResults) {
        //     return Molecule::query()
        //         ->select('id', 'name', 'identifier', 'canonical_smiles', 'standard_inchi', 'status', 'active', 'synonyms')
        //         ->whereIntegerInRaw('id', $this->searchResults)
        //         ->with(['properties' => function ($query) {
        //             $query->select('exact_molecular_weight', 'np_likeness');
        //         }]);
        // } 
        return Molecule::query();

    }

    protected function paginateTableQuery(Builder $query): Paginator
    {
        return parent::paginateTableQuery($query);
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
