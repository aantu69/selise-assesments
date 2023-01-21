<?php

namespace App\Http\Livewire\Admin;

use App\Models\Book;
use App\Helpers\Helper;
use Livewire\Component;
use Illuminate\Support\Str;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Cache;
use App\Http\Livewire\Traits\SearchSort;
use Illuminate\Support\Facades\Validator;

class Books extends Component
{
    use WithFileUploads;
    use SearchSort;
    public $title = 'Books';
    public $createButton = true;

    public $viewState = true;
    public $createState = false;
    public $updateState = false;

    public $state = [], $book, $photo;

    protected $listeners = ['destroy'];

    protected $rules = [
        'name' => 'required',
        'author_name' => 'required',
        'price' => 'required',
    ];

    protected $messages = [];

    public function mount()
    {
    }

    public function validateForm()
    {
        Validator::make($this->state, $this->rules, $this->messages)->validate();
    }

    private function headerConfig()
    {
        return [
            [
                'data' => 'name', 'label' => 'Book Name', 'order' => true,
                'func' => function ($value) {
                    return $value;
                }
            ],
            [
                'data' => 'author', 'label' => 'Author Name', 'order' => false,
                'func' => function ($value) {
                    return $value;
                }
            ],
            [
                'data' => 'price', 'label' => 'Price', 'order' => false,
                'func' => function ($value) {
                    return $value;
                }
            ],

            [
                'data' => 'created_at', 'label' => 'Created At', 'order' => true,
                'func' => function ($value) {
                    return $value->diffForHumans();
                }
            ],
            [
                'data' => 'updated_at', 'label' => 'Updated At', 'order' => true,
                'func' => function ($value) {
                    return $value->diffForHumans();
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
        return view('livewire.admin.books', [
            'results' => $this->results,
            'headers' => $this->headerConfig(),
            'title' => $this->title
        ])->layout('layouts.admin');
    }

    public function store()
    {
        $this->state['photo'] = $this->photo;
        $this->rules = array_merge($this->rules, [
            'photo' => ['required', 'image', 'mimes:jpg,jpeg,png'],
        ]);
        // $this->validateForm();
        // dd($this->state);
        if ($this->photo) {
            $this->uploadPhoto();
        }
        $book = Book::create($this->state);
        session()->flash('success', 'Data has been created successfully.');
        $this->resetInputFields();
    }

    public function edit(Book $book)
    {
        $this->updateState = true;
        $this->viewState = false;
        $this->state = $book->toArray();
        $this->book = $book;
    }

    public function update()
    {
        if ($this->photo) {
            $this->state['photo'] = $this->photo;
            $this->rules = array_merge($this->rules, [
                'photo' => ['required', 'image', 'mimes:jpg,jpeg,png'],
            ]);
        }
        $this->validateForm();
        if ($this->photo) {
            $this->uploadPhoto();
        }
        $this->book->update($this->state);
        session()->flash('success', 'Data has been updated successfully.');
        $this->resetInputFields();
    }

    public function confirmDelete($id)
    {
        $this->dispatchBrowserEvent('swal:confirm', ['id' => $id]);
    }

    public function destroy(Book $book)
    {
        $book->delete();
        session()->flash('success', 'Data has been deleted successfully.');
        $this->resetInputFields();
    }

    private function resetInputFields()
    {
        $this->reset(['state', 'createState', 'updateState', 'viewState', 'book', 'photo']);
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
        return Book::query()
            ->where(function ($query) {
                if ($this->search != "") {
                    $query->where('name', 'like', '%' . $this->search . '%');
                }
            })
            ->orderBy($this->sortColumn, $this->sortDirection);
    }

    public function create()
    {
        $this->createState = true;
        $this->viewState = false;
    }

    public function cancel()
    {
        $this->resetInputFields();
    }

    public function uploadPhoto()
    {
        if ($this->photo) {
            $time = time();
            $fileName = (string) Str::of($time)
                ->append('_photo.')
                ->append($this->photo->getClientOriginalExtension());

            $this->photo->storeAs('public/photos', $fileName);

            $this->state['photo'] = $fileName;
        }
    }
}
