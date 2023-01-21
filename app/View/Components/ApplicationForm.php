<?php

namespace App\View\Components;

use Carbon\Carbon;
use Illuminate\View\Component;

class ApplicationForm extends Component
{
    public $createState;
    public $state;

    public function __construct($createState, $state)
    {
        $this->createState = $createState;
        $this->state = $state;
    }

    public function render()
    {
        return view('components.application-form');
    }
}
