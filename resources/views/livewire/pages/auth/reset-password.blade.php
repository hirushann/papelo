<?php

use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Locked;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component
{
    #[Locked]
    public string $token = '';
    public string $email = '';
    public string $password = '';
    public string $password_confirmation = '';

    /**
     * Mount the component.
     */
    public function mount(string $token): void
    {
        $this->token = $token;

        $this->email = request()->string('email');
    }

    /**
     * Reset the password for the given user.
     */
    public function resetPassword(): void
    {
        $this->validate([
            'token' => ['required'],
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string', 'confirmed', Rules\Password::defaults()],
        ]);

        // Here we will attempt to reset the user's password. If it is successful we
        // will update the password on an actual user model and persist it to the
        // database. Otherwise we will parse the error and return the response.
        $status = Password::reset(
            $this->only('email', 'password', 'password_confirmation', 'token'),
            function ($user) {
                $user->forceFill([
                    'password' => Hash::make($this->password),
                    'remember_token' => Str::random(60),
                ])->save();

                event(new PasswordReset($user));
            }
        );

        // If the password was successfully reset, we will redirect the user back to
        // the application's home authenticated view. If there is an error we can
        // redirect them back to where they came from with their error message.
        if ($status != Password::PASSWORD_RESET) {
            $this->addError('email', __($status));

            return;
        }

        Session::flash('status', __($status));

        $this->redirectRoute('login', navigate: true);
    }
}; ?>

<div class="flex-1 flex flex-col items-center justify-center px-6 py-12">
    <div class="bg-white rounded-2xl w-full max-w-sm p-8 shadow-xl shadow-ink/5 border border-ink/10 relative">
        <div class="w-12 h-12 rounded-full bg-teal/10 flex items-center justify-center mb-4">
            <svg class="w-5 h-5 text-teal" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="11" width="18" height="10" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/><circle cx="12" cy="16" r="1"/></svg>
        </div>
        <h2 class="font-display text-2xl text-ink mb-1.5">Set a new password</h2>
        <p class="text-sm text-ink/60 mb-6">Choose a new password for your account.</p>
        
        <form wire:submit="resetPassword">
            <div class="space-y-4 mb-6">
                <!-- Email Address -->
                <div>
                    <label class="block text-xs font-semibold text-ink/70 mb-1.5">Email</label>
                    <input wire:model="email" type="email" required autofocus autocomplete="username" class="w-full border border-ink/15 rounded-lg py-[0.65rem] px-[0.9rem] text-[0.9rem] text-ink bg-white transition-colors focus:outline-none focus:border-teal focus:ring-[3px] focus:ring-teal/15 shadow-sm">
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                <!-- Password -->
                <div>
                    <label class="block text-xs font-semibold text-ink/70 mb-1.5">New password</label>
                    <input wire:model="password" type="password" required autocomplete="new-password" placeholder="At least 8 characters" class="w-full border border-ink/15 rounded-lg py-[0.65rem] px-[0.9rem] text-[0.9rem] text-ink bg-white transition-colors focus:outline-none focus:border-teal focus:ring-[3px] focus:ring-teal/15 shadow-sm">
                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>

                <!-- Confirm Password -->
                <div>
                    <label class="block text-xs font-semibold text-ink/70 mb-1.5">Confirm new password</label>
                    <input wire:model="password_confirmation" type="password" required autocomplete="new-password" placeholder="Re-enter password" class="w-full border border-ink/15 rounded-lg py-[0.65rem] px-[0.9rem] text-[0.9rem] text-ink bg-white transition-colors focus:outline-none focus:border-teal focus:ring-[3px] focus:ring-teal/15 shadow-sm">
                    <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                </div>
            </div>
            
            <button type="submit" class="w-full rounded-lg bg-teal text-paper font-semibold py-3 hover:bg-teal/90 transition shadow-sm">Reset password</button>
        </form>
    </div>
</div>
