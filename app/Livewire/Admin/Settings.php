<?php

namespace App\Livewire\Admin;

use App\Models\Setting;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Attributes\Computed;

#[Layout('layouts.admin', ['header' => 'Settings'])]
class Settings extends Component
{
    // General
    public string $platformName = 'Papelooo';
    public string $supportEmail = 'contact@papelooo.com';
    public string $timezone = 'Asia/Colombo (GMT+5:30)';
    public string $currency = 'LKR — Sri Lankan Rupee';

    // Payment — Lemon Squeezy
    public string $lsApiKey = '';
    public string $lsSigningSecret = '';
    public string $lsStoreId = '';
    
    // Pricing Defaults
    public int $defaultPrice = 100;
    public int $defaultSubscription = 990;

    // Notifications
    public bool $notifyNewStudent = true;
    public bool $notifyFailedPayment = true;
    public bool $notifyNewPaper = false;
    public bool $notifyWeeklySummary = true;

    public string $successMessage = '';

    // Admin Invite
    public string $inviteName = '';
    public string $inviteEmail = '';
    public string $invitePassword = '';

    #[Computed]
    public function admins()
    {
        return \App\Models\User::where('is_admin', true)->orderBy('created_at', 'asc')->get();
    }

    public function mount()
    {
        $settings = Setting::pluck('value', 'key')->toArray();

        // General
        $this->platformName = $settings['platformName'] ?? $this->platformName;
        $this->supportEmail = $settings['supportEmail'] ?? $this->supportEmail;
        $this->timezone = $settings['timezone'] ?? $this->timezone;
        $this->currency = $settings['currency'] ?? $this->currency;

        // Payment — Lemon Squeezy
        $this->lsApiKey = $settings['lsApiKey'] ?? $this->lsApiKey;
        $this->lsSigningSecret = $settings['lsSigningSecret'] ?? $this->lsSigningSecret;
        $this->lsStoreId = $settings['lsStoreId'] ?? $this->lsStoreId;

        // Pricing Defaults
        $this->defaultPrice = $settings['defaultPrice'] ?? $this->defaultPrice;
        $this->defaultSubscription = $settings['defaultSubscription'] ?? $this->defaultSubscription;

        // Notifications (cast string to boolean if stored in DB)
        $this->notifyNewStudent = isset($settings['notifyNewStudent']) ? (bool)$settings['notifyNewStudent'] : $this->notifyNewStudent;
        $this->notifyFailedPayment = isset($settings['notifyFailedPayment']) ? (bool)$settings['notifyFailedPayment'] : $this->notifyFailedPayment;
        $this->notifyNewPaper = isset($settings['notifyNewPaper']) ? (bool)$settings['notifyNewPaper'] : $this->notifyNewPaper;
        $this->notifyWeeklySummary = isset($settings['notifyWeeklySummary']) ? (bool)$settings['notifyWeeklySummary'] : $this->notifyWeeklySummary;
    }

    #[On('save-settings')]
    public function saveSettings()
    {
        $keys = [
            'platformName', 'supportEmail', 'timezone', 'currency',
            'lsApiKey', 'lsSigningSecret', 'lsStoreId',
            'defaultPrice', 'defaultSubscription',
            'notifyNewStudent', 'notifyFailedPayment', 'notifyNewPaper', 'notifyWeeklySummary'
        ];

        foreach ($keys as $key) {
            Setting::updateOrCreate(
                ['key' => $key],
                ['value' => $this->$key]
            );
        }

        $this->successMessage = 'Settings saved successfully!';
        $this->dispatch('settings-saved');
    }

    public function exportData()
    {
        // Mock export logic
        $this->successMessage = 'Data export started. You will receive an email shortly.';
        $this->dispatch('export-started');
    }

    public function inviteAdmin()
    {
        $this->validate([
            'inviteName' => 'required|string|max:255',
            'inviteEmail' => 'required|email|unique:users,email',
            'invitePassword' => 'required|string|min:8',
        ]);

        \App\Models\User::create([
            'name' => $this->inviteName,
            'email' => strtolower($this->inviteEmail),
            'password' => \Illuminate\Support\Facades\Hash::make($this->invitePassword),
            'is_admin' => true,
        ]);

        $this->reset(['inviteName', 'inviteEmail', 'invitePassword']);
        $this->dispatch('admin-invited');
        \Flux::modal('invite-admin')->close();
    }

    public function render()
    {
        return view('livewire.admin.settings');
    }
}
