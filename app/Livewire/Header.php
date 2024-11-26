<?php

namespace App\Livewire;

use Livewire\Component;

class Header extends Component
{
    /**
     * Render the header view.
     *
     * @return \Illuminate\View\View The view instance for the header.
     *
     * This method renders the view associated with the livewire header component.
     */
    public function render()
    {
        return view('livewire.header');
    }
}
