<?php

namespace App\Filament\Dashboard\Resources\OrganismResource\RelationManagers;

use App\Models\SampleLocation;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class SampleLocationsRelationManager extends RelationManager
{
    /**
     * The relationship name.
     */
    protected static string $relationship = 'sampleLocations';

    /**
     * The form schema used by the relation manager.
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
     * The table schema used by the relation manager.
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
                Tables\Actions\Action::make('edit')
                    ->url(fn (SampleLocation $record) => env('APP_URL').'/dashboard/sample-locations/'.$record->id.'/edit')
                    // ->color('info')
                    ->icon('heroicon-m-pencil-square'),
                // Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
