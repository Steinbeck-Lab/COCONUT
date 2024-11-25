<?php

namespace App\Filament\Dashboard\Resources\CitationResource\RelationManagers;

use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class MoleculeRelationManager extends RelationManager
{
    /**
     * The relationship name.
     */
    protected static string $relationship = 'molecules';

    /**
     * Defines the form schema of the relation manager.
     */
    public function form(Form $form): Form
    {
        return $form
            ->schema([
            ]);
    }

    /**
     * The table schema of the relation manager.
     */
    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                Tables\Columns\TextColumn::make('name'),
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
