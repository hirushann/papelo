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

    public function mount(User $user)
    {
        $this->user = $user;
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

        return view('livewire.admin.user-detail', [
            'currentSubscription' => $currentSubscription,
            'attemptsCount' => $attemptsCount,
            'avgScore' => $avgScore ? round($avgScore) : 0,
            'recentAttempts' => $recentAttempts,
            'recentSubscriptions' => $recentSubscriptions,
        ]);
    }
}
