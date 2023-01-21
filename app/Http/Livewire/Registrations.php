<?php

namespace App\Http\Livewire;

use Carbon\Carbon;
use App\Models\User;
use App\Helpers\Helper;
use Livewire\Component;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Intervention\Image\ImageManager;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class Registrations extends Component
{
    use WithFileUploads;
    public $title = 'Registration';
    public $createState = true;
    public $state = [];



    protected $rules = [
        'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
        'first_name' => 'required',
        'last_name' => 'required',
        'password' => 'required|string|min:6|confirmed',
        'password_confirmation' => 'required',

    ];

    protected $messages = [];

    public function validateForm()
    {
        // Validator::make($this->state, $this->rules, $this->messages)->validate();
        $validator = Validator::make($this->state, $this->rules, $this->messages);
        if ($validator->fails()) {
            $this->dispatchBrowserEvent('scrollToTop');
        }
        $validator->validate();
    }

    public function mount()
    {
    }

    public function render()
    {
        return view('livewire.registrations')->layout('layouts.frontend');
    }

    public function store(Request $request)
    {
        $this->validateForm();

        DB::transaction(function () use ($request) {
            $user = User::create([
                'name' => $this->state['first_name'] . ' ' . $this->state['last_name'],
                'email' => $this->state['email'],
                'password' => Hash::make($this->state['password'])
            ]);

            $user->profile()->create([
                'first_name' => $this->state['first_name'],
                'last_name' => $this->state['last_name']
            ]);

            session()->flash('success', 'You have been registered successfully.');
            $this->resetInputFields();
            return redirect()->back();
        });

        session()->flash('success', 'You have been registered successfully.');

        return redirect()->back();
    }

    private function resetInputFields()
    {
        $this->reset(['state', 'createState']);
        $this->resetErrorBag();
    }
}
