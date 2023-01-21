<?php

namespace App\Http\Livewire\Dashboard;

use App\Http\Livewire\Traits\SearchSort;
use App\Models\Action;
use App\Models\ActionType;
use App\Models\Circle;
use App\Models\Demand;
use Livewire\Component;
use Illuminate\Support\Facades\Validator;
use App\Traits\AuthorizesRoleOrPermission;

class Circles extends Component
{
    use AuthorizesRoleOrPermission;
    use SearchSort;

    public $title = 'Circles';
    public $createButton = false;
    public $perPageOptions = ['10' => '10', '25' => '25', '50' => '50', 'All' => '-1'];

    public $viewState = true;
    public $createState = false;
    public $updateState = false;

    public $state = [], $circle;

    protected $listeners = ['destroy'];

    protected $rules = [
        'name' => 'required',
        'name_bn' => 'required',
        'address' => 'required',
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
                'data' => 'name_bn', 'label' => 'Name (Bangla)', 'order' => true,
                'func' => function ($value) {
                    return $value;
                }
            ],
            [
                'data' => 'address', 'label' => 'Address', 'order' => true,
                'func' => function ($value) {
                    return $value;
                }
            ],
            [
                'data' => 'id', 'label' => 'Actions', 'order' => false, 'width' => '60px', 'style' => 'text-align: center;',
                'func' => function ($value) {
                    $showAction = true;
                    $editAction = true;
                    $deletAction = false;
                    return view('components.datatable-actions', compact('editAction', 'deletAction', 'value'));
                }
            ]
        ];
    }

    public function render()
    {
        return view('livewire.dashboard.circles', [
            'results' => $this->results,
            'headers' => $this->headerConfig(),
            'title' => $this->title
        ])->layout('layouts.dashboard');
    }

    // public function store()
    // {
    //     $this->validateForm();
    //     $user = Circle::create($this->state);
    //     session()->flash('success', 'Data has been created successfully.');
    //     $this->resetInputFields();
    // }

    public function edit(Circle $circle)
    {
        if (auth()->user()->getRoleNames()->toArray()[0] == 'Range') {
            session()->flash('error', 'You don\'t have permission to edit this entry.');
            return;
        }
        $this->updateState = true;
        $this->viewState = false;
        $this->state = $circle->toArray();
        $this->circle = $circle;
    }

    public function update()
    {
        $this->validateForm();
        $this->circle->update(['address' => $this->state['address']]);
        session()->flash('success', 'Data has been updated successfully.');
        $this->resetInputFields();
    }

    public function confirmDelete($id)
    {
        if (auth()->user()->getRoleNames()->toArray()[0] != 'Circle') {
            session()->flash('error', 'You don\'t have permission to delete this entry.');
            return;
        }
        // $this->authorizeRoleOrPermission('User Delete');
        $this->dispatchBrowserEvent('swal:confirm', ['id' => $id]);
    }

    // public function destroy(Circle $circle)
    // {
    //     $circle->delete();
    //     session()->flash('success', 'Data has been deleted successfully.');
    //     $this->resetInputFields();
    // }

    private function resetInputFields()
    {
        $this->reset(['state', 'createState', 'updateState', 'viewState']);
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
        return Circle::whereIn('id', auth()->user()->circles->pluck('id')->map(fn ($item) => (string) $item)->toArray())
            ->search(trim($this->search))
            ->orderBy($this->sortColumn, $this->sortDirection);
    }

    // public function create()
    // {
    //     $this->createState = true;
    //     $this->viewState = false;
    // }

    public function cancel()
    {
        $this->resetInputFields();
    }
}
