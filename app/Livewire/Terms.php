<?php

namespace App\Livewire;

use Illuminate\Support\Str;
use Laravel\Jetstream\Jetstream;
use Livewire\Attributes\Layout;
use Livewire\Component;

class Terms extends Component
{
    /**
     * The terms of service.
     *
     * @var string
     */
    public $terms = '';

    /**
     * Mounts the component and loads the terms of service from a markdown file.
     *
     * @return void
     */
    public function mount()
    {
        $termsFile = Jetstream::localizedMarkdownPath('terms.md');
        $this->terms = Str::markdown(file_get_contents($termsFile));
    }

    /**
     * Renders the terms view.
     *
     * @return \Illuminate\View\View The view for displaying the terms of service.
     */
    #[Layout('layouts.guest')]
    public function render()
    {
        return view('terms');
    }
}
