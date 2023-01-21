<?php

namespace App\Http\Livewire\Dashboard;

use App\Http\Livewire\Traits\SearchSort;
use Livewire\Component;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Validator;
use App\Traits\AuthorizesRoleOrPermission;

class UserRoles extends Component
{
    use AuthorizesRoleOrPermission;
    use SearchSort;
    public $title = 'Role';
    public $createButton = true;

    public $viewState = true;
    public $createState = false;
    public $updateState = false;

    public $state = [], $role, $permissions, $selectedPermissions = [];

    protected $listeners = ['destroy'];

    protected $rules = [
        'name' => 'required',
    ];

    protected $messages = [
        'selectedPermissions.required' => 'Please select atleast one permission.',
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
                'data' => 'permissions', 'label' => 'Permissions', 'order' => false,
                'func' => function ($value) {
                    $perms = '';
                    foreach ($value as $permission) {
                        $perms .= '<span class="btn btn-success btn-sm tbl-span">' . $permission->name . '</span>&nbsp;';
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
        return view('livewire.dashboard.user-roles', [
            'results' => $this->results,
            'headers' => $this->headerConfig(),
            'title' => $this->title
        ])->layout('layouts.dashboard');
    }

    public function store()
    {
        $this->state['guard_name'] = 'web';
        $this->state['selectedPermissions'] = $this->selectedPermissions;
        $this->rules = array_merge($this->rules, [
            'selectedPermissions' => 'required|array|min:1',
        ]);
        $this->validateForm();
        $role = Role::create($this->state);
        $role->syncPermissions($this->selectedPermissions);
        session()->flash('success', 'Data has been created successfully.');
        $this->resetInputFields();
    }

    public function edit(Role $role)
    {
        $this->updateState = true;
        $this->viewState = false;
        $this->state = $role->toArray();
        $this->role = $role;
        $this->permissions = Permission::orderBy('name', 'asc')->where('guard_name', 'web')->get();
        $this->selectedPermissions = $role->permissions()->pluck('id')->map(fn ($item) => (string) $item)->toArray();
    }

    public function update()
    {
        $this->state['selectedPermissions'] = $this->selectedPermissions;
        $this->rules = array_merge($this->rules, [
            'selectedPermissions' => 'required|array|min:1',
        ]);
        $this->validateForm();
        $this->role->update($this->state);
        $this->role->syncPermissions($this->selectedPermissions);
        session()->flash('success', 'Data has been updated successfully.');
        $this->resetInputFields();
    }

    public function confirmDelete($id)
    {
        $this->authorizeRoleOrPermission('Role Delete');
        $this->dispatchBrowserEvent('swal:confirm', ['id' => $id]);
    }

    public function destroy(Role $role)
    {
        $role->delete();
        session()->flash('success', 'Data has been deleted successfully.');
        $this->resetInputFields();
    }

    private function resetInputFields()
    {
        $this->reset(['state', 'createState', 'updateState', 'viewState', 'role', 'permissions', 'selectedPermissions']);
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
        return Role::where('guard_name', 'web')
            ->with(['permissions' => function ($query) {
                $query->orderBy('name', 'asc');
            }])
            ->where(function ($query) {
                if ($this->search != "") {
                    $query->where('name', 'like', '%' . $this->search . '%');
                }
            })
            ->orderBy($this->sortColumn, $this->sortDirection);
    }

    public function create()
    {
        $this->permissions = Permission::orderBy('name', 'asc')->where('guard_name', 'web')->get();
        $this->createState = true;
        $this->viewState = false;
    }

    public function cancel()
    {
        $this->resetInputFields();
    }
}
