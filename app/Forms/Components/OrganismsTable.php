<?php

namespace App\Forms\Components;

use App\Models\Organism;
use Filament\Forms\Components\Field;

class OrganismsTable extends Field
{
    /**
     * The view used to render the field.
     */
    protected string $view = 'forms.components.organisms-table';

    /**
     * Creates a new OrganismsTable component.
     */
    public static function make(string $name): static
    {
        return parent::make($name);
    }

    /**
     * Fetches organisms which have molecules and have a name that contains part of the $record_name.
     *
     * @param  string  $record_name
     * @return \Illuminate\Support\Collection
     */
    public function getTableData($record_name)
    {
        return Organism::select('id', 'name', urldecode('iri'), 'molecule_count')
            ->where('molecule_count', '>', 0)
            ->where(function ($q) use ($record_name) {
                $arr = explode(' ', $record_name);
                $sanitised_org_name = $arr[0].' '.$arr[1];
                $q->where([
                    ['name', '!=', $record_name],
                    ['name', 'ILIKE', '%'.$sanitised_org_name.'%'],
                ]);
            })
            ->get();
    }
}
