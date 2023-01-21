<?php

namespace App\Http\Livewire\Dashboard\Users;

use App\Models\User;
use Livewire\Component;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Traits\AuthorizesRoleOrPermission;

class Edit extends Component

{
    use AuthorizesRoleOrPermission;
    public $title = 'User';
    public $state = [], $user, $roles, $selectedRoles = [];

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

    public function mount(User $user)
    {
        $this->state = $user->toArray();
        $this->user = $user;
        $this->roles = Role::orderBy('name', 'asc')->where('guard_name', 'web')->get();
        $this->selectedRoles = $user->roles()->pluck('id')->map(fn ($item) => (string) $item)->toArray();
    }

    public function render()
    {

        return view('livewire.dashboard.users.edit', [
            'title' => $this->title
        ])->layout('layouts.dashboard');
    }

    public function update()
    {
        // $this->rules = array_diff($this->rules, []);
        $this->state['selectedRoles'] = $this->selectedRoles;
        $this->rules = array_merge($this->rules, [
            'email' => 'required|email|unique:users,email,' . $this->state['id'],
            'selectedRoles' => 'required|array|min:1',
        ]);
        // dd($this->selectedRoles);
        $this->validateForm();
        $this->state['password'] = Hash::make($this->state['password']);
        $this->user->update($this->state);
        $this->user->syncRoles($this->selectedRoles);
        session()->flash('success', 'Data has been updated successfully.');
        $this->resetInputFields();
    }

    private function resetInputFields()
    {
        return redirect()->route('dashboard.users.index');
    }

    public function cancel()
    {
        $this->resetInputFields();
    }
}
