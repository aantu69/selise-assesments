<?php

namespace App\View\Components;

use Illuminate\View\Component;

class DemandForm extends Component
{
    public $state;
    public $createState;

    public function __construct($state, $createState)
    {
        $this->state = $state;
        $this->createState = $createState;
    }

    public function render()
    {
        return view('components.demand-form');
    }
}
