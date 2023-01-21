<?php

namespace App\Http\Livewire\Dashboard;

use App\Http\Livewire\Traits\SearchSort;
use App\Models\Circle;
use App\Models\User;
use Livewire\Component;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Traits\AuthorizesRoleOrPermission;

class Users extends Component
{
    use AuthorizesRoleOrPermission;
    use SearchSort;
    public $title = 'User';
    public $createButton = true;

    public $viewState = true;
    public $createState = false;
    public $updateState = false;

    public $state = [], $user, $roles, $circles, $selectedRoles = [], $selectedCircles = [];

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

    private function headerConfig()
    {
        return [
            [
                'data' => 'name', 'label' => 'Name', 'order' => true,
                'func' => function ($value) {
                    return $value;
                }
            ],
            [
                'data' => 'email', 'label' => 'Email', 'order' => true,
                'func' => function ($value) {
                    return $value;
                }
            ],
            [
                'data' => 'roles', 'label' => 'Roles', 'order' => false,
                'func' => function ($value) {
                    $perms = '';
                    foreach ($value as $role) {
                        $perms .= '<span class="btn btn-success btn-sm tbl-span">' . $role->name . '</span>&nbsp;';
                        // $perms .= '<span class="badge badge-success">' . $role->name . '</span>&nbsp;';
                    }
                    return $perms;
                }
            ],
            [
                'data' => 'circles', 'label' => 'Circles', 'order' => false,
                'func' => function ($value) {
                    $perms = '';
                    foreach ($value as $circle) {
                        $perms .= '<span class="btn btn-success btn-sm tbl-span">' . $circle->name . '</span>&nbsp;';
                        // $perms .= '<span class="badge badge-success">' . $role->name . '</span>&nbsp;';
                    }
                    return $perms;
                }
            ],
            [
                'data' => 'created_at', 'label' => 'Created At', 'order' => true,
                'func' => function ($value) {
                    return $value->diffForHumans();
                }
            ],
            [
                'data' => 'updated_at', 'label' => 'Updated At', 'order' => true,
                'func' => function ($value) {
                    return $value->diffForHumans();
                }
            ],
            [
                'data' => 'id', 'label' => 'Actions', 'order' => false, 'width' => '60px', 'style' => 'text-align: center;',
                'func' => function ($value) {
                    $showAction = true;
                    $editAction = true;
                    $deletAction = true;
                    return view('components.datatable-actions', compact('editAction', 'deletAction', 'value'));
                }
            ]
        ];
    }

    public function render()
    {

        return view('livewire.dashboard.users', [
            'results' => $this->results,
            'headers' => $this->headerConfig(),
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
        $user->circles()->sync($this->selectedCircles);
        session()->flash('success', 'Data has been created successfully.');
        $this->resetInputFields();
    }

    public function edit(User $user)
    {
        $this->updateState = true;
        $this->viewState = false;
        $this->state = $user->toArray();
        $this->user = $user;
        $this->roles = Role::where('guard_name', 'web')->orderBy('name', 'asc')->get();
        $this->selectedRoles = $user->roles()->pluck('id')->map(fn ($item) => (string) $item)->toArray();
        $this->circles = Circle::orderBy('id', 'asc')->get();
        $this->selectedCircles = $user->circles()->pluck('id')->map(fn ($item) => (string) $item)->toArray();
        // dd($this->user);
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
        $this->user->circles()->sync($this->selectedCircles);
        session()->flash('success', 'Data has been updated successfully.');
        $this->resetInputFields();
    }

    public function confirmDelete($id)
    {
        $this->authorizeRoleOrPermission('User Delete');
        $this->dispatchBrowserEvent('swal:confirm', ['id' => $id]);
    }

    public function destroy(User $user)
    {
        $user->delete();
        session()->flash('success', 'Data has been deleted successfully.');
        $this->resetInputFields();
    }

    private function resetInputFields()
    {
        $this->reset(['state', 'createState', 'updateState', 'viewState', 'user', 'roles', 'selectedRoles']);
        $this->resetErrorBag();
    }

    public function getResultsProperty()
    {
        return $this->resultsQuery
            ->when($this->perPage != -1, function ($q) {
                return $q->paginate($this->perPage);
            }, function ($q) {
                return $q->paginate($q->count());
            });
    }

    public function getResultsQueryProperty()
    {
        return User::with(['roles' => function ($query) {
            $query->where('guard_name', 'web')->orderBy('name', 'asc');
        }, 'circles' => function ($query) {
            $query->orderBy('id', 'asc');
        }])
            ->search(trim($this->search))
            ->orderBy($this->sortColumn, $this->sortDirection);
    }

    public function create()
    {
        $this->roles = Role::where('guard_name', 'web')->orderBy('name', 'asc')->get();
        $this->circles = Circle::orderBy('id', 'asc')->get();
        $this->createState = true;
        $this->viewState = false;
    }

    public function cancel()
    {
        $this->resetInputFields();
    }
}
