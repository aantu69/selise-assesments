<?php

namespace App\Http\Livewire\Dashboard;

use Carbon\Carbon;
use App\Models\Demand;
use App\Models\Payment;
use Livewire\Component;
use App\Models\Taxpayer;
use App\Models\ActionType;
use Illuminate\Support\Facades\DB;
use App\Http\Livewire\Traits\SearchSort;
use Illuminate\Support\Facades\Validator;
use App\Traits\AuthorizesRoleOrPermission;

class Taxpayers extends Component
{
    use AuthorizesRoleOrPermission;
    use SearchSort;
    public $title = 'Taxpayer';
    public $createButton = true;
    public $perPageOptions = ['10' => '10', '25' => '25', '50' => '50', 'All' => '-1'];

    public $viewState = true;
    public $createState = false;
    public $updateState = false;

    public $state = [], $circles, $selectedCircle, $selectedStatus, $selectedAction, $newTaxpayer = true, $roles, $selectedRoles = [];

    public $start_date, $end_date, $previous_start_date, $previous_end_date, $disputed_actions, $undisputed_actions;

    protected $listeners = ['destroy', 'store'];

    protected $rules = [
        'tin' => 'required',
        'name' => 'required',
        'address' => 'required',
        'contact_name' => 'required',
        'contact_phone' => 'required',
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
                'data' => 'tin', 'label' => __('label.tin'), 'order' => true,
                'func' => function ($value) {
                    return $value;
                }
            ],
            [
                'data' => 'name', 'label' => __('label.name'), 'order' => true,
                'func' => function ($value) {
                    return $value;
                }
            ],
            [
                'data' => 'type', 'label' => __('label.type'), 'order' => true,
                'func' => function ($value) {
                    return $value;
                }
            ],
            [
                'data' => 'address', 'label' => __('label.address'), 'order' => true,
                'func' => function ($value) {
                    return $value;
                }
            ],
            [
                'data' => 'contact_name', 'label' => __('label.contact_name'), 'order' => true,
                'func' => function ($value) {
                    return $value;
                }
            ],
            [
                'data' => 'contact_phone', 'label' => __('label.contact_phone'), 'order' => true,
                'func' => function ($value) {
                    return $value;
                }
            ],
            [
                'data' => 'demands_count', 'label' => __('label.demands_count'), 'order' => true,
                'func' => function ($value) {
                    return $value;
                }
            ],
            [
                'data' => 'demands_sum_demand_amount', 'label' => __('label.demand_amount'), 'order' => true,
                'func' => function ($value) {
                    return $value / 100;
                }
            ],
            [
                'data' => 'corrected_amount', 'label' => __('label.correction_amount'), 'order' => true,
                'func' => function ($value) {
                    return $value / 100;
                }
            ],
            [
                'data' => 'paid_amount', 'label' => __('label.paid_amount'), 'order' => true,
                'func' => function ($value) {
                    return $value / 100;
                }
            ],
            [
                'data' => 'due_amount', 'label' => __('label.due_amount'), 'order' => true,
                'func' => function ($value) {
                    return $value / 100;
                }
            ],
            [
                'data' => 'id', 'label' => 'Actions', 'order' => false, 'width' => '60px', 'style' => 'text-align: center;',
                'func' => function ($value) {
                    $showDemands = true;
                    $createDemand = true;
                    $showAction = true;
                    $editAction = true;
                    $deletAction = true;
                    $noticeAction = true;
                    $status = $this->selectedStatus ?? 'all';
                    $stage = $this->selectedAction ?? 'all';
                    // dd($stage);
                    return view('components.datatable-actions', compact('showDemands', 'createDemand', 'editAction', 'deletAction', 'noticeAction', 'value', 'status', 'stage'));
                }
            ]
        ];
    }

    public function mount()
    {
        // dd(auth()->user()->circles->pluck('id')->map(fn ($item) => (string) $item)->toArray());
        if (auth()->user()->getRoleNames()->toArray()[0] != 'Circle') {
            $this->circles = auth()->user()->circles;
        }

        $this->year = date('Y');
        $this->month = date('m');

        if ($this->month < 7) {
            $this->previous_start_date = ($this->year - 2) . '-07-01';
            $this->previous_end_date = ($this->year - 1) . '-06-30';
            $this->start_date = ($this->year - 1) . '-07-01';
            $this->end_date = $this->year . '-06-30';
        } else {
            $this->previous_start_date = ($this->year - 1) . '-07-01';
            $this->previous_end_date = $this->year . '-06-30';
            $this->start_date = $this->year . '-07-01';
            $this->end_date = ($this->year + 1) . '-06-30';
        }

        $this->selectedStatus = 'current';

        // $this->end_date = Carbon::now()->startOfMonth()->endOfMonth()->toDateString();
        // $this->previous_end_date = Carbon::now()->subYear()->startOfMonth()->endOfMonth()->toDateString();

        $this->disputed_actions = ActionType::where('disputed', 1)->pluck('id')->map(fn ($item) => (string) $item)->toArray();
        $this->undisputed_actions = ActionType::where('disputed', 0)->pluck('id')->map(fn ($item) => (string) $item)->toArray();
    }

    public function render()
    {
        // dd(Demand::with('latestAction')->get());
        // dd(Taxpayer::allDemands($this->selectedCircle, $this->selectedStatus, $this->selectedAction, $this->disputed_actions, $this->undisputed_actions, $this->start_date, $this->end_date)->get());
        return view('livewire.dashboard.taxpayers', [
            'results' => $this->results,
            'headers' => $this->headerConfig(),
            'title' => $this->title
        ])->layout('layouts.dashboard');
    }

    public function confirmSave()
    {
        $this->rules = array_merge($this->rules, [
            'tax_year' => 'required',
            'demand_date' => 'required',
            'corresponding_section' => 'required',
            'demand_amount' => 'required',
        ]);
        $this->validateForm();
        $this->dispatchBrowserEvent('swal:confirmSave');
    }

    public function store()
    {
        $this->state['circle_id'] = auth()->user()->circles->first()->id;
        if ($this->newTaxpayer) {
            $taxpayer = Taxpayer::create($this->state);
            $taxpayer->demands()->create($this->state);
        } else {
            $this->taxpayer->demands()->create([
                'tax_year' => $this->state['tax_year'],
                'demand_date' => $this->state['demand_date'],
                'corresponding_section' => $this->state['corresponding_section'],
                'demand_amount' => $this->state['demand_amount'],
            ]);
        }
        session()->flash('success', 'Data has been created successfully.');
        $this->resetInputFields();
    }

    public function edit(Taxpayer $taxpayer)
    {
        if (auth()->user()->getRoleNames()->toArray()[0] == 'Range') {
            session()->flash('error', 'You don\'t have permission to edit this entry.');
            return;
        }
        $this->updateState = true;
        $this->viewState = false;
        $this->state = $taxpayer->toArray();
        $this->taxpayer = $taxpayer;
    }

    public function update()
    {
        $this->validateForm();
        $this->taxpayer->update($this->state);
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

    public function destroy(Taxpayer $taxpayer)
    {
        $taxpayer->payments()->delete();
        $taxpayer->demands()->delete();
        $taxpayer->delete();
        session()->flash('success', 'Data has been deleted successfully.');
        $this->resetInputFields();
    }

    private function resetInputFields()
    {
        $this->reset(['state', 'createState', 'updateState', 'viewState', 'newTaxpayer']);
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
        $selectedCircle = $this->selectedCircle;
        $selectedStatus = $this->selectedStatus;
        $selectedAction = $this->selectedAction;
        $disputed_actions = $this->disputed_actions;
        $undisputed_actions = $this->undisputed_actions;

        // $query = Taxpayer::allDemands($this->selectedCircle, $this->selectedStatus, $this->selectedAction, $this->disputed_actions, $this->undisputed_actions, $this->start_date, $this->end_date)->get();

        $query = Taxpayer::query();

        if ($selectedCircle) {
            $query = $query->where('circle_id', $selectedCircle);
        } else {
            $query = $query->whereIn('circle_id', auth()->user()->circles->pluck('id')->map(fn ($item) => (string) $item)->toArray());
        }

        $query = $query->whereHas('demands', function ($query) use ($selectedStatus, $selectedAction, $disputed_actions, $undisputed_actions) {
            $query->when($selectedStatus, function ($query) use ($selectedStatus) {
                $selectedStatus == 'due'
                    ? $query->whereNotBetween('demand_date', [$this->start_date, $this->end_date])
                    : $query->whereBetween('demand_date', [$this->start_date, $this->end_date]);
            })
                ->when($selectedAction, function ($query) use ($selectedAction, $disputed_actions, $undisputed_actions) {
                    $selectedAction == 'disputed'
                        ? $query->whereIn('action_type_id', $disputed_actions)
                        : $query->where(function ($query) use ($undisputed_actions) {
                            $query->whereNull('action_type_id')
                                ->orWhereIn('action_type_id', $undisputed_actions);
                        });
                });
        });

        $query = $query->addSelect([
            'demands_count' => Demand::whereColumn('taxpayer_id', 'taxpayers.id')
                ->selectRaw('count(id)')
                ->when($selectedStatus, function ($query) use ($selectedStatus) {
                    $selectedStatus == 'due'
                        ? $query->whereNotBetween('demand_date', [$this->start_date, $this->end_date])
                        : $query->whereBetween('demand_date', [$this->start_date, $this->end_date]);
                })
                ->when($selectedAction, function ($query) use ($selectedAction, $disputed_actions, $undisputed_actions) {
                    $selectedAction == 'disputed'
                        ? $query->whereIn('action_type_id', $disputed_actions)
                        : $query->where(function ($query) use ($undisputed_actions) {
                            $query->whereNull('action_type_id')
                                ->orWhereIn('action_type_id', $undisputed_actions);
                        });
                })
        ])
            ->addSelect([
                'demands_sum_demand_amount' => Demand::whereColumn('taxpayer_id', 'taxpayers.id')
                    ->selectRaw('sum(demand_amount)')
                    ->when($selectedStatus, function ($query) use ($selectedStatus) {
                        $selectedStatus == 'due'
                            ? $query->whereNotBetween('demand_date', [$this->start_date, $this->end_date])
                            : $query->whereBetween('demand_date', [$this->start_date, $this->end_date]);
                    })
                    ->when($selectedAction, function ($query) use ($selectedAction, $disputed_actions, $undisputed_actions) {
                        $selectedAction == 'disputed'
                            ? $query->whereIn('action_type_id', $disputed_actions)
                            : $query->where(function ($query) use ($undisputed_actions) {
                                $query->whereNull('action_type_id')
                                    ->orWhereIn('action_type_id', $undisputed_actions);
                            });
                    })
            ])
            ->addSelect([
                'corrected_amount' => Demand::whereColumn('taxpayer_id', 'taxpayers.id')
                    ->selectRaw('sum(case when corrected_amount = 0 then demand_amount else corrected_amount end)')
                    ->when($selectedStatus, function ($query) use ($selectedStatus) {
                        $selectedStatus == 'due'
                            ? $query->whereNotBetween('demand_date', [$this->start_date, $this->end_date])
                            : $query->whereBetween('demand_date', [$this->start_date, $this->end_date]);
                    })
                    ->when($selectedAction, function ($query) use ($selectedAction, $disputed_actions, $undisputed_actions) {
                        $selectedAction == 'disputed'
                            ? $query->whereIn('action_type_id', $disputed_actions)
                            : $query->where(function ($query) use ($undisputed_actions) {
                                $query->whereNull('action_type_id')
                                    ->orWhereIn('action_type_id', $undisputed_actions);
                            });
                    })
            ])
            ->addSelect([
                'paid_amount' => Demand::whereColumn('taxpayer_id', 'taxpayers.id')
                    ->selectRaw('sum(paid_amount)')
                    ->when($selectedStatus, function ($query) use ($selectedStatus) {
                        $selectedStatus == 'due'
                            ? $query->whereNotBetween('demand_date', [$this->start_date, $this->end_date])
                            : $query->whereBetween('demand_date', [$this->start_date, $this->end_date]);
                    })
                    ->when($selectedAction, function ($query) use ($selectedAction, $disputed_actions, $undisputed_actions) {
                        $selectedAction == 'disputed'
                            ? $query->whereIn('action_type_id', $disputed_actions)
                            : $query->where(function ($query) use ($undisputed_actions) {
                                $query->whereNull('action_type_id')
                                    ->orWhereIn('action_type_id', $undisputed_actions);
                            });
                    })
            ])
            ->addSelect([
                'due_amount' => Demand::whereColumn('taxpayer_id', 'taxpayers.id')
                    ->selectRaw('sum(case when corrected_amount = 0 then demand_amount else corrected_amount end) - sum(paid_amount)')
                    ->when($selectedStatus, function ($query) use ($selectedStatus) {
                        $selectedStatus == 'due'
                            ? $query->whereNotBetween('demand_date', [$this->start_date, $this->end_date])
                            : $query->whereBetween('demand_date', [$this->start_date, $this->end_date]);
                    })
                    ->when($selectedAction, function ($query) use ($selectedAction, $disputed_actions, $undisputed_actions) {
                        $selectedAction == 'disputed'
                            ? $query->whereIn('action_type_id', $disputed_actions)
                            : $query->where(function ($query) use ($undisputed_actions) {
                                $query->whereNull('action_type_id')
                                    ->orWhereIn('action_type_id', $undisputed_actions);
                            });
                    })
            ])
            ->search(trim($this->search))
            ->orderBy($this->sortColumn, $this->sortDirection);

        return $query;
        // dd($query);
    }

    public function create()
    {
        $year = date('Y');
        $month = date('m');

        if ($month < 7) {
            $this->state['tax_year'] = \App\Helpers\Helper::convertNumberEngToBang(($year - 1) . '-' . $year);
        } else {
            $this->state['tax_year'] = \App\Helpers\Helper::convertNumberEngToBang($year . '-' . ($year + 1));
        }

        $this->createState = true;
        $this->viewState = false;
    }

    public function createDemand(Taxpayer $taxpayer)
    {
        if (auth()->user()->getRoleNames()->toArray()[0] != 'Circle') {
            session()->flash('error', 'You don\'t have permission to create demand.');
            return;
        }
        $this->createState = true;
        $this->viewState = false;
        $this->taxpayer = $taxpayer;
        $this->state['tin'] = $taxpayer->tin;
        $this->state['name'] = $taxpayer->name;
        $this->state['address'] = $taxpayer->address;
        $this->state['contact_name'] = $taxpayer->contact_name;
        $this->state['contact_phone'] = $taxpayer->contact_phone;
        $this->newTaxpayer = false;
    }

    public function cancel()
    {
        $this->resetInputFields();
    }
}
