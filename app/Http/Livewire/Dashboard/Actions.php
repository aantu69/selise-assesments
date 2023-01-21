<?php

namespace App\Http\Livewire\Dashboard;

use App\Http\Livewire\Traits\SearchSort;
use App\Models\Action;
use App\Models\ActionType;
use App\Models\Demand;
use Livewire\Component;
use Illuminate\Support\Facades\Validator;
use App\Traits\AuthorizesRoleOrPermission;

class Actions extends Component
{
    use AuthorizesRoleOrPermission;
    use SearchSort;

    public Demand $demand;

    public $title = 'Actions';
    public $createButton = true;
    public $perPageOptions = ['10' => '10', '25' => '25', '50' => '50', 'All' => '-1'];

    public $viewState = true;
    public $createState = false;
    public $updateState = false;

    public $state = [], $roles, $selectedRoles = [], $action_types;

    protected $listeners = ['destroy', 'store'];

    protected $rules = [
        'action_date' => 'required',
        'action_type_id' => 'required',
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
                'data' => 'demand', 'label' => __('label.tin'), 'order' => true,
                'func' => function ($value) {
                    return $value->taxpayer->tin;
                }
            ],
            [
                'data' => 'demand', 'label' => __('label.name'), 'order' => true,
                'func' => function ($value) {
                    return $value->taxpayer->name;
                }
            ],
            [
                'data' => 'demand', 'label' => __('label.tax_year'), 'order' => true,
                'func' => function ($value) {
                    return $value->tax_year;
                }
            ],
            [
                'data' => 'demand', 'label' => __('label.demand_date'), 'order' => true,
                'func' => function ($value) {
                    return $value->demand_date;
                }
            ],
            [
                'data' => 'demand', 'label' => __('label.corresponding_section'), 'order' => true,
                'func' => function ($value) {
                    return $value->corresponding_section;
                }
            ],
            [
                'data' => 'demand', 'label' => __('label.demand_amount'), 'order' => true,
                'func' => function ($value) {
                    return $value->demand_amount;
                }
            ],

            [
                'data' => 'action_date', 'label' => __('label.action_date'), 'order' => true,
                'func' => function ($value) {
                    return $value;
                }
            ],
            [
                'data' => 'comments', 'label' => __('label.comments'), 'order' => true,
                'func' => function ($value) {
                    return $value;
                }
            ],
            [
                'data' => 'action_type', 'label' => __('label.action_type_id'), 'order' => true,
                'func' => function ($value) {
                    return $value->name;
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

    public function mount()
    {

        if (!in_array($this->demand->taxpayer->circle_id, auth()->user()->circles->pluck('id')->map(fn ($item) => (string) $item)->toArray())) {
            // session()->flash('error', 'You don\'t have permission.');
            return redirect()->route('dashboard.taxpayers');
        }
        $this->action_types = ActionType::where('id', '>', 1)->where('id', '<', 10)->get();
    }

    public function render()
    {
        // dd(auth()->user()->getRoleNames()->toArray()[0]);

        return view('livewire.dashboard.actions', [
            'results' => $this->results,
            'headers' => $this->headerConfig(),
            'title' => $this->title
        ])->layout('layouts.dashboard');
    }

    public function confirmSave()
    {
        $this->validateForm();
        $this->dispatchBrowserEvent('swal:confirmSave');
    }

    public function store()
    {
        $this->demand->actions()->create($this->state);
        $this->demand->update([
            'action_type_id' => $this->state['action_type_id']
        ]);
        session()->flash('success', 'Data has been created successfully.');
        $this->resetInputFields();
    }

    public function edit(Action $action)
    {
        if (auth()->user()->getRoleNames()->toArray()[0] == 'Range') {
            session()->flash('error', 'You don\'t have permission to edit this entry.');
            return;
        }
        $this->updateState = true;
        $this->viewState = false;
        $this->state = $action->demand->taxpayer->toArray();
        $this->state['tax_year'] = $action->demand->tax_year;
        $this->state['demand_date'] = $action->demand->demand_date;
        $this->state['corresponding_section'] = $action->demand->corresponding_section;
        $this->state['demand_amount'] = $action->demand->demand_amount;
        $this->state['demand_comments'] = $action->demand->comments;
        $this->state['taxpayer_id'] = $action->demand->taxpayer_id;
        $this->state['action_date'] = $action->action_date;
        $this->state['action_type_id'] = $action->action_type_id;
        $this->state['comments'] = $action->comments;
        $this->action = $action;
    }

    public function update()
    {
        $this->validateForm();
        $this->action->update($this->state);
        $this->demand->update([
            'action_type_id' => $this->state['action_type_id']
        ]);
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

    public function destroy(Action $action)
    {
        $action->delete();
        session()->flash('success', 'Data has been deleted successfully.');
        $this->resetInputFields();
    }

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
        // dd(Demand::with('taxpayer')->where('taxpayer_id', $this->taxpayer->id)->get());
        return Action::with('action_type', 'demand', 'demand.taxpayer', 'demand.payments')
            ->where('demand_id', $this->demand->id)
            ->orderBy('action_date', 'DESC');
    }

    public function create()
    {
        $demand = Demand::with('taxpayer')->find($this->demand->id);
        $this->state['tin'] = $demand->taxpayer->tin;
        $this->state['name'] = $demand->taxpayer->name;
        $this->state['type'] = $demand->taxpayer->type;
        $this->state['address'] = $demand->taxpayer->address;
        $this->state['contact_name'] = $demand->taxpayer->contact_name;
        $this->state['contact_phone'] = $demand->taxpayer->contact_phone;
        $this->state['tax_year'] = $demand->tax_year;
        $this->state['demand_date'] = $demand->demand_date;
        $this->state['corresponding_section'] = $demand->corresponding_section;
        $this->state['demand_amount'] = $demand->demand_amount;
        $this->state['demand_comments'] = $demand->comments;
        $this->state['contact_phone'] = $demand->taxpayer->contact_phone;
        $this->createState = true;
        $this->viewState = false;
    }

    public function cancel()
    {
        $this->resetInputFields();
    }
}
