<?php

namespace App\Livewire;

use Livewire\Component;

class StructureEditor extends Component
{
    /**
     * The mode of the editor
     *
     * @var string
     */
    public $mode = 'inline';

    /**
     * The SMILES of the molecule
     *
     * @var string
     */
    public $smiles;

    /**
     * The type of the editor
     *
     * @var string
     */
    public $type = 'substructure';

    /**
     * Mounts the component and initializes the SMILES string.
     *
     * @param  string  $smiles  The SMILES notation representing the chemical structure.
     * @return void
     */
    public function mount($smiles)
    {
        $this->smiles = $smiles;
    }

    /**
     * Renders the structure editor view.
     *
     * @return \Illuminate\View\View The view for the structure editor.
     */
    public function render()
    {
        return view('livewire.structure-editor');
    }
}
