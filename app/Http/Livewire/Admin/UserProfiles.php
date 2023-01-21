<?php

namespace App\Http\Livewire\Admin;

use App\Models\UserProfile;
use App\Models\AdminProfile;
use Livewire\Component;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class UserProfiles extends Component
{
    public $state = [], $user_profile;

    protected $rules = [
        'name' => 'required',
        'phone' => 'required'
    ];

    protected $messages = [
        // 'selectedRoles.required' => 'Please select atleast one role.',
    ];

    public function validateForm()
    {
        Validator::make($this->state, $this->rules, $this->messages)->validate();
    }

    public function mount()
    {
        $user_profile = AdminProfile::where('admin_id', Auth()->id())->first();
        $this->user_profile = $user_profile;
        $this->state = $user_profile->toArray();
    }


    public function render()
    {
        return view('livewire.admin.user-profiles')->layout('layouts.admin');
    }

    public function update()
    {
        $this->validateForm();
        $this->user_profile->update($this->state);
        session()->flash('success', 'Data has been updated successfully.');
    }
}
