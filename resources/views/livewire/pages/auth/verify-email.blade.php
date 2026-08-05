<?php

use App\Livewire\Actions\Logout;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component
{
    /**
     * Send an email verification notification to the user.
     */
    public function sendVerification(): void
    {
        if (Auth::user()->hasVerifiedEmail()) {
            $this->redirectIntended(default: route('dashboard', absolute: false), navigate: true);

            return;
        }

        Auth::user()->sendEmailVerificationNotification();

        Session::flash('status', 'verification-link-sent');
    }

    /**
     * Log the current user out of the application.
     */
    public function logout(Logout $logout): void
    {
        $logout();

        $this->redirect('/', navigate: true);
    }
}; ?>

<div class="flex-1 flex flex-col items-center justify-center px-6 py-12">
    <div class="bg-white rounded-2xl w-full max-w-sm p-8 text-center shadow-xl shadow-ink/5 border border-ink/10 relative">
        <div class="w-12 h-12 rounded-full bg-teal/10 flex items-center justify-center mx-auto mb-4">
            <svg class="w-5 h-5 text-teal" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="5" width="18" height="14" rx="2"/><path d="m3 7 9 6 9-6"/></svg>
        </div>
        <h2 class="font-display text-2xl text-ink mb-1.5">Check your inbox</h2>
        <p class="text-sm text-ink/60 mb-6">We've sent a verification link to <span class="font-medium text-ink">{{ auth()->user()->email }}</span>. Click it to activate your account.</p>
        
        @if (session('status') == 'verification-link-sent')
            <div class="mb-4 font-medium text-sm text-teal bg-teal/10 p-2 rounded-lg">
                A new verification link has been sent!
            </div>
        @endif

        <button wire:click="sendVerification" class="w-full rounded-lg bg-teal text-paper font-semibold py-3 hover:bg-teal/90 transition mb-3 shadow-sm">Resend email</button>
        <a href="{{ auth()->user()->is_admin ? route('admin.dashboard') : route('dashboard') }}" class="block w-full text-center text-sm font-medium text-ink/60 hover:text-ink">Continue to dashboard</a>
        
        <p class="text-xs text-ink/40 mt-6 mb-2">Didn't get it? Check your spam folder, or use the button above to resend.</p>
        
        <button wire:click="logout" class="text-xs text-margin/70 hover:text-margin underline">Log out</button>
    </div>
</div>
