<?php

namespace App\Http\Livewire\Dashboard;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class PasswordChanges extends Component
{
    public $current_password, $password, $password_confirmation;

    protected $rules = [
        'current_password' => 'required',
        'password' => 'required|string|min:6|confirmed',
    ];

    public function render()
    {
        return view('livewire.dashboard.password-changes')->layout('layouts.dashboard');
    }

    public function changePassword()
    {
        $this->validate();
        if (!(Hash::check($this->current_password, Auth::user()->password))) {
            return redirect()->back()->with("error", "Your current password does not matches with the password you provided. Please try again.");
        }
        if (strcmp($this->current_password, $this->password) == 0) {
            return redirect()->back()->with("error", "New Password cannot be same as your current password. Please choose a different password.");
        }

        $data = [
            'password' => Hash::make($this->password)
        ];

        Auth::user()->update($data);
        session()->flash('success', 'Password has been changed successfully.');
        $this->reset(['current_password', 'password', 'password_confirmation']);
    }
}
