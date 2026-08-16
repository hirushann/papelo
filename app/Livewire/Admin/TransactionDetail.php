<?php

namespace App\Livewire\Admin;

use App\Models\Subscription;
use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('layouts.admin')]
class TransactionDetail extends Component
{
    public Subscription $subscription;

    public function mount(Subscription $subscription)
    {
        // Load relationships needed for the receipt
        $subscription->load(['user', 'plan']);
        $this->subscription = $subscription;
    }

    public function render()
    {
        return view('livewire.admin.transaction-detail');
    }
}
