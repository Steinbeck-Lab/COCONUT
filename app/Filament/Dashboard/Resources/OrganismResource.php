<?php

namespace App\Filament\Dashboard\Resources;

use App\Filament\Dashboard\Resources\OrganismResource\Pages;
use App\Filament\Dashboard\Resources\OrganismResource\RelationManagers\MoleculesRelationManager;
use App\Filament\Dashboard\Resources\OrganismResource\RelationManagers\SampleLocationsRelationManager;
use App\Filament\Dashboard\Resources\OrganismResource\Widgets\OrganismStats;
use App\Forms\Components\OrganismsTable;
use App\Models\Organism;
use Archilex\AdvancedTables\Filters\AdvancedFilter;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Section;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use GuzzleHttp\Client;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Cache;
use Log;
use Tapp\FilamentAuditing\RelationManagers\AuditsRelationManager;

class OrganismResource extends Resource
{
    /**
     * The nativation group to which the resource belongs.
     */
    protected static ?string $navigationGroup = 'Data';

    /**
     * The sort order of the resource within the navigation group.
     */
    protected static ?int $navigationSort = 4;

    /**
     * The model the resource corresponds to.
     */
    protected static ?string $model = Organism::class;

    /**
     * Navigation icon for the resource.
     */
    protected static ?string $navigationIcon = 'heroicon-o-bug-ant';

    /**
     * Configures the form schema for the Organism resource.
     *
     * This method sets up the form structure by defining a grid layout with two groups.
     * Each group contains sections with specific schemas. The first group includes a section
     * using the Organism's form schema, while the second group contains a custom table and
     * conditionally hidden content based on the operation.
     *
     * @param  Form  $form  The form instance to be configured.
     * @return Form The configured form instance with the defined schema.
     */
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Grid::make()
                    ->schema([
                        Group::make()
                            ->schema([
                                Section::make('')
                                    ->schema(Organism::getForm()),
                            ])
                            ->columnSpan(1),
                        Group::make()
                            ->schema([
                                Section::make('')
                                    ->schema([
                                        OrganismsTable::make('Custom Table'),
                                        // \Livewire\Livewire::mount('similar-organisms', ['organismId' => function ($get) {
                                        //     return $get('name');
                                        // }]),
                                    ]),
                            ])
                            ->hidden(function ($operation) {
                                return $operation === 'create';
                            })
                            ->columnSpan(1),
                    ])
                    ->columns(2),  // Defines the number of columns in the grid
            ]);
    }

    /**
     * Defines the table schema used by the Organism resource.
     *
     * The table schema includes columns for the organism's name, rank, created and updated at timestamps,
     * as well as a filter and actions for viewing, editing, and deleting records. The table also includes
     * a bulk action group with a delete bulk action.
     *
     * @param  \Filament\Tables\Table  $table  The table instance to be configured.
     * @return \Filament\Tables\Table The configured table instance with the defined schema.
     */
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('rank')->wrap()
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                AdvancedFilter::make()
                    ->includeColumns(),
            ])
            ->actions([
                Tables\Actions\Action::make('iri')
                    ->label('IRI')
                    ->url(fn (Organism $record) => $record->iri ? urldecode($record->iri) : null, true)
                    ->color('info')
                    ->icon('heroicon-o-link'),
                // Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    /**
     * Gets the relation managers for the Organism resource.
     *
     * @return array An array of relation manager class names associated with the Organism resource.
     */
    public static function getRelations(): array
    {
        $arr = [
            MoleculesRelationManager::class,
            SampleLocationsRelationManager::class,
            AuditsRelationManager::class,
        ];

        return $arr;
    }

    /**
     * Defines the pages used by the Organism resource.
     *
     * The pages include list, create, and edit pages that are used to manage the Organism records.
     * The list page displays a table of all Organism records, the create page allows users to create
     * new Organism records, and the edit page allows users to edit existing Organism records.
     *
     * @return array An associative array of page class names and their corresponding routes.
     */
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListOrganisms::route('/'),
            'create' => Pages\CreateOrganism::route('/create'),
            'edit' => Pages\EditOrganism::route('/{record}/edit'),
            // 'view' => Pages\ViewOrganism::route('/{record}'),
        ];
    }

    /**
     * Gets the widgets to be displayed on the dashboard for the Organism resource.
     *
     * The widgets returned by this function are displayed on the dashboard and can be used to quickly
     * view key metrics about the Organism records without having to leave the dashboard.
     *
     * @return array An array of widget class names associated with the Organism resource.
     */
    public static function getWidgets(): array
    {
        return [
            OrganismStats::class,
        ];
    }

    /**
     * Returns the number of organisms in the database as a string.
     *
     * The value is cached for performance reasons.
     *
     * @return string|null The number of organisms in the database, or null if the cache key is not set.
     */
    public static function getNavigationBadge(): ?string
    {
        return Cache::get('stats.organisms');
    }

    /**
     * Call the Global Names Finder API to map an organism name to an Open Tree of Life identifier.
     *
     * @param  string  $name  The organism name to map.
     * @param  \App\Models\Organism  $organism  The organism model to update.
     * @return array An array containing the organism name, the OGG IRI, the organism model, and the rank of the match.
     */
    protected static function getGNFMatches($name, $organism)
    {
        $data = [
            'text' => $name,
            'bytesOffset' => false,
            'returnContent' => false,
            'uniqueNames' => true,
            'ambiguousNames' => true,
            'noBayes' => false,
            'oddsDetails' => false,
            'language' => 'eng',
            'wordsAround' => 2,
            'verification' => true,
            'allMatches' => true,
        ];

        $client = new Client;
        $url = 'https://finder.globalnames.org/api/v1/find';

        $response = $client->post($url, [
            'json' => $data,
        ]);

        $responseBody = json_decode($response->getBody(), true);

        return $responseBody;

        // if (isset($responseBody['names']) && count($responseBody['names']) > 0) {
        //     $r_name = $responseBody['names'][0];
        //     $matchType = $r_name['verification']['matchType'];
        //     if ($matchType == 'Exact' || $matchType == 'Fuzzy') {
        //         $iri = $r_name['verification']['bestResult']['outlink'] ?? $r_name['verification']['bestResult']['dataSourceTitleShort'];
        //         $ranks = $r_name['verification']['bestResult']['classificationRanks'] ?? null;
        //         $ranks = rtrim($ranks, '|');
        //         $ranks = explode('|', $ranks);
        //         $rank = end($ranks);
        //         if ($matchType == 'Fuzzy') {
        //             $rank = $rank . ' (fuzzy)';
        //         }
        //         return [$name, $iri, $organism, $rank];
        //     } elseif ($matchType == 'PartialFuzzy' || $matchType == 'PartialExact') {
        //         $iri = $r_name['verification']['bestResult']['dataSourceTitleShort'];
        //         if (isset($r_name['verification']['bestResult']['classificationRanks'])) {
        //             $ranks = rtrim($r_name['verification']['bestResult']['classificationRanks'], '|') ?? null;
        //             $paths = rtrim($r_name['verification']['bestResult']['classificationPath'], '|') ?? null;
        //             $ids = rtrim($r_name['verification']['bestResult']['classificationIds'], '|') ?? null;
        //             $ranks = explode('|', $ranks);
        //             $ranksLength = count($ranks);
        //             if ($ranksLength > 0) {
        //                 $parentRank = $ranks[$ranksLength - 2];
        //                 $parentName = $paths[$ranksLength - 2];
        //                 $parentId = $ids[$ranksLength - 2];

        //                 return [$name, $iri, $organism, $parentRank];
        //             }
        //         }
        //     }
        // } else {
        //     Self::error("Could not map: $name");
        // }
    }

    /**
     * Updates an organism model in the database with the given name, IRI, and rank.
     *
     * If the organism is not found in the database, an error message will be logged.
     *
     * @param  string  $name  The organism name.
     * @param  string  $iri  The organism IRI.
     * @param  Organism|null  $organism  The organism model to update. If not provided, it will be retrieved from the database.
     * @param  string|null  $rank  The organism rank. If not provided, it will not be updated.
     * @return void
     */
    protected static function updateOrganismModel($name, $iri, $organism = null, $rank = null)
    {
        if (! $organism) {
            $organism = Organism::where('name', $name)->first();
        }

        if ($organism) {
            $organism->name = $name;
            $organism->iri = $iri;
            $organism->rank = $rank;
            $organism->save();
        } else {
            self::error("Organism not found in the database: $name");
        }
    }

    /**
     * Searches for an organism's IRI using the OLS API.
     *
     * This function makes a GET request to the OLS API, searching for the specified
     * scientific name across multiple ontologies. The search results are returned
     * as a JSON-decoded array.
     *
     * @param  string  $name  The scientific name of the organism to search for.
     * @param  string  $rank  The taxonomic rank of the organism (e.g., species, genus, family).
     * @return array|null The search results as an associative array, or null if an error occurs.
     */
    protected static function getOLSIRI($name, $rank)
    {
        $client = new Client([
            'base_uri' => 'https://www.ebi.ac.uk/ols4/api/',
        ]);

        try {
            $response = $client->get('search', [
                'query' => [
                    'q' => $name,
                    'ontology' => ['ncbitaxon', 'efo', 'obi', 'uberon', 'taxrank'],
                    'exact' => false,
                    'obsoletes' => false,
                    'format' => 'json',
                ],
            ]);

            $data = json_decode($response->getBody(), true);

            return $data;

            // if (isset($data['elements']) && count($data['elements']) > 0) {

            //     $element = $data['elements'][0];
            //     if (isset($element['iri'], $element['ontologyId']) && $element['isObsolete'] === 'false') {
            //         if ($rank && $rank == 'species') {
            //             if (isset($element['http://purl.obolibrary.org/obo/ncbitaxon#has_rank']) && $element['http://purl.obolibrary.org/obo/ncbitaxon#has_rank'] == 'http://purl.obolibrary.org/obo/NCBITaxon_species') {
            //                 return urlencode($element['iri']);
            //             }
            //         } elseif ($rank && $rank == 'genus') {
            //             if (isset($element['http://purl.obolibrary.org/obo/ncbitaxon#has_rank']) && $element['http://purl.obolibrary.org/obo/ncbitaxon#has_rank'] == 'http://purl.obolibrary.org/obo/NCBITaxon_genus') {
            //                 return urlencode($element['iri']);
            //             }
            //         } elseif ($rank && $rank == 'family') {
            //             if (isset($element['http://purl.obolibrary.org/obo/ncbitaxon#has_rank']) && $element['http://purl.obolibrary.org/obo/ncbitaxon#has_rank'] == 'http://purl.obolibrary.org/obo/NCBITaxon_family') {
            //                 return urlencode($element['iri']);
            //             }
            //         }
            //     }
            // }
        } catch (\Exception $e) {
            // Self::error("Error fetching IRI for $name: " . $e->getMessage());
            Log::error("Error fetching IRI for $name: ".$e->getMessage());
        }

        return null;
    }
}
