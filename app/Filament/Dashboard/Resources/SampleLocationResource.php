<?php

namespace App\Filament\Dashboard\Resources;

use App\Filament\Dashboard\Resources\SampleLocationResource\Pages;
use App\Filament\Dashboard\Resources\SampleLocationResource\RelationManagers\MoleculesRelationManager;
use App\Models\SampleLocation;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Tapp\FilamentAuditing\RelationManagers\AuditsRelationManager;

class SampleLocationResource extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var class-string<\App\Models\SampleLocation>
     */
    protected static ?string $model = SampleLocation::class;

    /**
     * Should the resource be registered?
     */
    protected static bool $shouldRegisterNavigation = false;

    /**
     * Navigation Group to which the resource belongs.
     */
    protected static ?string $navigationGroup = 'Data';

    /**
     * The navigation sort order for the resource.
     */
    protected static ?int $navigationSort = 6;

    /**
     * The navigation icon for the resource.
     */
    protected static ?string $navigationIcon = 'heroicon-s-viewfinder-circle';

    /**
     * Defines the form schema for the SampleLocationResource.
     *
     * @param  \Filament\Forms\Form  $form  The form instance to be configured.
     * @return \Filament\Forms\Form The configured form instance with the schema.
     */
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('iri')
                    ->maxLength(255),
                Forms\Components\Select::make('organism_id')
                    ->relationship('organisms', 'name')
                    ->searchable()
                    ->required(),
                Forms\Components\TextInput::make('collection_ids')
                    ->maxLength(255),
                Forms\Components\TextInput::make('molecule_count')
                    ->numeric(),
                // Forms\Components\TextInput::make('slug')
                //     ->maxLength(255),
            ]);
    }

    /**
     * Configures the table schema for the SampleLocation resource.
     *
     * This method sets up the columns, filters, actions, and bulk actions
     * for the SampleLocation table. The columns include attributes like
     * name, iri, organism_id, creation and update timestamps, collection_ids,
     * molecule_count, and slug. Each column is configured with options such
     * as searchable, numeric, sortable, and toggleable. The table also includes
     * actions for viewing and editing records, as well as a bulk action group
     * for deleting records.
     *
     * @param  \Filament\Tables\Table  $table  The table instance to be configured.
     * @return \Filament\Tables\Table The configured table instance.
     */
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('iri')
                    ->searchable(),
                Tables\Columns\TextColumn::make('organism_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('collection_ids')
                    ->searchable(),
                Tables\Columns\TextColumn::make('molecule_count')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('slug')
                    ->searchable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    /**
     * Retrieves an array of relation manager classes associated with the SampleLocation resource.
     *
     * @return array An array of relation manager class names.
     */
    public static function getRelations(): array
    {
        return [
            MoleculesRelationManager::class,
            AuditsRelationManager::class,
        ];
    }

    /**
     * Returns an array of the URLs for each page in the SampleLocation resource.
     *
     * The pages include list, create, view, and edit pages that are used to manage
     * the SampleLocation records. The list page displays a table of all SampleLocation
     * records, the create page allows users to create new SampleLocation records,
     * the view page allows users to view details of a specific SampleLocation record,
     * and the edit page allows users to edit existing SampleLocation records.
     *
     * @return array An associative array of page class names and their corresponding routes.
     */
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSampleLocations::route('/'),
            'create' => Pages\CreateSampleLocation::route('/create'),
            'view' => Pages\ViewSampleLocation::route('/{record}'),
            'edit' => Pages\EditSampleLocation::route('/{record}/edit'),
        ];
    }
}
