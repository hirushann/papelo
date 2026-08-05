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
        // 1. Total spent
        $totalSpent = Purchase::where('user_id', $this->user->id)
            ->where('status', 'completed')
            ->sum('amount_paid');

        // 2. Attempts
        $attemptsCount = Attempt::where('user_id', $this->user->id)
            ->whereNotNull('completed_at')
            ->count();

        // 3. Avg Score
        $avgScore = Attempt::where('user_id', $this->user->id)
            ->whereNotNull('completed_at')
            ->avg('score');

        // 4. Papers Owned
        $papersOwned = Purchase::where('user_id', $this->user->id)
            ->where('status', 'completed')
            ->distinct('paper_id')
            ->count('paper_id');

        // History
        $recentAttempts = Attempt::with('paper.subject')
            ->where('user_id', $this->user->id)
            ->orderByDesc('created_at')
            ->limit(10)
            ->get();

        $recentPurchases = Purchase::with('paper')
            ->where('user_id', $this->user->id)
            ->orderByDesc('created_at')
            ->limit(10)
            ->get();

        return view('livewire.admin.user-detail', [
            'totalSpent' => $totalSpent,
            'attemptsCount' => $attemptsCount,
            'avgScore' => $avgScore ? round($avgScore) : 0,
            'papersOwned' => $papersOwned,
            'recentAttempts' => $recentAttempts,
            'recentPurchases' => $recentPurchases,
        ]);
    }
}
