<?php

namespace App\Filament\Dashboard\Resources\ReportResource\RelationManagers;

use App\Models\Molecule;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\HtmlString;

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
            ->recordTitleAttribute('identifier')
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
                Tables\Columns\TextColumn::make('id')->searchable()->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('identifier')->searchable()->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('identifier')
                    ->label('Details')
                    ->formatStateUsing(
                        fn (Molecule $molecule): HtmlString => new HtmlString("<strong>ID:</strong> {$molecule->id}<br><strong>Identifier:</strong> {$molecule->identifier}<br><strong>Name:</strong> {$molecule->name}")
                    )
                    ->description(fn (Molecule $molecule): string => $molecule->standard_inchi)
                    ->wrap(),
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->wrap()
                    ->lineClamp(6)
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('synonyms')
                    ->searchable()
                    ->wrap()
                    ->lineClamp(6)
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('properties.exact_molecular_weight')
                    ->label('Mol.Wt')
                    ->numeric()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('properties.np_likeness')
                    ->label('NP Likeness')
                    ->numeric()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('status'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\AttachAction::make()
                    ->multiple()
                    ->recordSelectSearchColumns(['canonical_smiles']),
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
     * This method checks if the report type of the owner record is 'molecule'.
     * If it is, the method returns true, allowing the record to be viewed;
     * otherwise, it returns false.
     *
     * @param  Model  $ownerRecord  The record being checked.
     * @param  string  $pageClass  The class of the page where the record is being viewed.
     * @return bool True if the record can be viewed, false otherwise.
     */
    public static function canViewForRecord(Model $ownerRecord, string $pageClass): bool
    {
        if ($ownerRecord->report_type === 'molecule') {
            return true;
        }

        return false;
    }
}
