<?php

use App\Livewire\Forms\LoginForm;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component
{
    public LoginForm $form;

    /**
     * Handle an incoming authentication request.
     */
    public function login(): void
    {
        $this->validate();

        $this->form->authenticate();

        Session::regenerate();

        $redirectRoute = auth()->user()->is_admin ? route('admin.dashboard', absolute: false) : route('papers', absolute: false);
        $this->redirectIntended(default: $redirectRoute, navigate: true);
    }
}; ?>

<div class="flex-1 flex flex-col items-center justify-center px-6 py-12">
    <div class="w-full max-w-sm bg-white rounded-2xl border border-ink/10 shadow-xl shadow-ink/5 p-8">
        <h1 class="font-display text-2xl text-ink mb-1.5">Welcome back</h1>
        <p class="text-sm text-ink/60 mb-7">Log in to pick up where you left off.</p>

        <!-- Session Status -->
        <x-auth-session-status class="mb-4" :status="session('status')" />

        <form wire:submit="login" class="space-y-4">
            <div>
                <label class="block text-xs font-semibold text-ink/70 mb-1.5">Email</label>
                <input wire:model="form.email" type="email" required autofocus placeholder="you@example.com" class="w-full border border-ink/15 rounded-lg py-[0.65rem] px-[0.9rem] text-[0.9rem] text-ink bg-white transition-colors focus:outline-none focus:border-teal focus:ring-[3px] focus:ring-teal/15 shadow-sm">
                <x-input-error :messages="$errors->get('form.email')" class="mt-2" />
            </div>
            
            <div>
                <div class="flex items-center justify-between mb-1.5">
                    <label class="block text-xs font-semibold text-ink/70">Password</label>
                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}" wire:navigate class="text-xs text-teal font-medium hover:underline">Forgot?</a>
                    @endif
                </div>
                <input wire:model="form.password" type="password" required placeholder="••••••••" class="w-full border border-ink/15 rounded-lg py-[0.65rem] px-[0.9rem] text-[0.9rem] text-ink bg-white transition-colors focus:outline-none focus:border-teal focus:ring-[3px] focus:ring-teal/15 shadow-sm">
                <x-input-error :messages="$errors->get('form.password')" class="mt-2" />
            </div>

            <label class="flex items-center gap-2 text-xs text-ink/60">
                <input wire:model="form.remember" type="checkbox" class="rounded border-ink/25 text-teal focus:ring-teal">
                Keep me logged in
            </label>

            <button type="submit" class="w-full rounded-lg bg-teal text-paper font-semibold py-3 hover:bg-teal/90 transition shadow-sm mt-2">Log in</button>
        </form>

        <p class="text-center text-sm text-ink/60 mt-6">Don't have an account? <a href="{{ route('register') }}" wire:navigate class="text-teal font-semibold hover:underline">Sign up</a></p>
    </div>

    <p class="text-center text-xs text-ink/40 mt-6">By continuing, you agree to Papelo's <a href="{{ route('terms') }}" class="underline" target="_blank">Terms</a> and <a href="{{ route('privacy') }}" class="underline" target="_blank">Privacy Policy</a>.</p>
</div>
