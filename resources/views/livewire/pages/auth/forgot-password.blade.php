<?php

use Illuminate\Support\Facades\Password;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component
{
    public string $email = '';

    /**
     * Send a password reset link to the provided email address.
     */
    public function sendPasswordResetLink(): void
    {
        $this->validate([
            'email' => ['required', 'string', 'email'],
        ]);

        // We will send the password reset link to this user. Once we have attempted
        // to send the link, we will examine the response then see the message we
        // need to show to the user. Finally, we'll send out a proper response.
        $status = Password::sendResetLink(
            $this->only('email')
        );

        if ($status != Password::RESET_LINK_SENT) {
            $this->addError('email', __($status));

            return;
        }

        $this->reset('email');

        session()->flash('status', __($status));
    }
}; ?>

<div class="flex-1 flex flex-col items-center justify-center px-6 py-12">
    <div class="bg-white rounded-2xl w-full max-w-sm p-8 shadow-xl shadow-ink/5 border border-ink/10 relative">
        <div class="w-12 h-12 rounded-full bg-teal/10 flex items-center justify-center mb-4">
            <svg class="w-5 h-5 text-teal" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="11" width="18" height="10" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
        </div>
        <h2 class="font-display text-2xl text-ink mb-1.5">Forgot your password?</h2>
        <p class="text-sm text-ink/60 mb-6">Enter your email and we'll send you a link to reset it.</p>
        
        <!-- Session Status -->
        <x-auth-session-status class="mb-4" :status="session('status')" />

        <form wire:submit="sendPasswordResetLink">
            <div class="mb-5">
                <label class="block text-xs font-semibold text-ink/70 mb-1.5">Email</label>
                <input wire:model="email" type="email" required autofocus placeholder="you@example.com" class="w-full border border-ink/15 rounded-lg py-[0.65rem] px-[0.9rem] text-[0.9rem] text-ink bg-white transition-colors focus:outline-none focus:border-teal focus:ring-[3px] focus:ring-teal/15 shadow-sm">
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>
            <button type="submit" class="w-full rounded-lg bg-teal text-paper font-semibold py-3 hover:bg-teal/90 transition mb-3 shadow-sm">Send reset link</button>
            <a href="{{ route('login') }}" wire:navigate class="block w-full text-center text-sm font-medium text-ink/60 hover:text-ink">Back to login</a>
        </form>
    </div>
</div>
