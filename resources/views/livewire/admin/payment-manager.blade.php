<div class="space-y-5">

    <x-slot name="headerActions">
        <span class="text-xs text-ink/40 hidden md:inline">via PayHere</span>
        <button class="inline-flex items-center gap-1.5 rounded-lg border border-ink/15 text-ink/70 text-sm font-semibold px-4 py-2 hover:border-teal/40 hover:text-teal transition">
          <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 3v13m0 0-4-4m4 4 4-4"/><path d="M4 19h16"/></svg>
          Export CSV
        </button>
    </x-slot>

    <!-- STATS -->
    <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-5">
        <div class="bg-white rounded-2xl border border-ink/10 p-5">
          <p class="text-xs font-medium text-ink/50 mb-2">Revenue this period</p>
          <p class="font-display text-2xl text-ink">Rs. {{ number_format($revenue) }}</p>
        </div>
        <div class="bg-white rounded-2xl border border-ink/10 p-5">
          <p class="text-xs font-medium text-ink/50 mb-2">Successful payments</p>
          <p class="font-display text-2xl text-ink">{{ number_format($successfulCount) }}</p>
        </div>
        <div class="bg-white rounded-2xl border border-ink/10 p-5">
          <p class="text-xs font-medium text-ink/50 mb-2">Failed payments</p>
          <p class="font-display text-2xl text-margin">{{ number_format($failedCount) }}</p>
        </div>
        <div class="bg-white rounded-2xl border border-ink/10 p-5">
          <p class="text-xs font-medium text-ink/50 mb-2">Refunded (Mock)</p>
          <p class="font-display text-2xl text-ink">Rs. {{ number_format($refundedAmount) }}</p>
        </div>
    </div>

    <!-- TOOLBAR -->
    <div class="flex flex-wrap items-center justify-between gap-3">
        <div class="flex items-center gap-2">
          <button wire:click="setStatusFilter('')" class="text-xs font-semibold rounded-full px-3.5 py-1.5 {{ $statusFilter === '' ? 'bg-ink text-paper' : 'border border-ink/15 text-ink/60 hover:border-teal/40' }}">All</button>
          <button wire:click="setStatusFilter('completed')" class="text-xs font-medium rounded-full px-3.5 py-1.5 {{ $statusFilter === 'completed' ? 'bg-ink text-paper' : 'border border-ink/15 text-ink/60 hover:border-teal/40' }}">Successful</button>
          <button wire:click="setStatusFilter('failed')" class="text-xs font-medium rounded-full px-3.5 py-1.5 {{ $statusFilter === 'failed' ? 'bg-ink text-paper' : 'border border-ink/15 text-ink/60 hover:border-teal/40' }}">Failed</button>
          <button wire:click="setStatusFilter('refunded')" class="text-xs font-medium rounded-full px-3.5 py-1.5 {{ $statusFilter === 'refunded' ? 'bg-ink text-paper' : 'border border-ink/15 text-ink/60 hover:border-teal/40' }}">Refunded</button>
        </div>
        <div class="flex items-center gap-2">
          <div class="relative">
            <input wire:model.live.debounce.300ms="search" type="text" placeholder="Search student or transaction ID…" class="w-64 text-sm rounded-lg border border-ink/15 pl-9 pr-3 py-1.5 focus:outline-none focus:ring-2 focus:ring-teal/30 focus:border-teal">
            <svg class="w-4 h-4 absolute left-3 top-2 text-ink/40" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="11" cy="11" r="7"/><path d="m21 21-4.35-4.35"/></svg>
          </div>
          <select wire:model.live="timeRange" class="text-sm border border-ink/15 rounded-lg px-3 py-1.5 bg-white text-ink/70">
            <option value="30">Last 30 days</option>
            <option value="7">Last 7 days</option>
            <option value="90">Last 90 days</option>
          </select>
        </div>
    </div>

    <!-- TABLE -->
    <div class="bg-white rounded-2xl border border-ink/10 overflow-hidden">
        <table class="w-full text-sm">
          <thead>
            <tr class="text-left text-xs text-ink/40 border-b border-ink/10">
              <th class="font-medium px-6 py-3">Transaction</th>
              <th class="font-medium px-6 py-3">Student</th>
              <th class="font-medium px-6 py-3">Item</th>
              <th class="font-medium px-6 py-3">Amount</th>
              <th class="font-medium px-6 py-3">Method</th>
              <th class="font-medium px-6 py-3">Status</th>
              <th class="font-medium px-6 py-3">Date</th>
              <th class="font-medium px-6 py-3 text-right">Actions</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-ink/5">
            @forelse($purchases as $purchase)
                @php
                    $statusStyles = [
                        'completed' => 'text-teal bg-teal/10',
                        'failed' => 'text-margin bg-margin/10',
                        'pending' => 'text-gold bg-gold/10',
                        'refunded' => 'text-ink/50 bg-ink/5',
                    ][$purchase->status] ?? 'text-ink/50 bg-ink/5';

                    $statusLabel = match($purchase->status) {
                        'completed' => 'Successful',
                        'failed' => 'Failed',
                        'pending' => 'Pending',
                        'refunded' => 'Refunded',
                        default => ucfirst($purchase->status)
                    };
                @endphp
                <tr class="hover:bg-paper/40">
                <td class="px-6 py-3.5 text-ink/50 font-mono text-xs">{{ $purchase->payhere_order_id ?? 'N/A' }}</td>
                <td class="px-6 py-3.5 text-ink/80">{{ $purchase->user->name ?? 'Unknown' }}</td>
                <td class="px-6 py-3.5 text-ink/60">{{ $purchase->paper->title ?? 'Deleted Paper' }}</td>
                <td class="px-6 py-3.5 font-medium text-ink">Rs. {{ number_format($purchase->amount_paid) }}</td>
                <td class="px-6 py-3.5 text-ink/60">PayHere</td>
                <td class="px-6 py-3.5"><span class="text-[11px] font-semibold {{ $statusStyles }} rounded-full px-2.5 py-0.5">{{ $statusLabel }}</span></td>
                <td class="px-6 py-3.5 text-ink/40">{{ $purchase->created_at->format('M d, g:i A') }}</td>
                <td class="px-6 py-4 text-right">
                    <a href="{{ route('admin.payments.show', $purchase->id) }}" wire:navigate class="text-xs font-semibold text-teal hover:underline">View receipt &rarr;</a>
                </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" class="px-6 py-8 text-center text-ink/50 text-sm">
                        No transactions found for this period.
                    </td>
                </tr>
            @endforelse
          </tbody>
        </table>
        @if($purchases->hasPages())
            <div class="px-6 py-4 border-t border-ink/10">
                {{ $purchases->links() }}
            </div>
        @endif
    </div>

</div>
