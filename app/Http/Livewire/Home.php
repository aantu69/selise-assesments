<?php

namespace App\Http\Livewire;

use Carbon\Carbon;
use App\Models\Book;
use App\Helpers\Helper;
use Livewire\Component;
use App\Traits\ApiTrait;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use App\Http\Livewire\Traits\SearchSort;

class Home extends Component
{
    use SearchSort;
    public function mount()
    {
        // Session::forget('user_id');
    }

    public function render()
    {
        return view('livewire.home', [
            'books' => $this->results,
        ])->layout('layouts.frontend');
    }

    public function getResultsProperty()
    {
        return $this->resultsQuery
            ->when($this->perPage != -1, function ($q) {
                return $q->paginate($this->perPage);
            }, function ($q) {
                return $q->paginate($q->count());
            });
    }

    public function getResultsQueryProperty()
    {
        return Book::query()
            ->where(function ($query) {
                if ($this->search != "") {
                    $query->where('name', 'like', '%' . $this->search . '%');
                }
            })
            ->orderBy($this->sortColumn, $this->sortDirection);
    }
}
