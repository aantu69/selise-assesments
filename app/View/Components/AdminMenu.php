<?php

namespace App\View\Components;

use App\Models\Setting;
use Illuminate\View\Component;

class AdminMenu extends Component
{
    public $logo;
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|string
     */
    public function render()
    {
        return view('components.admin-menu');
    }
}
