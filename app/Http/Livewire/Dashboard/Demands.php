<?php

namespace App\Http\Livewire\Dashboard;

use Carbon\Carbon;
use App\Models\Demand;
use Livewire\Component;
use App\Models\Taxpayer;
use App\Models\ActionType;
use App\Http\Livewire\Traits\SearchSort;
use Illuminate\Support\Facades\Validator;
use App\Traits\AuthorizesRoleOrPermission;

class Demands extends Component
{
    use AuthorizesRoleOrPermission;
    use SearchSort;

    public Taxpayer $taxpayer;
    public $status, $stage;

    public $title = 'Demand';
    public $createButton = true;
    public $perPageOptions = ['5' => '5', '10' => '10', '25' => '25', '50' => '50', 'All' => '-1'];

    public $viewState = true;
    public $createState = false;
    public $updateState = false;

    public $state = [], $newDemand = true, $action_types;
    public $storePayment = false, $storeAction = false, $storeCorrection = false;
    public $start_date, $end_date, $previous_start_date, $previous_end_date, $disputed_actions, $undisputed_actions;

    protected $listeners = ['destroy', 'store'];

    protected $rules = [
        'tax_year' => 'required',
        'demand_date' => 'required',
        'corresponding_section' => 'required',
        'demand_amount' => 'required',
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
                'data' => 'taxpayer', 'label' => __('label.tin'), 'order' => true,
                'func' => function ($value) {
                    return $value->tin;
                }
            ],
            [
                'data' => 'taxpayer', 'label' => __('label.name'), 'order' => true,
                'func' => function ($value) {
                    return $value->name;
                }
            ],
            [
                'data' => 'taxpayer', 'label' => __('label.type'), 'order' => true,
                'func' => function ($value) {
                    return $value->type;
                }
            ],
            [
                'data' => 'tax_year', 'label' => __('label.tax_year'), 'order' => true,
                'func' => function ($value) {
                    return $value;
                }
            ],
            [
                'data' => 'demand_date', 'label' => __('label.demand_date'), 'order' => true,
                'func' => function ($value) {
                    return $value;
                }
            ],
            [
                'data' => 'corresponding_section', 'label' => __('label.corresponding_section'), 'order' => true,
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
                'data' => 'demand_amount', 'label' => __('label.demand_amount'), 'order' => true, 'style' => 'text-align: right;', 'totals' => 'demand_amounts',
                'func' => function ($value) {
                    return $value;
                }
            ],
            [
                'data' => 'corrected_amount', 'label' => __('label.corrected_amount'), 'order' => true, 'style' => 'text-align: right;', 'totals' => 'corrected_amounts',
                'func' => function ($value) {
                    return $value;
                }
            ],
            [
                'data' => 'payments_sum_paid_amount', 'label' => __('label.paid_amount'), 'order' => true, 'style' => 'text-align: right;', 'totals' => 'paid_amounts',
                'func' => function ($value) {
                    return $value / 100;
                }
            ],
            [
                'data' => 'due_amount', 'label' => __('label.due_amount'), 'order' => true, 'style' => 'text-align: right;', 'totals' => 'due_amounts',
                'func' => function ($value) {
                    return $value / 100;
                }
            ],
            [
                'data' => 'actionType', 'label' => __('label.action_type_id'), 'order' => true,
                'func' => function ($value) {
                    $action = $value != null ? (in_array($value->name, ['পরিশোধ', 'সংশোধন']) ? '' : $value->name) : '';
                    // $action = $value != null ? $value->action_type->name : '';
                    // $action = count($value) > 0 ? $value[0]->action_type->name : '';
                    return $action;
                }
            ],
            // [
            //     'data' => 'latestAction', 'label' => __('label.action_type_id'), 'order' => true,
            //     'func' => function ($value) {
            //         $action = $value != null ? ($value->action_type_name == 'Nothing' ? '' : $value->action_type_name) : '';
            //         // $action = $value != null ? $value->action_type->name : '';
            //         // $action = count($value) > 0 ? $value[0]->action_type->name : '';
            //         return $action;
            //     }
            // ],
            [
                'data' => 'id', 'label' => 'Actions', 'order' => false, 'width' => '60px', 'style' => 'text-align: center;',
                'func' => function ($value) {
                    $showPayments = true;
                    $createPayment = true;
                    $showActions = true;
                    $createAction = true;
                    $showCorrections = true;
                    $createCorrection = true;
                    $showAction = true;
                    $editAction = true;
                    $deletAction = true;
                    return view('components.datatable-actions', compact('showPayments', 'createPayment', 'showActions', 'createAction', 'showCorrections', 'createCorrection', 'editAction', 'deletAction', 'value'));
                }
            ]
        ];
    }

    public function mount($taxpayer, $status = null, $stage = null)
    {
        $this->taxpayer = $taxpayer;
        $this->status = $status == 'all' ? null : $status;
        $this->stage = $stage == 'all' ? null : $stage;

        if (!in_array($this->taxpayer->circle_id, auth()->user()->circles->pluck('id')->map(fn ($item) => (string) $item)->toArray())) {
            return redirect()->route('dashboard.taxpayers');
        }
        $this->action_types = ActionType::where('id', '>', 1)->where('id', '<', 10)->get();

        $this->year = date('Y');
        $this->month = date('m') - 1;

        if (
            $this->month < 7
        ) {
            $this->previous_start_date = ($this->year - 2) . '-07-01';
            $this->start_date = ($this->year - 1) . '-07-01';
        } else {
            $this->previous_start_date = ($this->year - 1) . '-07-01';
            $this->start_date = $this->year . '-07-01';
        }

        $this->end_date = Carbon::now()->startOfMonth()->endOfMonth()->toDateString();
        $this->previous_end_date = Carbon::now()->subYear()->startOfMonth()->endOfMonth()->toDateString();

        $this->disputed_actions = ActionType::where('disputed', 1)->pluck('id')->map(fn ($item) => (string) $item)->toArray();
        $this->undisputed_actions = ActionType::where('disputed', 0)->pluck('id')->map(fn ($item) => (string) $item)->toArray();
    }

    public function render()
    {
        // dd(auth()->user()->getRoleNames()->toArray()[0]);
        // dd($this->results);
        return view('livewire.dashboard.demands', [
            'results' => $this->results,
            'headers' => $this->headerConfig(),
            'title' => $this->title
        ])->layout('layouts.dashboard');
    }

    public function confirmSave()
    {
        $this->dispatchBrowserEvent('swal:confirmSave');
    }

    public function store()
    {
        if ($this->newDemand) {
            $this->validateForm();
            $this->taxpayer->demands()->create($this->state);
        } else {
            if ($this->storePayment) {
                $this->rules = array_merge($this->rules, [
                    'paid_date' => 'required',
                    'paid_amount' => 'required',
                    'payment_detail' => 'required',
                ]);
                $this->validateForm();
                $this->state['comments'] = $this->state['payment_comments'];
                $this->demand->payments()->create($this->state);

                $this->state['action_date'] = $this->state['paid_date'];
                $this->state['action_type_id'] = 10;
                $this->demand->actions()->create($this->state);
                $this->demand->update([
                    'paid_amount' => Demand::withSum('payments', 'paid_amount')->find($this->demand->id)->payments_sum_paid_amount,
                    // 'action_type_id' => null,
                    'action_type_id' => $this->demand->latestAction->action_type_id,
                ]);

                // $this->demand->update([
                //     'paid_amount' => Demand::withSum('payments', 'paid_amount')->find($this->demand->id)->payments_sum_paid_amount
                // ]);
            } elseif ($this->storeAction) {
                $this->rules = array_merge($this->rules, [
                    'action_date' => 'required',
                    'action_type_id' => 'required'
                ]);
                $this->validateForm();
                $this->state['comments'] = $this->state['action_comments'];
                $this->demand->actions()->create($this->state);
                // dd($this->demand->latestAction->action_type_id);
                $this->demand->update([
                    // 'action_type_id' => $this->state['action_type_id']
                    'action_type_id' => $this->demand->latestAction->action_type_id
                ]);
            } elseif ($this->storeCorrection) {
                $this->rules = array_merge($this->rules, [
                    'correction_date' => 'required',
                    'correction_amount' => 'required',
                    'correction_section' => 'required',
                ]);
                $this->validateForm();
                $this->state['comments'] = $this->state['correction_comments'];
                $this->demand->corrections()->create($this->state);

                $this->state['action_date'] = $this->state['correction_date'];
                $this->state['action_type_id'] = 11;
                $this->demand->actions()->create($this->state);
                $this->demand->update([
                    'corrected_amount' => $this->state['correction_amount'],
                    // 'action_type_id' => null,
                    'action_type_id' => $this->demand->latestAction->action_type_id,
                ]);

                // $this->demand->update([
                //     'corrected_amount' => $this->state['correction_amount']
                // ]);
            }
        }

        session()->flash('success', 'Data has been created successfully.');
        $this->resetInputFields();
    }

    public function edit(Demand $demand)
    {
        if (auth()->user()->getRoleNames()->toArray()[0] == 'Range') {
            session()->flash('error', 'You don\'t have permission to edit this entry.');
            return;
        }
        $this->updateState = true;
        $this->viewState = false;
        $this->state = $this->taxpayer->toArray();
        $this->state['tax_year'] = $demand->tax_year;
        $this->state['demand_date'] = $demand->demand_date;
        $this->state['corresponding_section'] = $demand->corresponding_section;
        $this->state['demand_amount'] = $demand->demand_amount;
        $this->state['comments'] = $demand->comments;
        $this->state['taxpayer_id'] = $demand->taxpayer_id;
        $this->demand = $demand;
    }

    public function update()
    {
        $roles = array("Commissioner", "Admin");
        $this->validateForm();
        if (in_array(auth()->user()->getRoleNames()->toArray()[0], $roles)) {
            $this->demand->update($this->state);
        } else {
            $this->demand->update(
                [
                    'tax_year' => $this->state['tax_year'],
                    'demand_date' => $this->state['demand_date'],
                    'corresponding_section' => $this->state['corresponding_section']
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

    public function destroy(Demand $demand)
    {
        $demand->payments()->delete();
        $demand->delete();
        session()->flash('success', 'Data has been deleted successfully.');
        $this->resetInputFields();
    }

    private function resetInputFields()
    {
        $this->reset(['state', 'createState', 'updateState', 'viewState', 'newDemand', 'storePayment', 'storeAction', 'storeCorrection', 'title']);
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
        $status = $this->status;
        $stage = $this->stage;
        $disputed_actions = $this->disputed_actions;
        $undisputed_actions = $this->undisputed_actions;
        return Demand::with('taxpayer', 'actionType')
            ->withSum('payments', 'paid_amount')
            ->selectRaw('((case when demands.corrected_amount = 0 then demands.demand_amount else demands.corrected_amount end) - paid_amount) as due_amount')
            ->where('taxpayer_id', $this->taxpayer->id)
            ->when($status, function ($query) use ($status) {
                $status == 'due'
                    ? $query->whereNotBetween('demand_date', [$this->start_date, $this->end_date])
                    : $query->whereBetween('demand_date', [$this->start_date, $this->end_date]);
            })
            ->when($stage, function ($query) use ($stage, $disputed_actions, $undisputed_actions) {
                $stage == 'disputed'
                    ? $query->whereIn('action_type_id', $disputed_actions)
                    : $query->where(function ($query) use ($undisputed_actions) {
                        $query->whereNull('action_type_id')
                            ->orWhereIn('action_type_id', $undisputed_actions);
                    });
            })
            ->orderBy($this->sortColumn, $this->sortDirection);
    }

    public function create()
    {
        $this->state['tin'] = $this->taxpayer->tin;
        $this->state['name'] = $this->taxpayer->name;
        $this->state['type'] = $this->taxpayer->type;
        $this->state['address'] = $this->taxpayer->address;
        $this->state['contact_name'] = $this->taxpayer->contact_name;
        $this->state['contact_phone'] = $this->taxpayer->contact_phone;
        $this->createState = true;
        $this->viewState = false;
    }

    public function createPayment(Demand $demand)
    {
        if (auth()->user()->getRoleNames()->toArray()[0] != 'Circle') {
            session()->flash('error', 'You don\'t have permission to create payment.');
            return;
        }
        $this->createState = true;
        $this->viewState = false;
        $this->demand = $demand;
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
        $this->state['comments'] = $demand->comments;
        $this->state['payment_comments'] = '';
        $this->newDemand = false;
        $this->storePayment = true;
        $this->title = 'Payment';
    }

    public function createAction(Demand $demand)
    {
        if (auth()->user()->getRoleNames()->toArray()[0] != 'Circle') {
            session()->flash('error', 'You don\'t have permission to create action.');
            return;
        }
        $this->createState = true;
        $this->viewState = false;
        $this->demand = $demand;
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
        $this->state['comments'] = $demand->comments;
        $this->state['action_comments'] = '';
        $this->newDemand = false;
        $this->storeAction = true;
        $this->title = 'Action';
    }

    public function createCorrection(Demand $demand)
    {
        if (auth()->user()->getRoleNames()->toArray()[0] != 'Circle') {
            session()->flash('error', 'You don\'t have permission to create correction.');
            return;
        }
        $this->createState = true;
        $this->viewState = false;
        $this->demand = $demand;
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
        $this->state['comments'] = $demand->comments;
        $this->state['correction_comments'] = '';
        $this->newDemand = false;
        $this->storeCorrection = true;
        $this->title = 'Correction';
    }

    public function cancel()
    {
        $this->resetInputFields();
    }
}
