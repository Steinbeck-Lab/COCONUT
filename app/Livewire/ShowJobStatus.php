<?php

namespace App\Livewire;

use Livewire\Component;

class ShowJobStatus extends Component
{
    /**
     * The collection record.
     *
     * @var \App\Models\Collection
     */
    public $collection = null;

    /**
     * The fail message.
     *
     * @var string|null
     */
    public $failMessage = null;

    /**
     * Mounts the component with a given record.
     *
     * @param  mixed  $record  The record to be assigned to the collection property.
     * @return void
     */
    public function mount($record = null)
    {
        $this->collection = $record;
    }

    /**
     * Renders the job status view with associated job information.
     *
     * @return \Illuminate\View\View The view with job status and information.
     */
    public function render()
    {
        return view('livewire.show-job-status', [
            'status' => $this->collection ? $this->collection->jobs_status : null,
            'info' => $this->collection ? $this->collection->job_info : null,
        ]);
    }
}
