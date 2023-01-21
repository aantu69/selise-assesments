<?php

namespace App\Http\Livewire\Dashboard;

use App\Http\Livewire\Traits\SearchSort;
use App\Models\Notice;
use App\Models\NoticeType;
use App\Models\Taxpayer;
use Livewire\Component;
use Illuminate\Support\Facades\Validator;
use App\Traits\AuthorizesRoleOrPermission;

class PreviousNotices extends Component
{
    use AuthorizesRoleOrPermission;
    use SearchSort;


    public $title = 'Notice';
    public $createButton = false;

    public $viewState = true;

    public $state = [], $notice, $noticeTypes;

    protected $listeners = ['destroy'];

    public function mount()
    {
        if (!in_array($this->taxpayer->circle_id, auth()->user()->circles->pluck('id')->map(fn ($item) => (string) $item)->toArray())) {
            return redirect()->route('dashboard.taxpayers');
        }
        // dd(Taxpayer::with('demands')->find($this->taxpayer->id));
        $this->noticeTypes = NoticeType::all();
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

        return view('livewire.dashboard.previous-notices', [
            'results' => $this->results,
            'headers' => $this->headerConfig(),
            'title' => $this->title
        ])->layout('layouts.dashboard');
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
        return Notice::search(trim($this->search))
            ->orderBy($this->sortColumn, $this->sortDirection);
    }

    public function cancel()
    {
        $this->resetInputFields();
    }
}
