<?php

namespace App\View\Components;

use App\Helpers\Helper;
use Illuminate\View\Component;

class FrontContact extends Component
{

    public function __construct()
    {
        //
    }

    public function render()
    {
        return view('components.front-contact');
    }
}
