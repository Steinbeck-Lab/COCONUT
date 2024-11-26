<?php

namespace App\Livewire;

use Livewire\Component;

class MoleculeHistoryTimeline extends Component
{
    /**
     * The Molecule model instance.
     *
     * @var \App\Models\Molecule
     */
    public $mol = null;

    /**
     * The history of audits for the molecule.
     *
     * @var array
     */
    public $audit_data = [];

    /**
     * Retrieves the history of audits for the molecule, including metadata and affected columns.
     *
     * @return void
     */
    public function getHistory()
    {
        $audit_data = [];
        $audits_collection = $this->mol->audits->merge($this->mol->properties()->get()[0]->audits);
        foreach ($audits_collection->sortByDesc('created_at') as $index => $audit) {
            $audit_data[$index]['user_name'] = $audit->getMetadata()['user_name'];
            $audit_data[$index]['event'] = $audit->getMetadata()['audit_event'];
            $audit_data[$index]['created_at'] = date('Y/m/d', strtotime($audit->getMetadata()['audit_created_at']));

            $values = ! empty($audit->old_values) ? $audit->old_values : $audit->new_values;
            $first_affected_column = ! empty($values) ? array_keys($values)[0] : null;

            if (str_contains($first_affected_column, '.')) {
                $affected_column = explode('.', $first_affected_column)[0];

                $audit_data[$index]['affected_columns'][$affected_column]['old_value'] = $audit->old_values ? array_values($audit->old_values)[0] : null;
                $audit_data[$index]['affected_columns'][$affected_column]['new_value'] = $audit->new_values ? array_values($audit->new_values)[0] : null;
            } else {
                foreach ($audit->getModified() as $affected_column => $value) {

                    $audit_data[$index]['affected_columns'][$affected_column]['old_value'] = array_key_exists('old', $value) ? $value['old'] : null;
                    $audit_data[$index]['affected_columns'][$affected_column]['new_value'] = array_key_exists('new', $value) ? $value['new'] : null;
                }
            }
        }

        $initial_audit = [];
        $initial_audit['user_name'] = null;
        $initial_audit['event'] = null;
        $initial_audit['created_at'] = $this->mol->created_at->format('Y/m/d');
        $initial_audit['affected_columns']['created']['old_value'] = null;
        $initial_audit['affected_columns']['created']['new_value'] = null;

        array_push($audit_data, $initial_audit);
        $this->audit_data = $audit_data;
    }

    /**
     * Renders the view displaying the molecule's audit history timeline.
     *
     * @return \Illuminate\View\View The rendered view for molecule history timeline.
     */
    public function render()
    {
        return view('livewire.molecule-history-timeline')->with([
            'audit_data' => $this->audit_data,
        ]);
    }
}
