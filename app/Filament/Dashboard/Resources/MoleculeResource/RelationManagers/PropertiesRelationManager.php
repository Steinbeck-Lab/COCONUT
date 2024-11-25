<?php

namespace App\Filament\Dashboard\Resources\MoleculeResource\RelationManagers;

use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class PropertiesRelationManager extends RelationManager
{
    /**
     * The relationship name.
     */
    protected static string $relationship = 'properties';

    /**
     * Defines the form schema for managing the properties relationship.
     *
     * @param  Form  $form  The form instance to be configured.
     * @return Form The configured form instance.
     */
    public function form(Form $form): Form
    {
        return $form
            ->schema([]);
    }

    /**
     * Defines the table schema of the relation manager.
     *
     * @param  \Filament\Tables\Table  $table  The table instance to be configured.
     * @return \Filament\Tables\Table The configured table instance.
     */
    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('property')
            ->columns([
                Tables\Columns\TextColumn::make('molecular_formula'),
                Tables\Columns\TextColumn::make('np_likeness'),
                Tables\Columns\TextColumn::make('molecular_weight'),
                Tables\Columns\TextColumn::make('total_atom_count'),
                Tables\Columns\TextColumn::make('heavy_atom_count'),

            ])
            ->filters([
                //
            ])
            ->headerActions([
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
}
