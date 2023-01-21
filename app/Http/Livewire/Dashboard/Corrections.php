<?php

namespace App\Http\Livewire\Dashboard;

use App\Http\Livewire\Traits\SearchSort;
use App\Models\Correction;
use App\Models\Demand;
use Livewire\Component;
use Illuminate\Support\Facades\Validator;
use App\Traits\AuthorizesRoleOrPermission;

class Corrections extends Component
{
    use AuthorizesRoleOrPermission;
    use SearchSort;

    public Demand $demand;

    public $title = 'Corrections';
    public $createButton = true;
    public $perPageOptions = ['10' => '10', '25' => '25', '50' => '50', 'All' => '-1'];

    public $viewState = true;
    public $createState = false;
    public $updateState = false;

    public $state = [], $correction;

    protected $listeners = ['destroy', 'store'];

    protected $rules = [
        'correction_date' => 'required',
        'correction_amount' => 'required',
        'correction_section' => 'required',
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
                'data' => 'correction_section', 'label' => __('label.correction_section'), 'order' => true,
                'func' => function ($value) {
                    return $value;
                }
            ],
            [
                'data' => 'correction_date', 'label' => __('label.correction_date'), 'order' => true,
                'func' => function ($value) {
                    return $value;
                }
            ],
            [
                'data' => 'correction_amount', 'label' => __('label.correction_amount'), 'order' => true,
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
    }

    public function render()
    {
        // dd(auth()->user()->getRoleNames()->toArray()[0]);

        return view('livewire.dashboard.corrections', [
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
        $this->demand->corrections()->create($this->state);
        $this->state['action_date'] = $this->state['correction_date'];
        $this->state['action_type_id'] = 11;
        $this->demand->actions()->create($this->state);
        $this->demand->update([
            'corrected_amount' => $this->state['correction_amount'],
            // 'action_type_id' => null,
            'action_type_id' => $this->demand->latestAction->action_type_id
        ]);
        session()->flash('success', 'Data has been created successfully.');
        $this->resetInputFields();
    }

    public function edit(Correction $correction)
    {
        if (auth()->user()->getRoleNames()->toArray()[0] == 'Range') {
            session()->flash('error', 'You don\'t have permission to edit this entry.');
            return;
        }
        $this->updateState = true;
        $this->viewState = false;
        $this->state = $correction->demand->taxpayer->toArray();
        $this->state['tax_year'] = $correction->demand->tax_year;
        $this->state['demand_date'] = $correction->demand->demand_date;
        $this->state['corresponding_section'] = $correction->demand->corresponding_section;
        $this->state['demand_amount'] = $correction->demand->demand_amount;
        $this->state['demand_comments'] = $correction->demand->comments;
        $this->state['taxpayer_id'] = $correction->demand->taxpayer_id;
        $this->state['correction_date'] = $correction->correction_date;
        $this->state['correction_amount'] = $correction->correction_amount;
        $this->state['correction_section'] = $correction->correction_section;
        $this->state['comments'] = $correction->comments;
        $this->correction = $correction;
    }

    public function update()
    {
        $roles = array("Commissioner", "Admin");
        $this->validateForm();
        if (in_array(auth()->user()->getRoleNames()->toArray()[0], $roles)) {
            $this->correction->update($this->state);
            $this->demand->update([
                'corrected_amount' => $this->state['correction_amount']
            ]);
        } else {
            $this->correction->update(
                [
                    'correction_date' => $this->state['correction_date'],
                    'correction_section' => $this->state['correction_section'],
                    'comments' => $this->state['comments']
                ]
            );
        }
        session()->flash('success', 'Data has been updated successfully.');
        $this->resetInputFields();
    }

    public function confirmDelete($id)
    {
        $roles = array("Commissioner", "Admin");
        if (!in_array(auth()->user()->getRoleNames()->toArray()[0], $roles)) {
            session()->flash('error', 'You don\'t have permission to delete this entry.');
            return;
        }
        // $this->authorizeRoleOrPermission('User Delete');
        $this->dispatchBrowserEvent('swal:confirm', ['id' => $id]);
    }

    public function destroy(Correction $correction)
    {
        $correction->delete();
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
        return Correction::with('demand', 'demand.taxpayer')
            ->where('demand_id', $this->demand->id)
            ->orderBy($this->sortColumn, $this->sortDirection);
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
