<?php

namespace App\Http\Livewire\Admin;

use Imagick;
use App\Models\User;
use App\Helpers\Helper;
use App\Models\Payment;
use App\Models\Setting;
use Livewire\Component;
use Illuminate\Support\Str;

class Home extends Component
{
    public $viewState = false;
    public $createState = true;
    public $updateState = false;

    public function render()
    {
        return view('livewire.admin.home')->layout('layouts.admin');
    }
}
