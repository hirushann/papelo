<?php

namespace App\Livewire;

use App\Models\Attempt;
use App\Models\Paper;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class StudentDashboard extends Component
{
    public $user;
    public $incompleteAttempt = null;
    public $recentAttempts = [];
    public $stats = [
        'papers_attempted' => 0,
        'average_score' => 0,
        'papers_this_week' => 0,
    ];
    public $suggestedPaper = null;

    public function mount()
    {
        $this->user = Auth::user();

        if (!$this->user) {
            return redirect()->route('login');
        }

        // 1. Redirect admins to the admin dashboard
        if ($this->user->is_admin) {
            return redirect()->route('admin.dashboard');
        }

        // 2. Check for incomplete attempt
        $this->incompleteAttempt = Attempt::with('paper')
            ->where('user_id', $this->user->id)
            ->whereNull('completed_at')
            ->orderBy('started_at', 'desc')
            ->first();

        // 2. Load Stats
        $completedAttemptsQuery = Attempt::where('user_id', $this->user->id)
            ->whereNotNull('completed_at');
        
        $this->stats['papers_attempted'] = $completedAttemptsQuery->count();
        $this->stats['average_score'] = $this->stats['papers_attempted'] > 0 
            ? round($completedAttemptsQuery->avg('score')) 
            : 0;
            
        $this->stats['papers_this_week'] = Attempt::where('user_id', $this->user->id)
            ->where('started_at', '>=', Carbon::now()->subDays(7))
            ->count();

        // 3. Load Recent Scores
        $this->recentAttempts = Attempt::with('paper')
            ->where('user_id', $this->user->id)
            ->whereNotNull('completed_at')
            ->orderBy('completed_at', 'desc')
            ->take(4)
            ->get();

        // 4. Suggested Next Paper (Fallback logic: latest published paper they haven't taken)
        $attemptedPaperIds = Attempt::where('user_id', $this->user->id)
            ->pluck('paper_id')
            ->toArray();
            
        $this->suggestedPaper = Paper::where('is_published', true)
            ->whereNotIn('id', $attemptedPaperIds)
            ->latest()
            ->first();
    }

    public function logout(\App\Livewire\Actions\Logout $logout)
    {
        $logout();
        $this->redirect('/', navigate: true);
    }

    public function render()
    {
        return view('livewire.student-dashboard')
            ->layout('layouts.quiz');
    }
}
