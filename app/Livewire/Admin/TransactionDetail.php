<?php

namespace App\Livewire\Admin;

use App\Models\Purchase;
use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('layouts.admin')]
class TransactionDetail extends Component
{
    public Purchase $purchase;

    public function mount(Purchase $purchase)
    {
        // Load relationships needed for the receipt
        $purchase->load(['user', 'paper.subject']);
        $this->purchase = $purchase;
    }

    public function render()
    {
        return view('livewire.admin.transaction-detail');
    }
}
