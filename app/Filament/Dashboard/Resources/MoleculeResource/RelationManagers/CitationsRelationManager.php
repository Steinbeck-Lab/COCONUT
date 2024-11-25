<?php

namespace App\Filament\Dashboard\Resources\MoleculeResource\RelationManagers;

use App\Models\Citation;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class CitationsRelationManager extends RelationManager
{
    /**
     * Get the displayable name of the relationship.
     *
     * @return string
     */
    protected static string $relationship = 'citations';

    /**
     * Gets the form schema used by the relation manager.
     */
    public function form(Form $form): Form
    {
        return $form
            ->schema([]);
    }

    /**
     * Configure the table for the relation manager.
     */
    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('title')
            ->columns([
                Tables\Columns\TextColumn::make('title'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\AttachAction::make()
                    ->recordSelectSearchColumns(['title', 'authors', 'doi']),
                Tables\Actions\CreateAction::make()
                    ->form(Citation::getForm()),
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
