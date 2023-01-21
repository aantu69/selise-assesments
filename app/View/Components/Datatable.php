<?php

namespace App\View\Components;

use Illuminate\View\Component;

class Datatable extends Component
{
    public $title;
    public $createButton;
    public $headers;
    public $results;
    public $sortColumn;
    public $sortDirection;
    public $perPageOptions;
    public $selectPage;
    public $selectAll;
    public $checked;
    public $printButton;
    public $printRoute;
    public $mswordButton;
    public $mswordRoute;
    public $gazetteButton;
    public $gazetteRoute;
    public $approveButton;
    public $excelButton;
    public $filterBy;
    public $keywords;
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        $title,
        $createButton,
        $headers,
        $results,
        $sortColumn,
        $sortDirection,
        $perPageOptions = null,
        $selectPage = null,
        $selectAll = null,
        $checked = null,
        $printButton = null,
        $printRoute = null,
        $mswordButton = null,
        $mswordRoute = null,
        $gazetteButton = null,
        $gazetteRoute = null,
        $approveButton = null,
        $excelButton = null,
        $filterBy = null,
        $keywords = null
    ) {
        $this->title = $title;
        $this->createButton = $createButton;
        $this->headers = $headers;
        $this->results = $results;
        $this->sortColumn = $sortColumn;
        $this->sortDirection = $sortDirection;
        $this->perPageOptions = $perPageOptions == null ? ['5' => '5', '10' => '10', '25' => '25', '50' => '50', 'All' => '-1'] : $perPageOptions;
        $this->selectPage = $selectPage;
        $this->selectAll = $selectAll;
        $this->checked = $checked;
        $this->printButton = $printButton == null ? false : $printButton;
        $this->printRoute = $printRoute;
        $this->mswordButton = $mswordButton == null ? false : $mswordButton;
        $this->mswordRoute = $mswordRoute;
        $this->gazetteButton = $gazetteButton == null ? false : $gazetteButton;
        $this->gazetteRoute = $gazetteRoute;
        $this->approveButton = $approveButton == null ? false : $approveButton;
        $this->excelButton = $excelButton;
        $this->filterBy = $filterBy == null ? false : $filterBy;
        $this->keywords = $keywords;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|string
     */
    public function render()
    {
        return view('components.datatable');
    }
}
