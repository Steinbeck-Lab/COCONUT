<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LicenseResource\Pages;
use App\Filament\Resources\LicenseResource\RelationManagers\CollectionsRelationManager;
use App\Models\License;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class LicenseResource extends Resource
{
    /**
     * Navigation Group to which the resource belongs.
     */
    protected static ?string $navigationGroup = 'Settings';

    /**
     * The model the resource corresponds to.
     */
    protected static ?string $model = License::class;

    /**
     * Sort order of the resource in the navigation.
     */
    protected static ?int $navigationSort = 4;

    /**
     * The singular label of the resource.
     */
    protected static ?string $singularLabel = 'License';

    /**
     * Navigation icon for the resource.
     */
    protected static ?string $navigationIcon = 'heroicon-o-shield-check';

    /**
     * Build form for the license resource.
     */
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('title'),
                TextInput::make('spdx_id'),
                TextInput::make('url'),
                Textarea::make('description'),
                Textarea::make('body'),
                Textarea::make('category'),
            ])->columns(1);
    }

    /**
     * Build table for the license resource.
     */
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')->wrap()
                    ->searchable(),
                Tables\Columns\TextColumn::make('spdx_id')
                    ->searchable(),
                Tables\Columns\TextColumn::make('category')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('category'),
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
     * Defines the relationships that are available in the License Resource.
     */
    public static function getRelations(): array
    {
        return [
            CollectionsRelationManager::class,
        ];
    }

    /**
     * Get the pages for the License resource.
     *
     * @return array An associative array of page class names and their corresponding routes.
     */
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListLicenses::route('/'),
            'create' => Pages\CreateLicense::route('/create'),
            'edit' => Pages\EditLicense::route('/{record}/edit'),
        ];
    }
}
