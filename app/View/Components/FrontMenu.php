<?php

namespace App\View\Components;

use App\Helpers\Helper;
use Illuminate\View\Component;

class FrontMenu extends Component
{
    public $logo;

    public function __construct()
    {
        //
    }

    public function render()
    {
        return view('components.front-menu');
    }
}
