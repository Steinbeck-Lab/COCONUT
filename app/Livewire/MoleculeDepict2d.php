<?php

namespace App\Livewire;

use Illuminate\Support\Facades\Http;
use Livewire\Attributes\Computed;
use Livewire\Component;

class MoleculeDepict2d extends Component
{
    /**
     * SMILES string to be depicted
     *
     * @var string
     */
    public $smiles = null;

    /**
     * Name of the molecule
     *
     * @var string
     */
    public $name = null;

    /**
     * Identifier of the molecule
     *
     * @var string
     */
    public $identifier = null;

    /**
     * Height of the depiction in pixels
     *
     * @var int
     */
    public $height = 200;

    /**
     * Width of the depiction in pixels
     *
     * @var int
     */
    public $width = 200;

    /**
     * Toolkit to be used for depiction
     *
     * @var string
     */
    public $toolkit = 'cdk';

    /**
     * Options to be used for depiction
     *
     * @var string
     */
    public $options = false;

    public $CIP = true;

    /**
     * Computed property that returns the source URL for the 2D image
     *
     * @return string
     */
    #[Computed]
    public function source()
    {
        return env('CM_API').'depict/2D?smiles='.urlencode($this->smiles).'&height='.$this->height.'&width='.$this->width;
    }

    /**
     * Generate the 2D depiction preview URL for the molecule.
     *
     * @return string The URL for generating the 2D depiction of the molecule.
     *
     * This method creates a URL for rendering the 2D representation of a molecule
     * based on its SMILES string, using the CM API.
     */
    #[Computed]
    public function preview()
    {
        return env('CM_API').'depict/2D?smiles='.urlencode($this->smiles);
    }

    /**
     * Download the MOL file for the molecule using a specified toolkit.
     *
     * @param  string  $toolkit  The name of the toolkit used for converting the molecule to MOL format.
     * @return \Symfony\Component\HttpFoundation\StreamedResponse The MOL file stream response.
     *
     * This method fetches the MOL file for the molecule by sending a request to
     * the CM API and streams it for download.
     */
    public function downloadMolFile($toolkit)
    {
        $response = Http::get(env('CM_API').'convert/mol2D?smiles='.urlencode($this->smiles).'&toolkit='.$toolkit);

        return response()->streamDownload(function () use ($response) {
            echo $response->body();
        }, $this->identifier.'.mol');
    }

    /**
     * Render the molecule depiction 2D view.
     *
     * @return \Illuminate\View\View The view instance for displaying the 2D molecule depiction.
     *
     * This method renders the view associated with the livewire molecule depiction component.
     */
    public function render()
    {
        return view('livewire.molecule-depict2d');
    }
}
