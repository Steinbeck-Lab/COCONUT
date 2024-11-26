<?php

namespace App\Livewire;

use App\Models\Molecule;
use Cache;
use Livewire\Component;
use Livewire\WithPagination;

class RecentMolecules extends Component
{
    use WithPagination;

    /**
     * The number of molecules to display.
     *
     * @var int
     */
    public $size = 5;

    /**
     * Renders the recent molecules page by fetching molecules from cache or querying the database.
     * Caches the recent molecules for 48 hours.
     *
     * @return \Illuminate\View\View The rendered view of recent molecules.
     */
    public function render()
    {
        return view('livewire.recent-molecules', [
            'molecules' => Cache::remember('molecules.recent', 172800, function () {
                return Molecule::where('is_parent', false)->where('active', true)->where('name', '!=', null)->where('annotation_level', '=', 5)->orderByDesc('updated_at')->paginate($this->size);
            }),
        ]);
    }
}
