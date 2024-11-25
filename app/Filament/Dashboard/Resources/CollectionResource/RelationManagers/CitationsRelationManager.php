<?php

namespace App\Filament\Dashboard\Resources\CollectionResource\RelationManagers;

use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class CitationsRelationManager extends RelationManager
{
    /**
     * The relationship name.
     */
    protected static string $relationship = 'citations';

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
     * The table schema of the relation manager.
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
                Tables\Actions\AttachAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DetachAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DetachBulkAction::make(),
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
