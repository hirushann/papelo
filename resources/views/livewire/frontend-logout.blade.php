<?php

use App\Livewire\Actions\Logout;
use Livewire\Volt\Component;

new class extends Component
{
    public function logout(Logout $logout): void
    {
        $logout();
        $this->redirect('/', navigate: true);
    }
}; ?>

<button wire:click="logout" class="text-sm font-medium text-ink/70 hover:text-ink cursor-pointer">
    Log out
</button>
