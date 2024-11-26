<?php

namespace App\Livewire;

use Illuminate\Support\Str;
use Laravel\Jetstream\Jetstream;
use Livewire\Attributes\Layout;
use Livewire\Component;

class Policy extends Component
{
    /**
     * Variable to store the policy markdown.
     *
     * @var string
     */
    public $policy = '';

    /**
     * Mounts the policy content by reading the localized Markdown file and converting it to HTML.
     *
     * @return void
     */
    public function mount()
    {
        $policyFile = Jetstream::localizedMarkdownPath('policy.md');
        $this->policy = Str::markdown(file_get_contents($policyFile));
    }

    /**
     * Renders the policy page using the guest layout.
     *
     * @return \Illuminate\View\View The rendered policy page view.
     */
    #[Layout('layouts.guest')]
    public function render()
    {
        return view('policy');
    }
}
