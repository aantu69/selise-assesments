<?php

namespace App\Http\Livewire\Dashboard;

use App\Models\Action;
use App\Models\Circle;
use App\Models\Notice;
use Livewire\Component;
use App\Models\Taxpayer;
use App\Models\ActionType;
use App\Models\NoticeType;
use Illuminate\Support\Str;
use App\Http\Livewire\Traits\SearchSort;
use Illuminate\Support\Facades\Validator;
use App\Traits\AuthorizesRoleOrPermission;

class Notices extends Component
{
    use AuthorizesRoleOrPermission;
    use SearchSort;

    public Taxpayer $taxpayer;

    public $title = 'Reminder';
    public $createButton = true;

    public $viewState = false;
    public $createState = true;
    public $updateState = false;

    public $state = [], $notice, $noticeTypes, $noticeType, $noticeBottomPart, $person, $circleZone, $circle;

    protected $listeners = ['destroy'];

    protected $rules = [
        'document_number' => 'required',
        'created_date' => 'required',
        'to' => 'required',
        'payment_date' => 'required',
        'notice_type_id' => 'required',
    ];

    protected $messages = [
        'selectedRoles.required' => 'Please select atleast one role.',
        'selectedRoles.min' => 'Please select atleast one role.',
    ];

    public function validateForm()
    {
        Validator::make($this->state, $this->rules, $this->messages)->validate();
    }

    public function mount()
    {
        if (!in_array($this->taxpayer->circle_id, auth()->user()->circles->pluck('id')->map(fn ($item) => (string) $item)->toArray())) {
            return redirect()->route('dashboard.taxpayers');
        }

        $this->year = date('Y');
        $this->month = date('m');

        if ($this->month < 7) {
            $current_session = '/' . ($this->year - 1) . '-' . $this->year . '/';
        } else {
            $current_session = '/' . $this->year . '-' . ($this->year + 1) . '/';
        }
        $this->noticeTypes = NoticeType::all();
        $this->circle = Circle::with('zone')->find(auth()->user()->circles->first()->id);

        $tin_bn = \App\Helpers\Helper::convertNumberEngToBang($this->taxpayer->tin);
        $circle_bn = '/সা-' . Str::of($this->circle->name_bn)->explode('-')[1];
        $zone_bn = '/কঅ-' . Str::of($this->circle->zone->name_bn)->explode('-')[1];
        $current_session = \App\Helpers\Helper::convertNumberEngToBang($current_session);
        $this->state['document_number'] = $tin_bn . '/বকেয়া' . $circle_bn . $zone_bn . $current_session;

        $to = $this->taxpayer->type == 'কোম্পানি' ? '<p>ব্যবস্থাপনা পরিচালক/প্রধান নির্বাহী কর্মকর্তা<br/>' : '<p>';
        $to .= $this->taxpayer->name . '<br/>';
        $to .= $this->taxpayer->address . '</p>';
        $this->state['to'] = $to;





        //         নথি নং-১৬২২৯৯৭৫৬৫১৩/বকেয়া/সা-১৬১/কঅ-৮/২০২১-২০২২/                                              তারিখঃ ০৫/০৫/২০২২ খ্রিঃ

        // ব্যবস্থাপনা পরিচালক/প্রধান নির্বাহী কর্মকর্তা
        // পেট্রোবাংলা
        // ৩, কাওরান বাজার, ঢাকা।


        // return redirect()->route('dashboard.prints.notice', ['notice' => 1]);
    }

    private function headerConfig()
    {
        return [
            [
                'data' => 'subject', 'label' => 'Subject', 'order' => true,
                'func' => function ($value) {
                    return $value;
                }
            ],
            [
                'data' => 'top_part', 'label' => 'Top Part', 'order' => true,
                'func' => function ($value) {
                    return $value;
                }
            ],
            [
                'data' => 'bottom_part', 'label' => 'Bottom Part', 'order' => true,
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

    public function render()
    {
        return view('livewire.dashboard.notices', [
            'title' => $this->title
        ])->layout('layouts.dashboard');
    }

    public function store()
    {
        // dd($this->state);
        $this->validateForm();
        $this->state['created_by'] = auth()->user()->profile->name;
        $this->state['designation'] = auth()->user()->profile->designation;
        $this->state['phone'] = auth()->user()->profile->phone;
        $undisputed_actions = ActionType::where('disputed', 0)->pluck('id')->map(fn ($item) => (string) $item)->toArray();
        $taxpayer = Taxpayer::query()
            ->with('demands', function ($query) use ($undisputed_actions) {
                $query->whereRaw('((case when demands.corrected_amount = 0 then demands.demand_amount else demands.corrected_amount end) - paid_amount) > ?', [0])
                    ->where(function ($query) use ($undisputed_actions) {
                        $query->whereNull('action_type_id')
                            ->orWhereIn('action_type_id', $undisputed_actions);
                    });
            })
            ->whereHas('demands', function ($query) use ($undisputed_actions) {
                $query->whereRaw('((case when demands.corrected_amount = 0 then demands.demand_amount else demands.corrected_amount end) - paid_amount) > ?', [0])
                    ->where(function ($query) use ($undisputed_actions) {
                        $query->whereNull('action_type_id')
                            ->orWhereIn('action_type_id', $undisputed_actions);
                    });
            })
            ->find($this->taxpayer->id);


        if ($taxpayer == null) {
            session()->flash('error', 'করদাতার কোনো অবিতর্কিত বকেয়া নাই।');
            return;
        }

        $this->state['taxpayer_id'] = $this->taxpayer->id;

        $notice = Notice::create($this->state);
        foreach ($taxpayer->demands as $demand) {
            $demand_amount = ($demand->corrected_amount == null || $demand->corrected_amount == 0) ? $demand->demand_amount : $demand->corrected_amount;
            $due_amount = $demand_amount - $demand->paid_amount;
            $notice->noticeDetails()->create([
                'tax_year' => $demand->tax_year,
                'demand_amount' => $due_amount
            ]);

            $demand->update(['action_type_id' => 1]);

            $demand->actions()->create([
                'action_date' => $this->state['created_date'],
                'action_type_id' => 1
            ]);
        }

        $this->notice = Notice::with('noticeType', 'noticeDetails')->find($notice->id);
        // $this->circle = Circle::with('zone')->find(auth()->user()->circles->first()->id);
        $this->circleZone = $this->circle->name_bn . ', ' . $this->circle->zone->name_bn;
        $noticeBottomPart = Str::of($this->notice->noticeType->bottom_part)->replace('PYMENT_DUE_DATE', \App\Helpers\Helper::convertNumberEngToBang($this->notice->payment_date));
        $this->noticeBottomPart = Str::of($noticeBottomPart)->replace('CIRCLE_ADDRESS', $this->circleZone);

        $this->createState = false;
        $this->viewState = true;

        // dd($notice);
        // return redirect()->route('dashboard.prints.notice', ['notice' => $notice->id]);
    }

    public function edit(Notice $notice)
    {
        $this->updateState = true;
        $this->viewState = false;
        $this->state = $notice->toArray();
        $this->notice = $notice;
    }

    public function update()
    {
        $this->validateForm();
        $this->notice->update($this->state);
        session()->flash('success', 'Data has been updated successfully.');
        $this->resetInputFields();
    }

    public function confirmDelete($id)
    {
        $this->authorizeRoleOrPermission('Notice Delete');
        $this->dispatchBrowserEvent('swal:confirm', ['id' => $id]);
    }

    public function destroy(Notice $notice)
    {
        $notice->delete();
        session()->flash('success', 'Data has been deleted successfully.');
        $this->resetInputFields();
    }

    private function resetInputFields()
    {
        $this->reset(['state', 'createState', 'updateState', 'viewState', 'notice']);
        $this->resetErrorBag();
    }

    public function view()
    {
        // $this->validateForm();
        // $this->createState = false;
        // $this->viewState = true;
        // $this->person = Taxpayer::with('demands')->find($this->taxpayer->id);
        // $this->noticeType = NoticeType::find($this->state['notice_type_id']);
        // $this->circle = Circle::with('zone')->find(auth()->user()->circles->first()->id);
        // // dd($circle->zone->name);
        // $this->circleZone = 'কর ' . $this->circle->name_bn . ', ' . $this->circle->zone->name_bn;
        // $this->noticeBottomPart = Str::of($this->noticeType->bottom_part)->replace('PYMENT_DUE_DATE', \App\Helpers\Helper::convertNumberEngToBang($this->state['payment_date']));
        // $this->noticeBottomPart = Str::of($this->noticeBottomPart)->replace('CIRCLE_ADDRESS', $this->circleZone);
    }

    public function create()
    {
        $this->createState = true;
        $this->viewState = false;
    }

    public function cancel()
    {
        return redirect()->route('dashboard.taxpayers');
        // $this->resetInputFields();
    }
}
