<?php

namespace App\Livewire;

use App\Models\ContactSubmission;
use Livewire\Component;

class ContactUsForm extends Component
{
    public $name = '';
    public $email = '';
    public $category = 'General question';
    public $message = '';
    
    public $successMessage = '';

    protected $rules = [
        'name' => 'required|min:2',
        'email' => 'required|email',
        'category' => 'required|string',
        'message' => 'required|min:10',
    ];

    public function submit()
    {
        $this->validate();

        ContactSubmission::create([
            'name' => $this->name,
            'email' => $this->email,
            'category' => $this->category,
            'message' => $this->message,
        ]);

        $this->reset(['name', 'email', 'category', 'message']);
        
        $this->successMessage = 'Thank you! Your message has been sent successfully.';
    }

    public function render()
    {
        return view('livewire.contact-us-form');
    }
}
