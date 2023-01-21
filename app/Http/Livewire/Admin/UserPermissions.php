<?php

namespace App\Http\Livewire\Admin;

use App\Http\Livewire\Traits\SearchSort;
use Livewire\Component;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Validator;
use App\Traits\AuthorizesRoleOrPermission;

class UserPermissions extends Component
{
    use AuthorizesRoleOrPermission;
    use SearchSort;
    public $title = 'Permission';
    public $createButton = true;

    public $viewState = true;
    public $createState = false;
    public $updateState = false;

    public $state = [], $permission;

    protected $listeners = ['destroy'];

    protected $rules = [
        'name' => 'required'
    ];

    protected $messages = [];

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
                'data' => 'roles', 'label' => 'Roles', 'order' => false,
                'func' => function ($value) {
                    $perms = '';
                    foreach ($value as $role) {
                        $perms .= '<span class="btn btn-success btn-sm tbl-span">' . $role->name . '</span>&nbsp;';
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
        return view('livewire.admin.user-permissions', [
            'results' => $this->results,
            'headers' => $this->headerConfig(),
            'title' => $this->title
        ])->layout('layouts.admin');
    }
    public function store()
    {
        $this->state['guard_name'] = 'web';
        $this->validateForm();
        $permission = Permission::create($this->state);
        session()->flash('success', 'Data has been created successfully.');
        $this->resetInputFields();
    }

    public function edit(Permission $permission)
    {
        $this->updateState = true;
        $this->viewState = false;
        $this->state = $permission->toArray();
        $this->permission = $permission;
    }

    public function update()
    {
        $this->validateForm();
        $this->permission->update($this->state);
        session()->flash('success', 'Data has been updated successfully.');
        $this->resetInputFields();
    }

    public function confirmDelete($id)
    {
        $this->authorizeRoleOrPermission('permission_delete');
        $this->dispatchBrowserEvent('swal:confirm', ['id' => $id]);
    }

    public function destroy(Permission $permission)
    {
        $permission->delete();
        session()->flash('success', 'Data has been deleted successfully.');
        $this->resetInputFields();
    }

    private function resetInputFields()
    {
        $this->reset(['state', 'createState', 'updateState', 'viewState', 'permission']);
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
        return Permission::where('guard_name', 'web')
            ->with('roles')
            ->where(function ($query) {
                if ($this->search != "") {
                    $query->where('name', 'like', '%' . $this->search . '%');
                }
            })
            ->orderBy($this->sortColumn, $this->sortDirection);
    }

    public function create()
    {
        $this->createState = true;
        $this->viewState = false;
    }

    public function cancel()
    {
        $this->resetInputFields();
    }
}
