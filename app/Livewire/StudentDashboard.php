<?php

namespace App\Livewire;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use App\Models\Purchase;
use App\Models\Attempt;

class StudentDashboard extends Component
{
    public function render()
    {
        $user = Auth::user();

        // Calculate stats
        $totalPurchased = $user->purchases()->where('status', 'completed')->count();
        $totalAttempts = $user->attempts()->count();
        $averageScore = $totalAttempts > 0 ? $user->attempts()->avg('score') : 0;
        
        // Let's assume average score is percentage if total_questions is consistent, 
        // but since score is absolute, let's calculate average percentage score.
        $averagePercentage = 0;
        if ($totalAttempts > 0) {
            $attempts = $user->attempts()->get();
            $totalPercentage = 0;
            foreach ($attempts as $attempt) {
                if ($attempt->total_questions > 0) {
                    $totalPercentage += ($attempt->score / $attempt->total_questions) * 100;
                }
            }
            $averagePercentage = round($totalPercentage / $totalAttempts, 1);
        }

        // Recent Purchases (Continue Learning)
        $recentPurchases = Purchase::with(['paper.subject'])
            ->where('user_id', $user->id)
            ->where('status', 'completed')
            ->orderBy('created_at', 'desc')
            ->take(3)
            ->get();

        // Recent Attempts
        $recentAttempts = Attempt::with(['paper.subject'])
            ->where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        return view('livewire.student-dashboard', [
            'totalPurchased' => $totalPurchased,
            'totalAttempts' => $totalAttempts,
            'averagePercentage' => $averagePercentage,
            'recentPurchases' => $recentPurchases,
            'recentAttempts' => $recentAttempts,
        ]);
    }
}
