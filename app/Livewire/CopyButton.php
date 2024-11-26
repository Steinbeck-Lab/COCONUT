<?php

namespace App\Livewire;

use Livewire\Component;

class CopyButton extends Component
{
    /**
     * The text that needs to be copied.
     *
     * @var string
     */
    public $textToCopy;

    /**
     * Initialize the component with text to be copied.
     *
     * @param  string  $textToCopy  The text that needs to be copied.
     *
     * This method is called when the component is mounted, setting the
     * textToCopy property with the provided text.
     */
    public function mount($textToCopy)
    {
        $this->textToCopy = $textToCopy;
    }

    /**
     * Render the copy button view.
     *
     * @return \Illuminate\View\View The view instance for the copy button.
     *
     * This method renders the view associated with the livewire component.
     */
    public function render()
    {
        return view('livewire.copy-button');
    }
}
