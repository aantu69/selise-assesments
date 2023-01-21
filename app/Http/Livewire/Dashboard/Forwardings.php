<?php

namespace App\Http\Livewire\Dashboard;

use Carbon\Carbon;
use App\Models\Demand;
use Livewire\Component;
use App\Models\Forwarding;
use Illuminate\Support\Facades\DB;
use App\Http\Livewire\Traits\SearchSort;
use Illuminate\Support\Facades\Validator;
use App\Traits\AuthorizesRoleOrPermission;
use Illuminate\Support\Facades\Auth;

class Forwardings extends Component
{
    use AuthorizesRoleOrPermission;
    use SearchSort;
    public $title = 'Forward Diary';
    public $createButton = true;
    public $perPageOptions = ['10' => '10', '25' => '25', '50' => '50', 'All' => '-1'];


    public $viewState = true;
    public $createState = false;
    public $updateState = false;
    public $nextState = false;

    public $state = [], $circles, $selectedCircle, $date, $selectedForwardDate, $roles, $selectedRoles = [];


    protected $listeners = ['destroy', 'store'];

    protected $rules = [
        'tin' => 'required',
        'name' => 'required',
        'address' => 'required',
        'contact_name' => 'required',
        'contact_phone' => 'required',
        'subject' => 'required',
        'tax_year' => 'required',
        'forward_date' => 'required'
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
                'data' => 'subject', 'label' => __('label.subject'), 'order' => true,
                'func' => function ($value) {
                    return $value;
                }
            ],
            [
                'data' => 'tax_year', 'label' => __('label.tax_year'), 'order' => true,
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
                'data' => 'forward_date', 'label' => __('label.forward_date'), 'order' => true,
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
        if (auth()->user()->getRoleNames()->toArray()[0] != 'Circle') {
            $this->circles = auth()->user()->circles;
            $this->selectedForwardDate = date('d/m/Y');
        }
        $this->sortDirection = 'desc';
    }

    public function render()
    {
        return view('livewire.dashboard.forwardings', [
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
        $forwarding = Forwarding::create($this->state);
        session()->flash('success', 'Data has been created successfully.');
        $this->resetInputFields();
    }

    public function edit(Forwarding $forwarding)
    {
        if (auth()->user()->getRoleNames()->toArray()[0] == 'Range') {
            session()->flash('error', 'You don\'t have permission to edit this entry.');
            return;
        }
        $this->updateState = true;
        $this->viewState = false;
        $this->nextState = true;
        $this->state = $forwarding->toArray();
        $this->forwarding = $forwarding;
        $this->date = $this->state['forward_date'];
    }

    public function update()
    {
        $this->rules = array_merge($this->rules, [
            'comments' => ['required'],
        ]);
        $this->validateForm();
        $this->state['completed'] = true;
        $this->forwarding->update($this->state);
        session()->flash('success', 'Data has been updated successfully.');
        $this->resetInputFields();
    }

    public function nextStep()
    {
        $this->rules = array_merge($this->rules, [
            'comments' => ['required'],
        ]);
        $this->validateForm();
        $comments = $this->state['comments'];
        $this->state['comments'] = '';
        $forwarding = Forwarding::create($this->state);
        $this->state['comments'] = $comments;
        $this->state['forward_date'] = $this->date;
        $this->state['completed'] = true;
        $this->forwarding->update($this->state);
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

    public function destroy(Forwarding $forwarding)
    {
        $forwarding->delete();
        session()->flash('success', 'Data has been deleted successfully.');
        $this->resetInputFields();
    }

    private function resetInputFields()
    {
        $this->reset(['state', 'createState', 'updateState', 'viewState', 'nextState']);
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
        $selectedForwardDate = $this->selectedForwardDate;
        if ($selectedForwardDate != null) {
            $selectedForwardDate = Carbon::createFromFormat(config('app.date_format'), $selectedForwardDate)->format('Y-m-d');
        }

        $query = Forwarding::query();

        if ($selectedCircle) {
            $query = $query->where('circle_id', $selectedCircle);
        } else {
            $query = $query->whereIn('circle_id', auth()->user()->circles->pluck('id')->map(fn ($item) => (string) $item)->toArray());
        }

        $query = $query->when($selectedForwardDate, function ($query) use ($selectedForwardDate) {
            $query->where('forward_date', $selectedForwardDate);
        })
            ->search(trim($this->search))
            ->orderBy($this->sortColumn, $this->sortDirection);

        return $query;
    }

    // public function updatedSelectedForwardDate()
    // {
    //     dd($this->selectedForwardDate);
    // }

    public function create()
    {
        $this->year = date('Y');
        $this->month = date('m');

        if ($this->month < 7) {
            $this->state['tax_year'] = \App\Helpers\Helper::convertNumberEngToBang(($this->year - 1) . '-' . $this->year);
        } else {
            $this->state['tax_year'] = \App\Helpers\Helper::convertNumberEngToBang($this->year . '-' . ($this->year + 1));
        }
        $this->createState = true;
        $this->viewState = false;
    }

    public function cancel()
    {
        $this->resetInputFields();
    }
}
