<?php

namespace App\Livewire;

use App\Models\Collection;
use Cache;
use Livewire\Component;

class DataSources extends Component
{
    /**
     * The collections to display.
     *
     * @var array
     */
    public $collections = [];

    /**
     * Cache the 10 most recently promoted collections to avoid querying the
     * database on every request. The cache is valid for 48 hours.
     *
     * @return void
     */
    public function mount()
    {
        $this->collections = Cache::remember('collections', 172800, function () {
            return Collection::where('promote', true)->orderBy('sort_order')->take(10)->get(['title', 'image'])->toArray();
        });
    }
}
