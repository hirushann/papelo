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
    public $recaptchaToken = '';
    
    public $successMessage = '';

    protected $rules = [
        'name' => 'required|min:2',
        'email' => 'required|email',
        'category' => 'required|string',
        'message' => 'required|min:10',
        'recaptchaToken' => 'required',
    ];

    protected $messages = [
        'recaptchaToken.required' => 'Please complete the reCAPTCHA verification.',
    ];

    public function submit()
    {
        $this->validate();

        $response = \Illuminate\Support\Facades\Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
            'secret' => env('RECAPTCHA_SECRET_KEY'),
            'response' => $this->recaptchaToken,
        ]);

        if (! $response->json('success')) {
            $this->addError('recaptchaToken', 'reCAPTCHA verification failed. Please try again.');
            $this->recaptchaToken = '';
            $this->dispatch('reset-recaptcha');
            return;
        }

        ContactSubmission::create([
            'name' => $this->name,
            'email' => $this->email,
            'category' => $this->category,
            'message' => $this->message,
        ]);

        $this->reset(['name', 'email', 'category', 'message', 'recaptchaToken']);
        $this->dispatch('reset-recaptcha');
        
        $this->successMessage = 'Thank you! Your message has been sent successfully.';
    }

    public function render()
    {
        return view('livewire.contact-us-form');
    }
}
