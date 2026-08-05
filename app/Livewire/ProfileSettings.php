<?php

namespace App\Livewire;

use App\Models\Purchase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Livewire\Component;

class ProfileSettings extends Component
{
    public $user;
    
    // Profile form
    public $name = '';
    public $email = '';
    
    // Password form
    public $current_password = '';
    public $password = '';
    public $password_confirmation = '';
    
    // UI state
    public $activeTab = 'account';
    
    public function mount()
    {
        $this->user = Auth::user();
        
        if (!$this->user) {
            return redirect()->route('login');
        }
        
        $this->name = $this->user->name;
        $this->email = $this->user->email;
    }
    
    public function updateProfile()
    {
        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', Rule::unique('users')->ignore($this->user->id)],
        ]);
        
        $this->user->fill($validated);

        if ($this->user->isDirty('email')) {
            $this->user->email_verified_at = null;
        }

        $this->user->save();
        
        $this->dispatch('profile-updated');
        session()->flash('profile-success', 'Profile updated successfully.');
    }
    
    public function updatePassword()
    {
        $validated = $this->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
        
        $this->user->update([
            'password' => Hash::make($validated['password']),
        ]);
        
        $this->reset(['current_password', 'password', 'password_confirmation']);
        session()->flash('password-success', 'Password updated successfully.');
    }
    
    public function logout(\App\Livewire\Actions\Logout $logout)
    {
        $logout();
        $this->redirect('/', navigate: true);
    }

    public function render()
    {
        $purchases = Purchase::with('paper.subject')
            ->where('user_id', $this->user->id)
            ->where('status', 'completed')
            ->orderBy('created_at', 'desc')
            ->get();
            
        return view('livewire.profile-settings', [
            'purchases' => $purchases
        ])->layout('layouts.quiz')->title('Account Settings — Papelo');
    }
}
