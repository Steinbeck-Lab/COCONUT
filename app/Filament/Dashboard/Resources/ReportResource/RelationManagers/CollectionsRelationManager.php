<?php

namespace App\Filament\Dashboard\Resources\ReportResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class CollectionsRelationManager extends RelationManager
{
    /**
     * The relationship name.
     */
    protected static string $relationship = 'collections';

    /**
     * Defines the form schema for the Collections relation manager.
     *
     * @param  \Filament\Forms\Form  $form  The form instance.
     * @return \Filament\Forms\Form The configured form instance.
     */
    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('title')
                    ->required()
                    ->maxLength(255),
            ]);
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
                    ->multiple()
                    ->recordSelectSearchColumns(['title', 'description']),
            ])
            ->actions([
                Tables\Actions\DetachAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DetachBulkAction::make(),
                ]),
            ]);
    }

    /**
     * Determine if the record can be viewed for the given page class.
     *
     * This method checks if the report type of the owner record is 'collection'.
     * If it is, the method returns true, allowing the record to be viewed;
     * otherwise, it returns false.
     *
     * @param  Model  $ownerRecord  The record being checked.
     * @param  string  $pageClass  The class of the page where the record is being viewed.
     * @return bool True if the record can be viewed, false otherwise.
     */
    public static function canViewForRecord(Model $ownerRecord, string $pageClass): bool
    {
        if ($ownerRecord->report_type === 'collection') {
            return true;
        }

        return false;
    }
}
