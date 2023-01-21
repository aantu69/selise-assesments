<?php

namespace App\Http\Livewire\Dashboard\Users;

use App\Http\Livewire\Traits\SearchSort;
use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;
use App\Traits\AuthorizesRoleOrPermission;

class Index extends Component
{
    use AuthorizesRoleOrPermission;
    use WithPagination;
    use SearchSort;

    protected $paginationTheme = 'bootstrap';
    public $title = 'User';
    public $createButton = true;
    public $crudRoutePart = 'dashboard.users';


    protected $listeners = ['destroy'];

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
                    $crudRoutePart = $this->crudRoutePart;
                    return view('components.datatable-actions', compact('editAction', 'deletAction', 'value', 'crudRoutePart'));
                }
            ]
        ];
    }

    public function render()
    {
        return view('livewire.dashboard.users.index', [
            'results' => $this->results,
            'headers' => $this->headerConfig(),
            'title' => $this->title
        ])->layout('layouts.dashboard');
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
            $query->orderBy('name', 'asc')->where('guard_name', 'web');
        }])
            ->search(trim($this->search))
            ->orderBy($this->sortColumn, $this->sortDirection);
    }

    public function confirmDelete($id)
    {
        // $this->authorizeRoleOrPermission('User Delete');
        $this->dispatchBrowserEvent('swal:confirm', ['id' => $id]);
    }

    public function destroy(User $user)
    {
        $user->delete();
        session()->flash('success', 'Data has been deleted successfully.');
    }
}
