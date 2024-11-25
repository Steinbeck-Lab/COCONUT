<?php

namespace App\Filament\Dashboard\Resources;

use App\Filament\Dashboard\Resources\EntryResource\Pages;
use App\Models\Entry;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class EntryResource extends Resource
{
    /**
     * The model the resource corresponds to.
     */
    protected static ?string $model = Entry::class;

    /**
     * Label for the resource.
     */
    protected static ?string $label = 'Entry';

    /**
     * The navigation group for the resource.
     */
    protected static ?string $navigationGroup = 'Entry';

    /**
     * Should the resource be registered in navigation.
     */
    protected static bool $shouldRegisterNavigation = false;

    /**
     * Should the resource be registered in navigation.
     */
    protected static bool $shouldRegisterInNavigation = false;

    /**
     * Icon for the resource.
     */
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    /**
     * Get the table definition for the resource.
     */
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
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
     * Get the relation managers for the Entry resource.
     *
     * @return array An array of relation manager class names associated with the Entry resource.
     */
    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    /**
     * Returns an array of the URLs for each page in the Entry resource.
     *
     * @return array An array of page URLs associated with the Entry resource.
     */
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListEntries::route('/'),
            'create' => Pages\CreateEntry::route('/create'),
            'view' => Pages\ViewEntry::route('/{record}'),
            'edit' => Pages\EditEntry::route('/{record}/edit'),
        ];
    }

    /**
     * Returns an infolist schema for the Entry resource.
     *
     * An infolist is a set of components that can be used to display information
     * about a resource on a details page or in a table.
     *
     * @param  Infolist  $infolist  The infolist instance to configure.
     * @return Infolist The configured infolist instance.
     */
    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\TextEntry::make('indentifier'),
            ]);
    }
}
