<?php

namespace App\Http\Livewire\Dashboard;

use App\Http\Livewire\Traits\SearchSort;
use App\Models\Demand;
use App\Models\Payment;
use Livewire\Component;
use Illuminate\Support\Facades\Validator;
use App\Traits\AuthorizesRoleOrPermission;

class Payments extends Component
{
    use AuthorizesRoleOrPermission;
    use SearchSort;

    public Demand $demand;

    public $title = 'Payments';
    public $createButton = true;
    public $perPageOptions = ['10' => '10', '25' => '25', '50' => '50', 'All' => '-1'];

    public $viewState = true;
    public $createState = false;
    public $updateState = false;

    public $state = [], $payment;

    protected $listeners = ['destroy', 'store'];

    protected $rules = [
        'paid_date' => 'required',
        'paid_amount' => 'required',
        'payment_detail' => 'required',
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
                'data' => 'payment_detail', 'label' => __('label.payment_detail'), 'order' => true,
                'func' => function ($value) {
                    return $value;
                }
            ],
            [
                'data' => 'paid_date', 'label' => __('label.paid_date'), 'order' => true,
                'func' => function ($value) {
                    return $value;
                }
            ],
            [
                'data' => 'paid_amount', 'label' => __('label.paid_amount'), 'order' => true,
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

        return view('livewire.dashboard.payments', [
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
        $this->demand->payments()->create($this->state);
        $this->state['action_date'] = $this->state['paid_date'];
        $this->state['action_type_id'] = 10;
        $this->demand->actions()->create($this->state);
        $this->demand->update([
            'paid_amount' => Demand::withSum('payments', 'paid_amount')->find($this->demand->id)->payments_sum_paid_amount,
            // 'action_type_id' => 10,
            'action_type_id' => $this->demand->latestAction->action_type_id
        ]);
        session()->flash('success', 'Data has been created successfully.');
        $this->resetInputFields();
    }

    public function edit(Payment $payment)
    {
        if (auth()->user()->getRoleNames()->toArray()[0] == 'Range') {
            session()->flash('error', 'You don\'t have permission to edit this entry.');
            return;
        }
        $this->updateState = true;
        $this->viewState = false;
        $this->state = $payment->demand->taxpayer->toArray();
        $this->state['tax_year'] = $payment->demand->tax_year;
        $this->state['demand_date'] = $payment->demand->demand_date;
        $this->state['corresponding_section'] = $payment->demand->corresponding_section;
        $this->state['demand_amount'] = $payment->demand->demand_amount;
        $this->state['demand_comments'] = $payment->demand->comments;
        $this->state['taxpayer_id'] = $payment->demand->taxpayer_id;
        $this->state['paid_date'] = $payment->paid_date;
        $this->state['paid_amount'] = $payment->paid_amount;
        $this->state['payment_detail'] = $payment->payment_detail;
        $this->state['comments'] = $payment->comments;
        $this->payment = $payment;
    }

    public function update()
    {
        $roles = array("Commissioner", "Admin");
        $this->validateForm();
        if (in_array(auth()->user()->getRoleNames()->toArray()[0], $roles)) {
            $this->payment->update($this->state);
            $this->demand->update([
                'paid_amount' => Demand::withSum('payments', 'paid_amount')->find($this->demand->id)->payments_sum_paid_amount
            ]);
        } else {
            $this->payment->update(
                [
                    'paid_date' => $this->state['paid_date'],
                    'payment_detail' => $this->state['payment_detail'],
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

    public function destroy(Payment $payment)
    {
        $payment->delete();
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
        return Payment::with('demand', 'demand.taxpayer')
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
