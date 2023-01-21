<?php

namespace App\Http\Livewire\Dashboard\Users;

use App\Models\User;
use Livewire\Component;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Traits\AuthorizesRoleOrPermission;

class Create extends Component
{
    use AuthorizesRoleOrPermission;

    public $title = 'User';

    public $state = [], $user, $roles, $selectedRoles = [];

    protected $listeners = ['destroy'];

    protected $rules = [
        'name' => 'required',
    ];

    protected $messages = [
        'selectedRoles.required' => 'Please select atleast one role.',
        'selectedRoles.min' => 'Please select atleast one role.',
    ];

    public function validateForm()
    {
        Validator::make($this->state, $this->rules, $this->messages)->validate();
    }

    public function mount()
    {
        $this->roles = Role::orderBy('name', 'asc')->where('guard_name', 'web')->get();
    }

    public function render()
    {
        return view('livewire.dashboard.users.create', [
            'title' => $this->title
        ])->layout('layouts.dashboard');
    }

    public function store()
    {
        $this->state['selectedRoles'] = $this->selectedRoles;
        $this->rules = array_merge($this->rules, [
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8',
            'selectedRoles' => 'required|array|min:1',
        ]);

        $this->state['remember_token'] = Str::random(10);
        $this->state['approved'] = 1;
        $this->state['active'] = 1;
        $this->state['password'] = Hash::make($this->state['password']);
        // dd($this->state);
        $this->validateForm();
        $user = User::create($this->state);
        $user->syncRoles($this->selectedRoles);
        session()->flash('success', 'Data has been created successfully.');
        $this->resetInputFields();
    }

    public function cancel()
    {
        $this->resetInputFields();
    }

    private function resetInputFields()
    {
        return redirect()->route('dashboard.users.index');
    }
}
