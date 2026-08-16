<?php

namespace App\Livewire\Admin;

use App\Models\User;
use App\Models\Purchase;
use App\Models\Attempt;
use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('layouts.admin')]
class UserDetail extends Component
{
    public User $user;
    public $selectedPlanId = '';

    public function mount(User $user)
    {
        $this->user = $user;
    }

    public function grantFreePlan()
    {
        $this->validate([
            'selectedPlanId' => 'required|exists:plans,id',
        ]);

        \App\Models\Subscription::create([
            'user_id' => $this->user->id,
            'plan_id' => $this->selectedPlanId,
            'status' => 'active',
            'ls_subscription_id' => 'manual_grant_' . \Illuminate\Support\Str::random(8),
            'ls_customer_id' => 'manual',
            'current_period_start' => now(),
            'current_period_end' => now()->addYears(10), // essentially lifetime
            'attempts_used' => 0,
        ]);

        $this->selectedPlanId = '';
        
        session()->flash('success', 'Free plan access granted successfully.');
    }

    public function render()
    {
        // 1. Current Plan (Instead of Total Spent)
        $currentSubscription = \App\Models\Subscription::with('plan')
            ->where('user_id', $this->user->id)
            ->whereIn('status', ['active', 'past_due'])
            ->latest()
            ->first();

        // 2. Attempts
        $attemptsCount = Attempt::where('user_id', $this->user->id)
            ->whereNotNull('completed_at')
            ->count();

        // 3. Avg Score
        $avgScore = Attempt::where('user_id', $this->user->id)
            ->whereNotNull('completed_at')
            ->avg('score');

        // History
        $recentAttempts = Attempt::with('paper.subject')
            ->where('user_id', $this->user->id)
            ->orderByDesc('created_at')
            ->limit(10)
            ->get();

        $recentSubscriptions = \App\Models\Subscription::with('plan')
            ->where('user_id', $this->user->id)
            ->orderByDesc('created_at')
            ->limit(10)
            ->get();

        $plans = \App\Models\Plan::where('is_active', true)->orderBy('sort_order')->get();

        return view('livewire.admin.user-detail', [
            'currentSubscription' => $currentSubscription,
            'attemptsCount' => $attemptsCount,
            'avgScore' => $avgScore ? round($avgScore) : 0,
            'recentAttempts' => $recentAttempts,
            'recentSubscriptions' => $recentSubscriptions,
            'plans' => $plans,
        ]);
    }
}
