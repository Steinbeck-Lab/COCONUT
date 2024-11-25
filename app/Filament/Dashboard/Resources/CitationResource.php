<?php

namespace App\Filament\Dashboard\Resources;

use App\Filament\Dashboard\Resources\CitationResource\Pages;
use App\Filament\Dashboard\Resources\CitationResource\RelationManagers\CollectionRelationManager;
use App\Filament\Dashboard\Resources\CitationResource\RelationManagers\MoleculeRelationManager;
use App\Models\Citation;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Cache;
use Tapp\FilamentAuditing\RelationManagers\AuditsRelationManager;

class CitationResource extends Resource
{
    /**
     * The group the resource belongs to.
     */
    protected static ?string $navigationGroup = 'Data';

    /**
     * The model the resource corresponds to.
     */
    protected static ?string $model = Citation::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     */
    protected static ?string $recordTitleAttribute = 'title';

    /**
     * The slug that should be used to represent the resource when being displayed.
     */
    protected static ?string $slug = 'citations';

    /**
     * The navigation sort order for the resource.
     */
    protected static ?int $navigationSort = 2;

    /**
     * The navigation icon for the resource.
     */
    protected static ?string $navigationIcon = 'heroicon-o-ticket';

    /**
     * Gets the form schema used by the resource.
     */
    public static function form(Form $form): Form
    {
        return $form
            ->schema(Citation::getForm());
    }

    /**
     * Gets the table schema used by the resource.
     */
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')->wrap()
                    ->description(fn (Citation $citation): string => $citation->authors.' ~ '.$citation->doi)
                    ->searchable(),
                TextColumn::make('doi')->wrap()
                    ->label('DOI')
                    ->searchable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    /**
     * Get the relation managers for the Citation resource.
     *
     * @return array An array of relation manager class names associated with the Citation resource.
     */
    public static function getRelations(): array
    {
        return [
            CollectionRelationManager::class,
            MoleculeRelationManager::class,
            AuditsRelationManager::class,
        ];
    }

    /**
     * Get the pages for the Citation resource.
     *
     * @return array An associative array of page classes and their corresponding routes.
     */
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCitations::route('/'),
            'create' => Pages\CreateCitation::route('/create'),
            'edit' => Pages\EditCitation::route('/{record}/edit'),
        ];
    }

    /**
     * Get the navigation badge for the Citation resource.
     *
     * @return string|null The navigation badge value, or null if not available.
     */
    public static function getNavigationBadge(): ?string
    {
        return Cache::get('stats.citations');
    }
}
