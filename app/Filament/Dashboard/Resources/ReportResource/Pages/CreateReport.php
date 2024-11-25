<?php

namespace App\Filament\Dashboard\Resources\ReportResource\Pages;

use App\Events\ReportSubmitted;
use App\Filament\Dashboard\Resources\ReportResource;
use App\Models\Citation;
use App\Models\Collection;
use App\Models\Molecule;
use App\Models\Organism;
use Filament\Actions\Action;
use Filament\Resources\Pages\CreateRecord;

class CreateReport extends CreateRecord
{
    /**
     * The resource the record belongs to.
     */
    protected static string $resource = ReportResource::class;

    /**
     * Molecule which is being reported
     */
    protected $molecule;

    /**
     * Is this a change report?
     */
    protected $is_change = false;

    /**
     * Mol IDs CSV
     */
    protected $mol_id_csv = null;

    /**
     * Report Type
     */
    protected $report_type = null;

    /**
     * Evidence
     */
    protected $evidence = null;

    /**
     * Returns the title of the report creation page.
     *
     * If the report is for a molecule, it appends the molecule's name and identifier to the title.
     */
    public function getTitle(): string
    {
        $title = 'Create Report';
        request()->type == 'change' ? $title = 'Request Changes' : $title = 'Report ';
        if (request()->has('compound_id')) {
            $title = $title.' - '.$this->molecule->name.' ('.$this->molecule->identifier.')';
        }

        return __($title);
    }

    /**
     * Prepares the data before filling the form.
     *
     * Sets the 'type' in the data array based on the request type. If a 'compound_id' is present in the request,
     * retrieves the corresponding Molecule instance and assigns it to the $molecule property.
     */
    protected function beforeFill(): void
    {
        $this->data['type'] = request()->type;

        if (request()->has('compound_id')) {
            $this->molecule = Molecule::where('identifier', request()->compound_id)->first();
        }
    }

    /**
     * Sets the initial data for the form after the Livewire component has been hydrated.
     *
     * Sets the molecule's identifier and type based on the request. If the type is 'change', sets the existing
     * synonyms, CAS numbers, geo locations, organisms, and citations for the molecule. Otherwise, sets the
     * identifier and type to null. If a 'collection_uuid' is present in the request, adds the collection's ID to
     * the 'collections' array. If a 'citation_id' is present in the request, adds the citation's ID to the
     * 'citations' array. If a 'compound_id' is present in the request, sets the 'mol_id_csv' to the compound's
     * identifier and sets the 'report_type' to 'molecule'. If an 'organism_id' is present in the request, adds the
     * organism's ID to the 'organisms' array and sets the 'report_type' to 'organism'.
     */
    protected function afterFill(): void
    {
        $request = request();
        $this->data['compound_id'] = $request->compound_id;
        $this->data['type'] = $request->type;

        if ($request->type == 'change') {
            $this->data['is_change'] = true;
            $this->is_change = true;

            $this->data['existing_geo_locations'] = $this->molecule->geo_locations->pluck('name')->toArray();
            $this->data['existing_synonyms'] = $this->molecule->synonyms;
            $this->data['existing_cas'] = array_values($this->molecule->cas ?? []);
            $this->data['existing_organisms'] = $this->molecule->organisms->pluck('name')->toArray();
            $this->data['existing_citations'] = $this->molecule->citations->where('title', '!=', null)->pluck('title')->toArray();
        } else {
            $this->data['is_change'] = false;
        }

        if ($request->has('collection_uuid')) {
            $collection = Collection::where('uuid', $request->collection_uuid)->get();
            $id = $collection[0]->id;
            array_push($this->data['collections'], $id);
            $this->data['report_type'] = 'collection';
        } elseif ($request->has('citation_id')) {
            $citation = Citation::where('id', $request->citation_id)->get();
            $id = $citation[0]->id;
            array_push($this->data['citations'], $id);
            $this->data['report_type'] = 'citation';
        } elseif ($request->has('compound_id')) {
            $this->data['mol_id_csv'] = $request->compound_id;
            $this->data['report_type'] = 'molecule';
        } elseif ($request->has('organism_id')) {
            $citation = Organism::where('id', $request->organism_id)->get();
            $id = $citation[0]->id;
            array_push($this->data['organisms'], $id);
            $this->data['report_type'] = 'organism';
        }
    }

    /**
     * Stores the validated data into class properties.
     */
    protected function afterValidate(): void
    {
        $this->mol_id_csv = $this->data['mol_id_csv'];
        $this->is_change = $this->data['is_change'];
        $this->report_type = $this->data['report_type'];
        $this->evidence = $this->data['evidence'];
    }

    /**
     * Prepare the validated data for creation.
     *
     * Depending on the selected report type, remove unnecessary fields
     * from the validated data.
     */
    protected function beforeCreate(): void
    {
        if ($this->data['report_type'] == 'collection') {
            $this->data['citations'] = [];
            $this->data['mol_id_csv'] = null;
            $this->data['organisms'] = [];
        } elseif ($this->data['report_type'] == 'citation') {
            $this->data['collections'] = [];
            $this->data['mol_id_csv'] = null;
            $this->data['organisms'] = [];
        } elseif ($this->data['report_type'] == 'molecule') {
            $this->data['collections'] = [];
            $this->data['citations'] = [];
            $this->data['organisms'] = [];
        } elseif ($this->data['report_type'] == 'organism') {
            $this->data['collections'] = [];
            $this->data['citations'] = [];
            $this->data['mol_id_csv'] = null;
        }
    }

    /**
     * Mutates the form data before creating a new record.
     *
     * Ensures that the record is assigned to the currently logged in user,
     * sets the initial status to 'submitted', and adds the report type and
     * evidence to the data.
     *
     * If the report is a change request, creates a suggested changes JSON
     * object and adds it to the data.
     *
     * @param  array  $data  The form data to be mutated.
     * @return array The mutated form data.
     */
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['user_id'] = auth()->id();
        $data['status'] = 'submitted';
        $data['mol_id_csv'] = $this->mol_id_csv;
        $data['is_change'] = $this->is_change;
        $data['report_type'] = $this->report_type;
        $data['evidence'] = $this->evidence;

        if ($data['is_change']) {
            $suggested_changes = [];
            $suggested_changes['existing_geo_locations'] = $data['existing_geo_locations'];
            $suggested_changes['new_geo_locations'] = $data['new_geo_locations'];
            $suggested_changes['approve_geo_locations'] = false;

            $suggested_changes['existing_synonyms'] = $data['existing_synonyms'];
            $suggested_changes['new_synonyms'] = $data['new_synonyms'];
            $suggested_changes['approve_synonyms'] = false;

            $suggested_changes['name'] = $data['name'];
            $suggested_changes['approve_name'] = false;

            $suggested_changes['existing_cas'] = $data['existing_cas'];
            $suggested_changes['new_cas'] = $data['new_cas'];
            $suggested_changes['approve_cas'] = false;

            $suggested_changes['existing_organisms'] = $data['existing_organisms'];
            $suggested_changes['approve_existing_organisms'] = false;

            $suggested_changes['new_organisms'] = $data['new_organisms'];

            $suggested_changes['existing_citations'] = $data['existing_citations'];
            $suggested_changes['approve_existing_citations'] = false;

            $suggested_changes['new_citations'] = $data['new_citations'];

            // Overall Changes suggested
            $suggested_changes['overall_changes'] = getOverallChanges($data);

            // seperate copy for Curators
            $suggested_changes['curator'] = $suggested_changes;

            $data['suggested_changes'] = $suggested_changes;
        }

        return $data;
    }

    /**
     * Executes actions after a report is created.
     *
     * If the report includes a 'mol_id_csv', it retrieves the corresponding
     * Molecule instances and associates them with the report. Dispatches a
     * ReportSubmitted event after the report creation.
     */
    protected function afterCreate(): void
    {
        if (! is_null($this->record->mol_id_csv)) {
            $mol_identifiers = explode(',', $this->record->mol_id_csv);
            $molecules = Molecule::whereIn('identifier', $mol_identifiers)->get();
            foreach ($molecules as $molecule) {

                $this->record->molecules()->attach($molecule);
            }
        }

        ReportSubmitted::dispatch($this->record);
    }

    /**
     * Gets the action for the create form.
     *
     * If the report is a change report, overrides the default action to:
     *  - hide the submit button
     *  - display the changes in a table
     *  - hide the modal when the 'is_change' property is false
     *  - calls the parent create method when the action is triggered
     */
    protected function getCreateFormAction(): Action
    {
        if (! $this->data['is_change']) {
            return parent::getCreateFormAction();
        }

        return parent::getCreateFormAction()
            ->submit(null)
            ->form(function () {
                return getChangesToDisplayModal($this->data);
            })
            ->modalHidden(function () {
                return ! $this->data['is_change'];
            })
            ->action(function () {
                $this->closeActionModal();
                $this->create();
            });
    }
}
