<?php

namespace App\Filament\Dashboard\Resources\CollectionResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class MoleculesRelationManager extends RelationManager
{
    /**
     * The relationship name.
     */
    protected static string $relationship = 'molecules';

    /**
     * The form schema used by the relation manager.
     */
    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('canonical_smiles')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    /**
     * The table schema of the relation manager.
     */
    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('canonical_smiles')
            ->columns([
                Tables\Columns\TextColumn::make('identifier'),
                Tables\Columns\TextColumn::make('canonical_smiles'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
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
