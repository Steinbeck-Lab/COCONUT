<?php

namespace App\Livewire;

use App\Models\Collection;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.guest')]
class CollectionList extends Component
{
    use WithPagination;

    /**
     * The search query.
     *
     * @var string
     */
    #[Url(except: '', keep: true, history: true, as: 'q')]
    public $query = '';

    /**
     * The number of results per page.
     *
     * @var int
     */
    #[Url(as: 'limit')]
    public $size = 20;

    /**
     * The sort order.
     *
     * @var string
     */
    #[Url()]
    public $sort = null;

    /**
     * The current page.
     *
     * @var int
     */
    #[Url()]
    public $page = null;

    /**
     * Reset pagination when the query is updated.
     *
     * @return void
     *
     * This method ensures that pagination is reset when the query changes.
     */
    public function updatingQuery()
    {
        $this->resetPage();
    }

    /**
     * Render the collection list view with search and pagination.
     *
     * @return \Illuminate\View\View The view instance with collections data.
     *
     * This method handles searching for collections based on title or description
     * and paginates the results.
     */
    public function render()
    {
        $search = $this->query;
        $collections = Collection::query()
            ->where(function ($query) use ($search) {
                $query->whereRaw('LOWER(title) LIKE ?', ['%'.strtolower($search).'%'])
                    ->orWhereRaw('LOWER(description) LIKE ?', ['%'.strtolower($search).'%']);
            })
            ->orderByRaw('title LIKE ? DESC', [$search.'%'])
            ->orderByRaw('description LIKE ? DESC', [$search.'%'])
            ->paginate($this->size);

        return view('livewire.collection-list', ['collections' => $collections]);
    }
}
