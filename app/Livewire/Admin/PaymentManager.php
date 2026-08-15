<?php

namespace App\Livewire\Admin;

use App\Models\Subscription;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;

#[Layout('layouts.admin', ['header' => 'Payments'])]
class PaymentManager extends Component
{
    use WithPagination;

    public $search = '';
    public $statusFilter = '';
    public $timeRange = '30';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function setStatusFilter($status)
    {
        $this->statusFilter = $status;
        $this->resetPage();
    }

    public function render()
    {
        $dateThreshold = match($this->timeRange) {
            '7' => now()->subDays(7),
            '30' => now()->subDays(30),
            '90' => now()->subDays(90),
            default => now()->subDays(30),
        };

        // Query for stats for the selected time range
        $baseStatQuery = Subscription::where('subscriptions.created_at', '>=', $dateThreshold);
        
        $revenue = (clone $baseStatQuery)->where('subscriptions.status', 'active')
            ->join('plans', 'subscriptions.plan_id', '=', 'plans.id')
            ->sum('plans.price');
        $activeCount = (clone $baseStatQuery)->where('subscriptions.status', 'active')->count();
        $cancelledCount = (clone $baseStatQuery)->where('subscriptions.status', 'cancelled')->count();
        $pastDueCount = (clone $baseStatQuery)->where('subscriptions.status', 'past_due')->count();

        // Main table query
        $query = Subscription::with(['user', 'plan'])
            ->where('subscriptions.created_at', '>=', $dateThreshold);

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('ls_subscription_id', 'like', '%' . $this->search . '%')
                  ->orWhereHas('user', function ($uq) {
                      $uq->where('name', 'like', '%' . $this->search . '%')
                         ->orWhere('email', 'like', '%' . $this->search . '%');
                  });
            });
        }

        if ($this->statusFilter) {
            $query->where('status', $this->statusFilter);
        }

        $subscriptions = $query->latest()->paginate(15);

        return view('livewire.admin.payment-manager', [
            'subscriptions' => $subscriptions,
            'revenue' => $revenue,
            'activeCount' => $activeCount,
            'cancelledCount' => $cancelledCount,
            'pastDueCount' => $pastDueCount,
        ]);
    }
}
