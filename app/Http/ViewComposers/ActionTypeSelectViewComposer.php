<?php

namespace App\Http\ViewComposers;

use Illuminate\View\View;
use App\Models\ActionType;



class ActionTypeSelectViewComposer
{
    public function compose(View $view)
    {

        // $action_types = ActionType::all();
        // $view->with('action_types', $action_types);
    }
}
