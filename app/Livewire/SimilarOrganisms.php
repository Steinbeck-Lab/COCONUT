<?php

namespace App\Livewire;

use App\Models\Organism;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class SimilarOrganisms extends Component implements HasForms, HasTable
{
    use InteractsWithForms;
    use InteractsWithTable;

    /**
     * Defines the table configuration for displaying organisms.
     *
     * @param  Table  $table  The table instance to be configured.
     * @return Table The configured table instance.
     */
    public function table(Table $table): Table
    {
        return $table
            ->query(Organism::query()->select('id', 'name', urldecode('iri'))->where('id', 1))
            ->columns([
                TextColumn::make('name'),
            ])
            ->filters([
                // ...
            ])
            ->actions([
                // ...
            ])
            ->bulkActions([
                // ...
            ]);
    }

    /**
     * Renders the view for displaying similar organisms.
     *
     * @return \Illuminate\View\View The view for the similar organisms page.
     */
    public function render(): View
    {
        return view('livewire.similar-organisms');
    }
}
