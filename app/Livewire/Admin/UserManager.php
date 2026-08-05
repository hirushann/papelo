<?php

namespace App\Livewire\Admin;

use App\Models\User;
use App\Models\Attempt;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;

#[Layout('layouts.admin', ['header' => 'Manage Users'])]
class UserManager extends Component
{
    use WithPagination;

    public $search = '';
    public $levelFilter = '';
    public $planFilter = '';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function setLevelFilter($level)
    {
        $this->levelFilter = $level;
        $this->resetPage();
    }

    public function setPlanFilter($plan)
    {
        $this->planFilter = $plan;
        $this->resetPage();
    }

    public function render()
    {
        $query = User::where('is_admin', false)
            ->withCount('attempts');

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('email', 'like', '%' . $this->search . '%');
            });
        }

        // We do not have native db columns for plan/level, so filtering by them in DB is tricky,
        // but we can mock it or leave the UI filters as visual placeholders. 
        // For plan filtering based on purchases:
        if ($this->planFilter === 'pay-per-paper') {
            $query->has('purchases');
        } elseif ($this->planFilter === 'no-purchases') {
            $query->doesntHave('purchases');
        }

        $users = $query->latest()->paginate(10);

        // Stats
        $totalStudents = User::where('is_admin', false)->count();
        $newThisWeek = User::where('is_admin', false)->where('created_at', '>=', now()->subWeek())->count();
        $totalAttempts = Attempt::count();
        $avgAttempts = $totalStudents > 0 ? round($totalAttempts / $totalStudents, 1) : 0;
        
        return view('livewire.admin.user-manager', [
            'users' => $users,
            'totalStudents' => $totalStudents,
            'newThisWeek' => $newThisWeek,
            'avgAttempts' => $avgAttempts,
        ]);
    }
}
