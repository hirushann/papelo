<?php

namespace App\Livewire\Admin;

use App\Models\Purchase;
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
        $baseStatQuery = Purchase::where('created_at', '>=', $dateThreshold);
        
        $revenue = (clone $baseStatQuery)->where('status', 'completed')->sum('amount_paid');
        $successfulCount = (clone $baseStatQuery)->where('status', 'completed')->count();
        $failedCount = (clone $baseStatQuery)->where('status', 'failed')->count();
        $refundedCount = 0; // Mocked as we don't have this status
        $refundedAmount = 0; // Mocked

        // Main table query
        $query = Purchase::with(['user', 'paper'])
            ->where('created_at', '>=', $dateThreshold);

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('payhere_order_id', 'like', '%' . $this->search . '%')
                  ->orWhereHas('user', function ($uq) {
                      $uq->where('name', 'like', '%' . $this->search . '%')
                         ->orWhere('email', 'like', '%' . $this->search . '%');
                  });
            });
        }

        if ($this->statusFilter) {
            if ($this->statusFilter === 'refunded') {
                // Mock: return empty or something, as we don't have refunded
                $query->where('status', 'refunded'); 
            } else {
                $query->where('status', $this->statusFilter);
            }
        }

        $purchases = $query->latest()->paginate(15);

        return view('livewire.admin.payment-manager', [
            'purchases' => $purchases,
            'revenue' => $revenue,
            'successfulCount' => $successfulCount,
            'failedCount' => $failedCount,
            'refundedAmount' => $refundedAmount,
        ]);
    }
}
