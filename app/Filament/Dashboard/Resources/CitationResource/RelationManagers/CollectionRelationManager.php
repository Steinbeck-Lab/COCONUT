<?php

namespace App\Filament\Dashboard\Resources\CitationResource\RelationManagers;

use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class CollectionRelationManager extends RelationManager
{
    /**
     * The relationship name.
     */
    protected static string $relationship = 'collections';

    /**
     * Gets the form schema used by the relation manager.
     */
    public function form(Form $form): Form
    {
        return $form
            ->schema([
            ]);
    }

    /**
     * Configures the table for the relation manager.
     *
     * Defines the columns, filters, actions, and bulk actions available
     * in the table. The table displays the 'title' attribute with wrapping
     * enabled and includes edit and delete actions for each record. A delete
     * bulk action is also provided.
     */
    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('title')
            ->columns([
                Tables\Columns\TextColumn::make('title')->wrap(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
