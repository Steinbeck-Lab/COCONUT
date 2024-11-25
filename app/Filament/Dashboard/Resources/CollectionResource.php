<?php

namespace App\Filament\Dashboard\Resources;

use App\Filament\Dashboard\Resources\CollectionResource\Pages;
use App\Filament\Dashboard\Resources\CollectionResource\RelationManagers\CitationsRelationManager;
use App\Filament\Dashboard\Resources\CollectionResource\RelationManagers\EntriesRelationManager;
use App\Filament\Dashboard\Resources\CollectionResource\RelationManagers\MoleculesRelationManager;
use App\Filament\Dashboard\Resources\CollectionResource\Widgets\CollectionStats;
use App\Filament\Dashboard\Resources\CollectionResource\Widgets\EntriesOverview;
use App\Livewire\ShowJobStatus;
use App\Models\Collection;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Livewire;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\SpatieTagsInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use Tapp\FilamentAuditing\RelationManagers\AuditsRelationManager;

class CollectionResource extends Resource
{
    /**
     * The model the resource corresponds to.
     */
    protected static ?string $model = Collection::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     */
    protected static ?string $titleAttribute = 'title';

    /**
     * The navigation group that the resource should be placed in.
     */
    protected static ?string $navigationGroup = 'Data';

    /**
     * The sort order of the resource in the navigation.
     */
    protected static ?int $navigationSort = 1;

    /**
     * The navigation icon for the resource.
     */
    protected static ?string $navigationIcon = 'heroicon-o-swatch';

    /**
     * The record title attribute.
     */
    protected static ?string $recordTitleAttribute = 'title';

    /**
     * Get the form schema for the resource.
     */
    public static function form(Form $form): Form
    {
        return $form
            ->schema(
                [
                    Livewire::make(ShowJobStatus::class),

                    Section::make('Database details')
                        ->description('Provide details of the database and link to the resource.')
                        ->schema([
                            TextInput::make('title')->live()
                                ->afterStateUpdated(fn (Set $set, ?string $state) => $set('slug', Str::slug($state))
                                ),
                            TextInput::make('slug'),
                            Textarea::make('description'),
                            TextInput::make('url'),
                        ]),
                    Section::make('Meta data')
                        ->schema([
                            SpatieTagsInput::make('tags')
                                ->splitKeys(['Tab', ','])
                                ->type('collections'),
                            TextInput::make('identifier'),
                        ]),
                    Section::make()
                        ->schema([
                            FileUpload::make('image')
                                ->label('Display image')
                                ->image()
                                ->directory('collections')
                                ->visibility('public')
                                ->imageEditor()
                                ->imageEditorAspectRatios([
                                    '1:1',
                                ]),

                        ]),
                    Section::make('Distribution')
                        ->schema([
                            Select::make('license')
                                ->relationship('license', 'title')
                                ->preload()
                                ->searchable(),
                        ]),
                ]
            )->columns(1);
    }

    /**
     * The table schema for the resource.
     */
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->sortable()
                    ->searchable()
                    ->wrap(),
                Tables\Columns\TextColumn::make('entries')
                    ->state(function (Model $record) {
                        return $record->total_entries.'/'.$record->failed_entries;
                    }),
                Tables\Columns\TextColumn::make('molecules_count')->label('Molecules'),
                Tables\Columns\TextColumn::make('citations_count')->label('Citations'),
                Tables\Columns\TextColumn::make('organisms_count')->label('Organisms'),
                Tables\Columns\TextColumn::make('geo_count')->label('Geo Locations'),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'DRAFT' => 'info',
                        'REVIEW' => 'warning',
                        'EMBARGO' => 'warning',
                        'PUBLISHED' => 'success',
                        'REJECTED' => 'danger',
                    }),
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
            ])
            ->paginated([10, 25, 50, 100, 'all'])
            ->defaultPaginationPageOption(100);
    }

    /**
     * Get the relation managers for the Collection resource.
     *
     * @return array An array of relation manager class names associated with the Collection resource.
     */
    public static function getRelations(): array
    {
        $arr = [
            EntriesRelationManager::class,
            CitationsRelationManager::class,
            AuditsRelationManager::class,
            MoleculesRelationManager::class,
        ];

        return $arr;
    }

    /**
     * Returns an array of the URLs for each page in the Collection resource.
     *
     * @return array An array of page URLs associated with the Collection resource.
     */
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCollections::route('/'),
            'create' => Pages\CreateCollection::route('/create'),
            'edit' => Pages\EditCollection::route('/{record}/edit'),
            'view' => Pages\ViewCollection::route('/{record}'),
        ];
    }

    /**
     * Get the widgets available on the resource.
     *
     * @return array An array of widget class names associated with the Collection resource.
     */
    public static function getWidgets(): array
    {
        return [
            CollectionStats::class,
            EntriesOverview::class,
        ];
    }

    /**
     * Get the navigation badge for the Collection resource.
     *
     * @return string|null The navigation badge value, or null if not available.
     */
    public static function getNavigationBadge(): ?string
    {
        return Cache::get('stats.collections');
    }
}
