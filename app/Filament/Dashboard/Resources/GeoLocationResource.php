<?php

namespace App\Filament\Dashboard\Resources;

use App\Filament\Dashboard\Resources\GeoLocationResource\Pages;
use App\Filament\Dashboard\Resources\GeoLocationResource\Widgets\GeoLocationStats;
use App\Models\GeoLocation;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Cache;
use Tapp\FilamentAuditing\RelationManagers\AuditsRelationManager;

class GeoLocationResource extends Resource
{
    /**
     * Navitation group to which the resource belongs to
     */
    protected static ?string $navigationGroup = 'Data';

    /**
     * Navigation sort order
     */
    protected static ?int $navigationSort = 5;

    /**
     * The model the resource corresponds to.
     */
    protected static ?string $model = GeoLocation::class;

    /**
     * Navigation icon
     */
    protected static ?string $navigationIcon = 'heroicon-o-map-pin';

    /**
     * Defines the form schema for the GeoLocation resource.
     *
     * @param  Form  $form  The form instance to be configured.
     * @return Form The configured form instance.
     */
    public static function form(Form $form): Form
    {
        return $form
            ->schema(GeoLocation::getForm());
    }

    /**
     * Defines the table schema for the GeoLocation resource.
     *
     * @param  Table  $table  The table instance to be configured.
     * @return Table The configured table instance.
     */
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
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
     * Get the relation managers for the GeoLocation resource.
     *
     * @return array An array of relation manager class names associated with the GeoLocation resource.
     */
    public static function getRelations(): array
    {
        return [
            AuditsRelationManager::class,
        ];
    }

    /**
     * Returns an array of the URLs for each page in the GeoLocation resource.
     *
     * @return array An array of page URLs associated with the GeoLocation resource.
     */
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListGeoLocations::route('/'),
            'create' => Pages\CreateGeoLocation::route('/create'),
            'edit' => Pages\EditGeoLocation::route('/{record}/edit'),
            'view' => Pages\ViewGeoLocation::route('/{record}'),
        ];
    }

    /**
     * Retrieves an array of widget class names for the GeoLocation resource.
     *
     * @return array An array of widget class names.
     */
    public static function getWidgets(): array
    {
        return [
            GeoLocationStats::class,
        ];
    }

    /**
     * Get the navigation badge for the GeoLocation resource.
     *
     * @return string|null The navigation badge value, or null if not available.
     */
    public static function getNavigationBadge(): ?string
    {
        return Cache::get('stats.geo_locations');
    }
}
