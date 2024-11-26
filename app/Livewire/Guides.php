<?php

namespace App\Livewire;

use Livewire\Attributes\Layout;
use Livewire\Component;

class Guides extends Component
{
    /**
     * Render the guides view within the 'guest' layout.
     *
     * @return \Illuminate\View\View The view instance for the guides page.
     *
     * This method renders the view associated with the livewire guides component, using the 'guest' layout.
     */
    #[Layout('layouts.guest')]
    public function render()
    {
        return view('livewire.guides');
    }
}
