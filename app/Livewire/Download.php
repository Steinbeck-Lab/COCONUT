<?php

namespace App\Livewire;

use Livewire\Attributes\Layout;
use Livewire\Component;

class Download extends Component
{
    public $terms = '';

    /**
     * Renders the 'download' Livewire component view with the 'guest' layout.
     *
     * @return \Illuminate\View\View
     */
    #[Layout('layouts.guest')]
    public function render()
    {
        return view('livewire.download');
    }
}
