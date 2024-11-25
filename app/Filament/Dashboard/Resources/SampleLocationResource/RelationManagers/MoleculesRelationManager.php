<?php

namespace App\Filament\Dashboard\Resources\SampleLocationResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class MoleculesRelationManager extends RelationManager
{
    /**
     * The name of the relationship.
     */
    protected static string $relationship = 'molecules';

    /**
     * Defines the form schema for managing the molecules relationship.
     *
     * @param  \Filament\Forms\Form  $form  The form instance to be configured.
     * @return \Filament\Forms\Form The configured form instance with the schema.
     */
    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    /**
     * Configures the table used by the relation manager.
     *
     * @param  \Filament\Tables\Table  $table  The table instance to be configured.
     * @return \Filament\Tables\Table The configured table instance.
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
