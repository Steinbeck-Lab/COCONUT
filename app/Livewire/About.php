<?php

namespace App\Livewire;

use Livewire\Attributes\Layout;
use Livewire\Component;

class About extends Component
{
    public $terms = '';

    /**
     * Render the 'about' Livewire component view with the 'guest' layout.
     *
     * @return \Illuminate\View\View
     */
    #[Layout('layouts.guest')]
    public function render()
    {
        return view('livewire.about');
    }
}
