<?php

namespace App\Filament\Dashboard\Resources\OrganismResource\RelationManagers;

use App\Models\Molecule;
use App\Models\Organism;
use App\Models\SampleLocation;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Actions\BulkAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Str;

class MoleculesRelationManager extends RelationManager
{
    protected static string $relationship = 'molecules';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('id')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('identifier')
            ->columns([
                ImageColumn::make('structure')->square()
                    ->label('Structure')
                    ->state(function ($record) {
                        return env('CM_API', 'https://api.cheminf.studio/latest/').'depict/2D?smiles='.urlencode($record->canonical_smiles).'&height=300&width=300&CIP=true&toolkit=cdk';
                    })
                    ->width(200)
                    ->height(200)
                    ->ring(5)
                    ->defaultImageUrl(url('/images/placeholder.png')),
                Tables\Columns\TextColumn::make('id')->searchable()->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('identifier')->searchable()->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('name')->searchable()
                    ->formatStateUsing(
                        fn (Molecule $molecule): HtmlString => new HtmlString("<strong>ID:</strong> {$molecule->id}<br><strong>Identifier:</strong> {$molecule->identifier}<br><strong>Name:</strong> {$molecule->name}")
                    )
                    ->description(fn (Molecule $molecule): string => $molecule->standard_inchi)
                    ->wrap(),
                Tables\Columns\TextColumn::make('synonyms')
                    ->searchable()
                    ->wrap()
                    ->lineClamp(6)
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('properties.exact_molecular_weight')
                    ->label('Mol.Wt')
                    ->numeric()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('properties.np_likeness')
                    ->label('NP Likeness')
                    ->numeric()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('status'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\AttachAction::make()->multiple(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DetachAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DissociateBulkAction::make(),
                    BulkAction::make('Change Association')
                        ->form([
                            Forms\Components\Select::make('org_id')
                                ->label('Name')
                                ->getSearchResultsUsing(function (string $search): array {
                                    return Organism::query()
                                        ->where(function (Builder $builder) use ($search) {
                                            $searchString = "%$search%";
                                            $builder->where('name', 'ilike', $searchString);
                                        })

                                        ->limit(10)
                                        ->get()
                                        ->mapWithKeys(function (Organism $organism) {
                                            return [$organism->id => $organism->name];
                                        })
                                        ->toArray();
                                })
                                ->searchable()
                                ->live(onBlur: true)
                                ->required(),
                            Forms\Components\Select::make('locations')
                                ->relationship('sampleLocations', 'name')
                                ->getSearchResultsUsing(function (string $search, $get): array {
                                    return SampleLocation::query()
                                        ->where(function (Builder $builder) use ($search, $get) {
                                            $searchString = "%$search%";
                                            $builder->where('name', 'ilike', $searchString)
                                                ->where('organism_id', '=', $get('org_id'));
                                        })
                                        ->limit(10)
                                        ->get()
                                        ->mapWithKeys(function (SampleLocation $location) {
                                            return [$location->id => $location->name];
                                        })
                                        ->toArray();
                                })
                                ->multiple()
                                ->createOptionForm([
                                    Forms\Components\TextInput::make('name')
                                        ->required(),
                                    Forms\Components\TextInput::make('iri')
                                        ->required(),
                                ])
                                ->createOptionUsing(function (array $data, $livewire): int {
                                    $slug = Str::slug($data['name']);
                                    $location_exists = SampleLocation::where('slug', $slug)->where('organism_id', $livewire->mountedTableBulkActionData['org_id'])->first();
                                    if ($location_exists) {
                                        return $location_exists->id;
                                    }

                                    return SampleLocation::create([
                                        'name' => str()->ucfirst(str()->trim($data['name'])),
                                        'slug' => $slug,
                                        'iri' => $data['iri'],
                                        'organism_id' => $livewire->mountedTableBulkActionData['org_id'],
                                    ])->getKey();
                                }),
                        ])
                        ->action(function (array $data, Collection $records, $livewire): void {
                            DB::transaction(function () use ($data, $records, $livewire) {
                                DB::beginTransaction();
                                try {
                                    $moleculeIds = $records->pluck('id')->toArray();
                                    $currentOrganism = $this->getOwnerRecord();
                                    $currentOrganism->auditDetach('molecules', $moleculeIds);

                                    foreach ($currentOrganism->sampleLocations as $location) {
                                        $location->auditDetach('molecules', $moleculeIds);
                                    }
                                    $newOrganism = Organism::findOrFail($data['org_id']);

                                    $locations = $livewire->mountedTableBulkActionData['locations'];
                                    if ($locations) {
                                        $sampleLocations = SampleLocation::findOrFail($locations);
                                        foreach ($sampleLocations as $location) {
                                            $location->auditSyncWithoutDetaching('molecules', $moleculeIds);
                                        }
                                    }
                                    $newOrganism->auditSyncWithoutDetaching('molecules', $moleculeIds);

                                    $currentOrganism->refresh();
                                    $newOrganism->refresh();

                                    $currentOrganism->molecule_count = $currentOrganism->molecules()->count();
                                    $currentOrganism->save();
                                    $newOrganism->molecule_count = $newOrganism->molecules()->count();
                                    $newOrganism->save();

                                    DB::commit();
                                } catch (\Exception $e) {
                                    // Rollback the transaction in case of any error
                                    DB::rollBack();
                                    throw $e; // Optionally rethrow the exception
                                }
                            });

                            // $this->getOwnerRecord()->molecules()->detach($records->pluck('id'));
                        }),
                ]),
            ]);
    }

    public function isReadOnly(): bool
    {
        return false;
    }
}
