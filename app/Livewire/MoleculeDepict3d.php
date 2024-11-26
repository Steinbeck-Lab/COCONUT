<?php

namespace App\Livewire;

use Livewire\Attributes\Computed;
use Livewire\Component;

class MoleculeDepict3d extends Component
{
    /**
     * SMILES string to be depicted
     *
     * @var string
     */
    public $smiles = null;

    /**
     * Height of the depiction in pixels
     *
     * @var int
     */
    public $height = 200;

    /**
     * Width of the depiction
     *
     * @var string
     */
    public $width = '100%';

    public $CIP = true;

    /**
     * Generate the 3D depiction source URL for the molecule.
     *
     * @return string The URL to generate the 3D depiction using SMILES and CM API.
     */
    #[Computed]
    public function source()
    {
        return env('CM_API').'depict/3D?smiles='.urlencode($this->smiles).'&height='.$this->height.'&width='.$this->width.'&CIP='.$this->CIP.'&toolkit=rdkit';
    }

    /**
     * Render the molecule depiction 3D view.
     *
     * @return \Illuminate\View\View The view for displaying the 3D molecule depiction.
     */
    public function render()
    {
        return view('livewire.molecule-depict3d');
    }
}
