<?php

use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component
{
    public string $name = '';
    public string $email = '';
    public string $password = '';
    public string $level = 'scholarship';
    public bool $terms = false;

    /**
     * Handle an incoming registration request.
     */
    public function register(): void
    {
        \Illuminate\Support\Facades\Log::info('REGISTER: method called', [
            'name' => $this->name,
            'email' => $this->email,
            'level' => $this->level,
            'terms' => $this->terms,
            'password_length' => strlen($this->password),
        ]);

        try {
            $validated = $this->validate([
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'email', 'max:255', 'unique:'.User::class],
                'password' => ['required', 'string', Rules\Password::defaults()],
                'level' => ['required', 'in:scholarship,ol,al'],
                'terms' => ['accepted'],
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            \Illuminate\Support\Facades\Log::error('REGISTER: Validation Failed', $e->errors());
            throw $e;
        }

        \Illuminate\Support\Facades\Log::info('REGISTER: validation passed', ['validated' => $validated]);

        $user_data = [
            'name' => $validated['name'],
            'email' => strtolower($validated['email']),
            'password' => Hash::make($validated['password']),
            'level' => $validated['level'],
        ];

        \Illuminate\Support\Facades\Log::info('REGISTER: creating user', ['user_data_keys' => array_keys($user_data), 'level' => $user_data['level']]);

        $user = User::create($user_data);

        \Illuminate\Support\Facades\Log::info('REGISTER: user created', ['user_id' => $user->id, 'level_in_db' => $user->level]);

        event(new Registered($user));
        Auth::login($user);

        // Notify admins if this is a student
        if (!$user->is_admin) {
            \Illuminate\Support\Facades\Notification::send(
                User::where('is_admin', true)->get(),
                new \App\Notifications\AdminNewStudentNotification($user)
            );
        }

        $redirectRoute = $user->is_admin ? route('admin.dashboard', absolute: false) : route('papers', absolute: false);
        $this->redirect($redirectRoute, navigate: true);
    }
}; ?>
<div>
<style>
    .level-option input { display: none; }
    .level-option span {
        display: block;
        text-align: center;
        border: 1px solid rgba(34,49,74,0.15);
        border-radius: 0.5rem;
        padding: 0.55rem 0.5rem;
        font-size: 0.8rem;
        font-weight: 600;
        color: rgba(34,49,74,0.6);
        cursor: pointer;
        transition: all .15s;
    }
    .level-option input:checked + span {
        border-color: #3F7D6B;
        border-width: 2px;
        background: rgba(63,125,107,0.1);
        color: #22314A;
    }
</style>
<div class="flex-1 flex items-center justify-center px-6 py-12">
    <div class="w-full max-w-sm bg-white rounded-2xl border border-ink/10 shadow-xl shadow-ink/5 p-8">
        <h1 class="font-display text-2xl text-ink mb-1.5">Create your account</h1>
        <p class="text-sm text-ink/60 mb-6">Start practicing in under a minute.</p>

        <form wire:submit.prevent="register" class="space-y-4">
            <div>
                <label class="block text-xs font-semibold text-ink/70 mb-1.5">Full name</label>
                <input wire:model="name" type="text" required autofocus placeholder="Nimal Perera" class="w-full border border-ink/15 rounded-lg py-[0.65rem] px-[0.9rem] text-[0.9rem] text-ink bg-white transition-colors focus:outline-none focus:border-teal focus:ring-[3px] focus:ring-teal/15 shadow-sm">
                <x-input-error :messages="$errors->get('name')" class="mt-2" />
            </div>
            
            <div>
                <label class="block text-xs font-semibold text-ink/70 mb-1.5">Email</label>
                <input wire:model="email" type="email" required placeholder="you@example.com" class="w-full border border-ink/15 rounded-lg py-[0.65rem] px-[0.9rem] text-[0.9rem] text-ink bg-white transition-colors focus:outline-none focus:border-teal focus:ring-[3px] focus:ring-teal/15 shadow-sm">
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>
            
            <div>
                <label class="block text-xs font-semibold text-ink/70 mb-1.5">Password</label>
                <input wire:model="password" type="password" required placeholder="At least 8 characters" class="w-full border border-ink/15 rounded-lg py-[0.65rem] px-[0.9rem] text-[0.9rem] text-ink bg-white transition-colors focus:outline-none focus:border-teal focus:ring-[3px] focus:ring-teal/15 shadow-sm">
                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            <div>
                <label class="block text-xs font-semibold text-ink/70 mb-2">What are you preparing for?</label>
                <div class="grid grid-cols-3 gap-2">
                    <label class="level-option">
                        <input wire:model="level" name="level" type="radio" value="scholarship">
                        <span>Grade 5<br>Scholarship</span>
                    </label>
                    <label class="level-option">
                        <input wire:model="level" name="level" type="radio" value="ol">
                        <span>O/L</span>
                    </label>
                    <label class="level-option">
                        <input wire:model="level" name="level" type="radio" value="al">
                        <span>A/L</span>
                    </label>
                </div>
                <x-input-error :messages="$errors->get('level')" class="mt-2" />
            </div>

            <div>
                <label class="flex items-start gap-2 text-xs text-ink/60 pt-1">
                    <input wire:model="terms" type="checkbox" required class="rounded border-ink/25 text-teal focus:ring-teal mt-0.5">
                    <span>I agree to Papelooo's <a href="{{ route('terms') }}" class="text-teal underline" target="_blank">Terms</a> and <a href="{{ route('privacy') }}" class="text-teal underline" target="_blank">Privacy Policy</a></span>
                </label>
                <x-input-error :messages="$errors->get('terms')" class="mt-2" />
            </div>

            <button type="submit" class="w-full rounded-lg bg-teal text-paper font-semibold py-3 hover:bg-teal/90 transition mt-2 shadow-sm">Create account</button>
        </form>

        <p class="text-center text-sm text-ink/60 mt-6">Already have an account? <a href="{{ route('login') }}" wire:navigate class="text-teal font-semibold hover:underline">Log in</a></p>
    </div>
</div>
</div>
