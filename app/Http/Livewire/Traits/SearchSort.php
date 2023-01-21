<?php

namespace App\Http\Livewire\Traits;

use Livewire\WithPagination;


trait SearchSort
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    public $perPage  = 10;
    public $search;
    public $sortColumn = 'id';
    public $sortDirection = 'asc';

    public $checked = [];
    public $selectPage = false;
    public $selectAll = false;

    public function sortBy($column)
    {
        $this->sortColumn = $column;
        $this->sortDirection = $this->sortDirection == 'asc' ? 'desc' : 'asc';
    }

    // public function updated($property)
    // {
    //     $this->validateOnly($property);
    // }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingPerPage()
    {
        $this->resetPage();
    }

    public function updatedSelectPage($value)
    {
        if ($value) {
            $this->checked = $this->results->pluck('id')->map(fn ($item) => (string) $item)->toArray();
        } else {
            $this->checked = [];
            $this->selectAll = false;
        }
    }

    public function selectAll()
    {
        $this->selectAll = true;
        $this->checked = $this->resultsQuery->pluck('id')->map(fn ($item) => (string) $item)->toArray();
    }

    public function updatedChecked()
    {
        $total_current = count($this->results->pluck('id')->map(fn ($item) => (string) $item)->toArray());
        $selected = count($this->checked);
        $this->selectPage = ($total_current == $selected) ? true : false;
    }
}
