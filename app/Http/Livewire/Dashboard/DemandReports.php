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

class DemandReports extends Component
{
    use AuthorizesRoleOrPermission;
    use SearchSort;


    public $circles, $actionTypes, $selectedCircle, $selectedStartDate, $selectedEndDate,
        $selectedStatus = 'current', $selectedAction, $selectedActionType, $selectedTaxpayerType;

    public $title = 'Demand';
    public $createButton = false;
    public $perPageOptions = ['5' => '5', '10' => '10', '25' => '25', '50' => '50', 'All' => '-1'];


    public $start_date, $end_date, $previous_start_date, $previous_end_date, $disputed_actions, $undisputed_actions;


    private function headerConfig()
    {
        if (auth()->user()->getRoleNames()->toArray()[0] != 'Circle') {
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
                    'data' => 'circle', 'label' => __('label.circle'), 'order' => false,
                    'func' => function ($value) {
                        return $value->name;
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
                    'data' => 'demand_amount', 'label' => __('label.demand_amount'), 'order' => true, 'style' => 'text-align: right;', 'totals' => 'demand_amounts',
                    'func' => function ($value) {
                        return $value;
                    }
                ],
                [
                    'data' => 'corrected_amount', 'label' => __('label.corrected_amount'), 'order' => true, 'style' => 'text-align: right;',
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
                        return $action;
                    }
                ],
            ];
        } else {
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
                    'data' => 'demand_amount', 'label' => __('label.demand_amount'), 'order' => true, 'style' => 'text-align: right;', 'totals' => 'demand_amounts',
                    'func' => function ($value) {
                        return $value;
                    }
                ],
                [
                    'data' => 'corrected_amount', 'label' => __('label.corrected_amount'), 'order' => true, 'style' => 'text-align: right;',
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
                        return $action;
                    }
                ],
            ];
        }
    }

    public function mount()
    {
        if (auth()->user()->getRoleNames()->toArray()[0] != 'Circle') {
            $this->circles = auth()->user()->circles;
        }

        $this->actionTypes = ActionType::where('id', '<', 10)->get();

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
        return view('livewire.dashboard.demand-reports', [
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
        $selectedCircle = $this->selectedCircle;
        $selectedStatus = $this->selectedStatus;
        $selectedAction = $this->selectedAction;
        $selectedActionType = $this->selectedActionType;
        $disputed_actions = $this->disputed_actions;
        $undisputed_actions = $this->undisputed_actions;
        $selectedTaxpayerType = $this->selectedTaxpayerType;

        $selectedStartDate = $this->selectedStartDate;
        $selectedEndDate = $this->selectedEndDate;
        if ($selectedStartDate != null) {
            $selectedStartDate = Carbon::createFromFormat(config('app.date_format'), $selectedStartDate)->format('Y-m-d');
        }
        if ($selectedEndDate != null) {
            $selectedEndDate = Carbon::createFromFormat(config('app.date_format'), $selectedEndDate)->format('Y-m-d');
        }

        $query = Demand::query();

        if ($selectedCircle) {
            $query = $query->where('circle_id', $selectedCircle);
        } else {
            $query = $query->whereIn('circle_id', auth()->user()->circles->pluck('id')->map(fn ($item) => (string) $item)->toArray());
        }

        $query = $query->with('taxpayer', 'actionType', 'circle')
            ->withSum('payments', 'paid_amount')
            ->selectRaw('((case when demands.corrected_amount = 0 then demands.demand_amount else demands.corrected_amount end) - paid_amount) as due_amount')
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
            ->when($selectedStartDate && $selectedEndDate, function ($query) use ($selectedStartDate, $selectedEndDate) {
                $query->whereBetween('demand_date', [$selectedStartDate, $selectedEndDate]);
            })
            ->when($selectedActionType, function ($query) use ($selectedActionType) {
                $selectedActionType == 1
                    ? $query->whereNull('action_type_id')->orWhere('action_type_id', $selectedActionType)
                    : $query->where('action_type_id', $selectedActionType);
            })
            // ->when($selectedTaxpayerType, function ($query) use ($selectedStartDate, $selectedEndDate) {
            //     $query->where('demand_date', [$selectedStartDate, $selectedEndDate]);
            // })
            ->orderBy($this->sortColumn, $this->sortDirection);

        return $query;
    }

    public function resetAll()
    {
        $this->reset(['selectedCircle', 'selectedStatus', 'selectedAction', 'selectedStartDate', 'selectedEndDate', 'selectedActionType']);
        $this->resetErrorBag();
    }
}
