<?php

namespace App\Livewire;

use Livewire\Component;

class Footer extends Component
{
    /**
     * Render the footer view.
     *
     * @return \Illuminate\View\View The view instance for the footer.
     *
     * This method renders the view associated with the livewire footer component.
     */
    public function render()
    {
        return view('livewire.footer');
    }
}
