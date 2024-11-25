<?php

namespace App\Filament\Dashboard\Resources\MoleculeResource\RelationManagers;

use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Table;

class RelatedRelationManager extends RelationManager
{
    /**
     * The relationship name.
     */
    protected static string $relationship = 'related';

    /**
     * The form schema used by the relation manager.
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
            ->recordTitleAttribute('name')
            ->columns([
                ImageColumn::make('structure')->square()
                    ->label('Structure')
                    ->state(function ($record) {
                        return env('CM_API', 'https://api.cheminf.studio/latest/').'depict/2D?smiles='.urlencode($record->canonical_smiles).'&height=300&width=300&CIP=true&toolkit=cdk';
                    })
                    ->width(200)
                    ->height(200)
                    ->ring(5)
                    ->defaultImageUrl(url('/images/placeholder.png')),
                // Tables\Columns\TextColumn::make('name')->searchable(),
                Tables\Columns\TextColumn::make('id')->searchable(),
                Tables\Columns\TextColumn::make('type')->searchable(),
                Tables\Columns\TextColumn::make('status'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\AttachAction::make(),
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
