<?php

namespace App\Filament\Dashboard\Resources\MoleculeResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Actions\AttachAction;
use Filament\Tables\Table;

class OrganismsRelationManager extends RelationManager
{
    /**
     * The relationship name.
     */
    protected static string $relationship = 'organisms';

    /**
     * Defines the form schema for managing the organisms relationship.
     *
     * @param  Form  $form  The form instance to be configured.
     * @return Form The configured form instance.
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
     * Defines the table schema for the relation manager.
     *
     * @param  Table  $table  The table instance to be configured.
     * @return Table The configured table instance.
     */
    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                Tables\Columns\TextColumn::make('name'),
                Tables\Columns\TextColumn::make('organism_parts'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\AttachAction::make()
                    ->preloadRecordSelect()
                    ->form(fn (AttachAction $action): array => [
                        $action->getRecordSelect(),
                        Forms\Components\TextInput::make('organism_parts'),
                    ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->form(function ($action) {
                        return [
                            Forms\Components\TextInput::make('organism_parts'),
                        ];
                    }),
                Tables\Actions\DetachAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DetachBulkAction::make(),
                ]),
            ]);
    }
}
