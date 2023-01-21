<?php

namespace App\Http\Livewire\Dashboard;

use Carbon\Carbon;
use App\Models\Demand;
use Livewire\Component;
use App\Models\ActionType;
use App\Traits\ApiTrait;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class Home extends Component
{

    public $title = 'Document';
    public $viewState = false;
    public $createState = true;
    public $updateState = false;
    public $state = [], $data = [], $photo, $circles, $selectedCircle;
    public $tax_year, $year, $month, $start_date, $end_date, $previous_start_date, $previous_end_date, $disputed_actions, $undisputed_actions;
    public $demands_previous = [], $demands_current = [];
    public $collections_previous = [], $collections_current = [];
    public $current_total_demands = 0, $previous_total_demands = 0, $changes_demands = 0;
    public $current_total_collections = 0, $previous_total_collections = 0, $changes_collections = 0;
    public $current_tax_year, $previous_tax_year;

    public function mount()
    {
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
            $this->current_tax_year = ($this->year - 1) . '-' . $this->year;
            $this->previous_tax_year = ($this->year - 2) . '-' . ($this->year - 1);
        } else {
            $this->previous_start_date = ($this->year - 1) . '-07-01';
            $this->previous_end_date = $this->year . '-06-30';
            $this->start_date = $this->year . '-07-01';
            $this->end_date = ($this->year + 1) . '-06-30';
            $this->current_tax_year = $this->year . '-' . ($this->year + 1);
            $this->previous_tax_year = ($this->year - 1) . '-' . $this->year;
        }

        // $this->end_date = Carbon::now()->startOfMonth()->endOfMonth()->toDateString();
        // $this->previous_end_date = Carbon::now()->subYear()->startOfMonth()->endOfMonth()->toDateString();

        $this->disputed_actions = ActionType::where('disputed', 1)->pluck('id')->map(fn ($item) => (string) $item)->toArray();
        $this->undisputed_actions = ActionType::where('disputed', 0)->pluck('id')->map(fn ($item) => (string) $item)->toArray();
    }

    public function render()
    {
        $selectedCircle = $this->selectedCircle;
        $disputed_actions = $this->disputed_actions;
        $undisputed_actions = $this->undisputed_actions;

        $this->data['due_demands'] = Demand::query()
            ->when($selectedCircle, function ($query) use ($selectedCircle) {
                $query->where('circle_id', $selectedCircle);
            }, function ($query) {
                $query->whereIn('circle_id', auth()->user()->circles->pluck('id')->map(fn ($item) => (string) $item)->toArray());
            })
            ->whereNotBetween('demand_date', [$this->start_date, $this->end_date])
            ->selectRaw('sum((case when corrected_amount = 0 then demand_amount else corrected_amount end) - paid_amount) as due_amount')
            ->get();

        $this->data['disputed_due_demands'] = Demand::query()
            ->when($selectedCircle, function ($query) use ($selectedCircle) {
                $query->where('circle_id', $selectedCircle);
            }, function ($query) {
                $query->whereIn('circle_id', auth()->user()->circles->pluck('id')->map(fn ($item) => (string) $item)->toArray());
            })
            ->whereIn('action_type_id', $disputed_actions)
            ->whereNotBetween('demand_date', [$this->start_date, $this->end_date])
            ->selectRaw('sum((case when corrected_amount = 0 then demand_amount else corrected_amount end) - paid_amount) as due_amount')
            ->get();

        $this->data['undisputed_due_demands'] = Demand::query()
            ->when($selectedCircle, function ($query) use ($selectedCircle) {
                $query->where('circle_id', $selectedCircle);
            }, function ($query) {
                $query->whereIn('circle_id', auth()->user()->circles->pluck('id')->map(fn ($item) => (string) $item)->toArray());
            })
            ->where(function ($query) use ($undisputed_actions) {
                $query->whereNull('action_type_id')
                    ->orWhereIn('action_type_id', $undisputed_actions);
            })
            ->whereNotBetween('demand_date', [$this->start_date, $this->end_date])
            ->selectRaw('sum((case when corrected_amount = 0 then demand_amount else corrected_amount end) - paid_amount) as due_amount')
            ->get();

        $this->data['current_demands'] = Demand::query()
            ->when($selectedCircle, function ($query) use ($selectedCircle) {
                $query->where('circle_id', $selectedCircle);
            }, function ($query) {
                $query->whereIn('circle_id', auth()->user()->circles->pluck('id')->map(fn ($item) => (string) $item)->toArray());
            })
            ->whereBetween('demand_date', [$this->start_date, $this->end_date])
            ->selectRaw('sum((case when corrected_amount = 0 then demand_amount else corrected_amount end) - paid_amount) as due_amount')
            ->get();

        $this->data['disputed_current_demands'] = Demand::query()
            ->when($selectedCircle, function ($query) use ($selectedCircle) {
                $query->where('circle_id', $selectedCircle);
            }, function ($query) {
                $query->whereIn('circle_id', auth()->user()->circles->pluck('id')->map(fn ($item) => (string) $item)->toArray());
            })
            ->whereIn('action_type_id', $disputed_actions)
            ->whereBetween('demand_date', [$this->start_date, $this->end_date])
            ->selectRaw('sum((case when corrected_amount = 0 then demand_amount else corrected_amount end) - paid_amount) as due_amount')
            ->get();

        $this->data['undisputed_current_demands'] = Demand::query()
            ->when($selectedCircle, function ($query) use ($selectedCircle) {
                $query->where('circle_id', $selectedCircle);
            }, function ($query) {
                $query->whereIn('circle_id', auth()->user()->circles->pluck('id')->map(fn ($item) => (string) $item)->toArray());
            })
            ->where(function ($query) use ($undisputed_actions) {
                $query->whereNull('action_type_id')
                    ->orWhereIn('action_type_id', $undisputed_actions);
            })
            ->whereBetween('demand_date', [$this->start_date, $this->end_date])
            ->selectRaw('sum((case when corrected_amount = 0 then demand_amount else corrected_amount end) - paid_amount) as due_amount')
            ->get();


        // $demands = Demand::selectRaw('year(demand_date) as year, month(demand_date) as month, monthname(demand_date) as monthName, sum(demand_amount)/100 as demands')
        //     ->whereBetween('demand_date', [$this->start_date, $this->end_date])
        //     ->groupBy('year', 'month', 'monthName')
        //     ->orderBy('year', 'asc')
        //     ->orderBy('month', 'asc')
        //     ->get();

        $this->chartData();

        return view('livewire.dashboard.home', $this->data)->layout('layouts.dashboard');
    }

    public function chartData()
    {
        $selectedCircle = $this->selectedCircle;
        $disputed_actions = $this->disputed_actions;
        $undisputed_actions = $this->undisputed_actions;

        $query1 = 'id, name, month_number';
        $results1 = DB::query()
            ->selectRaw($query1)
            ->from('months');

        $query2 = 'year(demand_date) as year, month(demand_date) as month, monthname(demand_date) as monthName, sum(demand_amount)/100 as demands';
        $results2 = DB::query()
            ->selectRaw($query2)
            ->from('demands')
            ->when($selectedCircle, function ($query) use ($selectedCircle) {
                $query->where('circle_id', $selectedCircle);
            }, function ($query) {
                $query->whereIn('circle_id', auth()->user()->circles->pluck('id')->map(fn ($item) => (string) $item)->toArray());
            })
            ->whereBetween('demand_date', [$this->start_date, $this->end_date])
            ->groupBy('year', 'month', 'monthName')
            ->orderBy('year', 'asc')
            ->orderBy('month', 'asc');

        $query3 = 'results1.*, results2.year, IFNULL(results2.demands, 0) AS demand_amount';
        $results3 = DB::query()
            ->selectRaw($query3)
            ->fromSub($results1, "results1")
            ->leftJoinSub($results2, 'results2', [['results1.month_number', '=', 'results2.month']])
            ->orderBy('results1.id', 'ASC')->get()->toArray();


        $demands_current = array();
        $current_total_demands = 0;
        foreach ($results3 as $result) {
            $demands_current[] = (int)$result->demand_amount;
            $current_total_demands += (int)$result->demand_amount;
        }
        $this->demands_current = json_encode($demands_current, JSON_NUMERIC_CHECK);
        $this->current_total_demands = $current_total_demands;
        // dump($this->current);

        $results4 = DB::query()
            ->selectRaw($query2)
            ->from('demands')
            ->when($selectedCircle, function ($query) use ($selectedCircle) {
                $query->where('circle_id', $selectedCircle);
            }, function ($query) {
                $query->whereIn('circle_id', auth()->user()->circles->pluck('id')->map(fn ($item) => (string) $item)->toArray());
            })
            ->whereBetween('demand_date', [$this->previous_start_date, $this->previous_end_date])
            ->groupBy('year', 'month', 'monthName')
            ->orderBy('year', 'asc')
            ->orderBy('month', 'asc');

        $query4 = 'results1.*, results4.year, IFNULL(results4.demands, 0) AS demand_amount';
        $results5 = DB::query()
            ->selectRaw($query4)
            ->fromSub($results1, "results1")
            ->leftJoinSub($results4, 'results4', [['results1.month_number', '=', 'results4.month']])
            ->orderBy('results1.id', 'ASC')->get()->toArray();

        $demands_previous = array();
        $previous_total_demands = 0;
        foreach ($results5 as $result) {
            $demands_previous[] = (int)$result->demand_amount;
            $previous_total_demands += (int)$result->demand_amount;
        }
        $this->demands_previous = json_encode($demands_previous, JSON_NUMERIC_CHECK);
        $this->previous_total_demands = $previous_total_demands;

        $this->changes_demands = $this->previous_total_demands > 0 ? ($this->current_total_demands - $this->previous_total_demands) / $this->previous_total_demands * 100 : 0;

        $query6 = 'year(paid_date) as year, month(paid_date) as month, monthname(paid_date) as monthName, sum(paid_amount)/100 as collections';
        $results6 = DB::query()
            ->selectRaw($query6)
            ->from('payments')
            ->when($selectedCircle, function ($query) use ($selectedCircle) {
                $query->where('circle_id', $selectedCircle);
            }, function ($query) {
                $query->whereIn('circle_id', auth()->user()->circles->pluck('id')->map(fn ($item) => (string) $item)->toArray());
            })
            ->whereBetween('paid_date', [$this->start_date, $this->end_date])
            ->groupBy('year', 'month', 'monthName')
            ->orderBy('year', 'asc')
            ->orderBy('month', 'asc');

        $query7 = 'results1.*, results6.year, IFNULL(results6.collections, 0) AS paid_amount';
        $results7 = DB::query()
            ->selectRaw($query7)
            ->fromSub($results1, "results1")
            ->leftJoinSub($results6, 'results6', [['results1.month_number', '=', 'results6.month']])
            ->orderBy('results1.id', 'ASC')->get()->toArray();


        $collections_current = array();
        $current_total_collections = 0;
        foreach ($results7 as $result) {
            $collections_current[] = (int)$result->paid_amount;
            $current_total_collections += (int)$result->paid_amount;
        }
        $this->collections_current = json_encode($collections_current, JSON_NUMERIC_CHECK);
        $this->current_total_collections = $current_total_collections;

        $query8 = 'year(paid_date) as year, month(paid_date) as month, monthname(paid_date) as monthName, sum(paid_amount)/100 as collections';
        $results8 = DB::query()
            ->selectRaw($query8)
            ->from('payments')
            ->when($selectedCircle, function ($query) use ($selectedCircle) {
                $query->where('circle_id', $selectedCircle);
            }, function ($query) {
                $query->whereIn('circle_id', auth()->user()->circles->pluck('id')->map(fn ($item) => (string) $item)->toArray());
            })
            ->whereBetween('paid_date', [$this->previous_start_date, $this->previous_end_date])
            ->groupBy('year', 'month', 'monthName')
            ->orderBy('year', 'asc')
            ->orderBy('month', 'asc');

        $query9 = 'results1.*, results8.year, IFNULL(results8.collections, 0) AS paid_amount';
        $results9 = DB::query()
            ->selectRaw($query9)
            ->fromSub($results1, "results1")
            ->leftJoinSub($results8, 'results8', [['results1.month_number', '=', 'results8.month']])
            ->orderBy('results1.id', 'ASC')->get()->toArray();

        $collections_previous = array();
        $previous_total_collections = 0;
        foreach ($results9 as $result) {
            $collections_previous[] = (int)$result->paid_amount;
            $previous_total_collections += (int)$result->paid_amount;
        }
        $this->collections_previous = json_encode($collections_previous, JSON_NUMERIC_CHECK);
        $this->previous_total_collections = $previous_total_collections;

        $this->changes_collections = $this->previous_total_collections > 0 ? ($this->current_total_collections - $this->previous_total_collections) / $this->previous_total_collections * 100 : 0;
    }

    public function getDueDemands()
    {
        $selectedCircle = $this->selectedCircle;
        $this->data['demands'] = Demand::query()
            ->with('taxpayer')
            ->when($selectedCircle, function ($query) use ($selectedCircle) {
                $query->where('circle_id', $selectedCircle);
            }, function ($query) {
                $query->whereIn('circle_id', auth()->user()->circles->pluck('id')->map(fn ($item) => (string) $item)->toArray());
            })
            ->whereNotBetween('demand_date', [$this->start_date, $this->end_date])
            ->get();
    }

    public function getDisputedDueDemands()
    {
        $selectedCircle = $this->selectedCircle;
        $disputed_actions = $this->disputed_actions;
        $this->data['demands'] = Demand::query()
            ->with('taxpayer')
            ->when($selectedCircle, function ($query) use ($selectedCircle) {
                $query->where('circle_id', $selectedCircle);
            }, function ($query) {
                $query->whereIn('circle_id', auth()->user()->circles->pluck('id')->map(fn ($item) => (string) $item)->toArray());
            })
            ->whereIn('action_type_id', $disputed_actions)
            ->whereNotBetween('demand_date', [$this->start_date, $this->end_date])
            ->get();
    }

    public function getUndisputedDueDemands()
    {
        $selectedCircle = $this->selectedCircle;
        $undisputed_actions = $this->undisputed_actions;
        $this->data['demands'] = Demand::query()
            ->when($selectedCircle, function ($query) use ($selectedCircle) {
                $query->where('circle_id', $selectedCircle);
            }, function ($query) {
                $query->whereIn('circle_id', auth()->user()->circles->pluck('id')->map(fn ($item) => (string) $item)->toArray());
            })
            ->where(function ($query) use ($undisputed_actions) {
                $query->whereNull('action_type_id')
                    ->orWhereIn('action_type_id', $undisputed_actions);
            })
            ->whereNotBetween('demand_date', [$this->start_date, $this->end_date])
            ->get();
    }

    public function getCurrentDemands()
    {
        $selectedCircle = $this->selectedCircle;
        $this->data['demands'] = Demand::query()
            ->when($selectedCircle, function ($query) use ($selectedCircle) {
                $query->where('circle_id', $selectedCircle);
            }, function ($query) {
                $query->whereIn('circle_id', auth()->user()->circles->pluck('id')->map(fn ($item) => (string) $item)->toArray());
            })
            ->whereBetween('demand_date', [$this->start_date, $this->end_date])
            ->get();
    }
    public function getDisputedCurrentDemands()
    {
        $selectedCircle = $this->selectedCircle;
        $disputed_actions = $this->disputed_actions;
        $this->data['demands'] = Demand::query()
            ->when($selectedCircle, function ($query) use ($selectedCircle) {
                $query->where('circle_id', $selectedCircle);
            }, function ($query) {
                $query->whereIn('circle_id', auth()->user()->circles->pluck('id')->map(fn ($item) => (string) $item)->toArray());
            })
            ->whereIn('action_type_id', $disputed_actions)
            ->whereBetween('demand_date', [$this->start_date, $this->end_date])
            ->get();
    }
    public function getUndisputedCurrentDemands()
    {
        $selectedCircle = $this->selectedCircle;
        $undisputed_actions = $this->undisputed_actions;
        $this->data['demands'] = Demand::query()
            ->when($selectedCircle, function ($query) use ($selectedCircle) {
                $query->where('circle_id', $selectedCircle);
            }, function ($query) {
                $query->whereIn('circle_id', auth()->user()->circles->pluck('id')->map(fn ($item) => (string) $item)->toArray());
            })
            ->where(function ($query) use ($undisputed_actions) {
                $query->whereNull('action_type_id')
                    ->orWhereIn('action_type_id', $undisputed_actions);
            })
            ->whereBetween('demand_date', [$this->start_date, $this->end_date])
            ->get();
    }
}
