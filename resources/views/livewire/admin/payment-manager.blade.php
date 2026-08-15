<div class="space-y-5">

    <x-slot name="headerActions">
        <span class="text-xs text-ink/40 hidden md:inline">via Lemon Squeezy</span>
        <button class="inline-flex items-center gap-1.5 rounded-lg border border-ink/15 text-ink/70 text-sm font-semibold px-4 py-2 hover:border-teal/40 hover:text-teal transition">
          <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 3v13m0 0-4-4m4 4 4-4"/><path d="M4 19h16"/></svg>
          Export CSV
        </button>
    </x-slot>

    <!-- STATS -->
    <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-5">
        <div class="bg-white rounded-2xl border border-ink/10 p-5">
          <p class="text-xs font-medium text-ink/50 mb-2">Estimated monthly revenue</p>
          <p class="font-display text-2xl text-ink">Rs. {{ number_format($revenue) }}</p>
        </div>
        <div class="bg-white rounded-2xl border border-ink/10 p-5">
          <p class="text-xs font-medium text-ink/50 mb-2">Active subscriptions</p>
          <p class="font-display text-2xl text-ink">{{ number_format($activeCount) }}</p>
        </div>
        <div class="bg-white rounded-2xl border border-ink/10 p-5">
          <p class="text-xs font-medium text-ink/50 mb-2">Cancelled</p>
          <p class="font-display text-2xl text-margin">{{ number_format($cancelledCount) }}</p>
        </div>
        <div class="bg-white rounded-2xl border border-ink/10 p-5">
          <p class="text-xs font-medium text-ink/50 mb-2">Past due</p>
          <p class="font-display text-2xl text-ink">{{ number_format($pastDueCount) }}</p>
        </div>
    </div>

    <!-- TOOLBAR -->
    <div class="flex flex-wrap items-center justify-between gap-3">
        <div class="flex items-center gap-2">
          <button wire:click="setStatusFilter('')" class="text-xs font-semibold rounded-full px-3.5 py-1.5 {{ $statusFilter === '' ? 'bg-ink text-paper' : 'border border-ink/15 text-ink/60 hover:border-teal/40' }}">All</button>
          <button wire:click="setStatusFilter('active')" class="text-xs font-medium rounded-full px-3.5 py-1.5 {{ $statusFilter === 'active' ? 'bg-ink text-paper' : 'border border-ink/15 text-ink/60 hover:border-teal/40' }}">Active</button>
          <button wire:click="setStatusFilter('cancelled')" class="text-xs font-medium rounded-full px-3.5 py-1.5 {{ $statusFilter === 'cancelled' ? 'bg-ink text-paper' : 'border border-ink/15 text-ink/60 hover:border-teal/40' }}">Cancelled</button>
          <button wire:click="setStatusFilter('past_due')" class="text-xs font-medium rounded-full px-3.5 py-1.5 {{ $statusFilter === 'past_due' ? 'bg-ink text-paper' : 'border border-ink/15 text-ink/60 hover:border-teal/40' }}">Past Due</button>
        </div>
        <div class="flex items-center gap-2">
          <div class="relative">
            <input wire:model.live.debounce.300ms="search" type="text" placeholder="Search student or subscription ID…" class="w-64 text-sm rounded-lg border border-ink/15 pl-9 pr-3 py-1.5 focus:outline-none focus:ring-2 focus:ring-teal/30 focus:border-teal">
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
              <th class="font-medium px-6 py-3">Subscription</th>
              <th class="font-medium px-6 py-3">Student</th>
              <th class="font-medium px-6 py-3">Plan</th>
              <th class="font-medium px-6 py-3">Price</th>
              <th class="font-medium px-6 py-3">Status</th>
              <th class="font-medium px-6 py-3">Since</th>
              <th class="font-medium px-6 py-3 text-right">Actions</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-ink/5">
            @forelse($subscriptions as $subscription)
                @php
                    $statusStyles = [
                        'active' => 'text-teal bg-teal/10',
                        'cancelled' => 'text-ink/50 bg-ink/5',
                        'past_due' => 'text-margin bg-margin/10',
                        'expired' => 'text-ink/40 bg-ink/5',
                        'paused' => 'text-gold bg-gold/10',
                    ][$subscription->status] ?? 'text-ink/50 bg-ink/5';
                @endphp
                <tr class="hover:bg-paper/40">
                <td class="px-6 py-3.5 text-ink/50 font-mono text-xs">{{ $subscription->ls_subscription_id ?? 'N/A' }}</td>
                <td class="px-6 py-3.5 text-ink/80">{{ $subscription->user->name ?? 'Unknown' }}</td>
                <td class="px-6 py-3.5 text-ink/60">{{ $subscription->plan->name ?? 'Unknown' }}</td>
                <td class="px-6 py-3.5 font-medium text-ink">Rs. {{ number_format($subscription->plan->price ?? 0) }}</td>
                <td class="px-6 py-3.5"><span class="text-[11px] font-semibold {{ $statusStyles }} rounded-full px-2.5 py-0.5">{{ ucfirst(str_replace('_', ' ', $subscription->status)) }}</span></td>
                <td class="px-6 py-3.5 text-ink/40">{{ $subscription->created_at->format('M d, g:i A') }}</td>
                <td class="px-6 py-4 text-right">
                    <a href="{{ route('admin.payments.show', $subscription->id) }}" wire:navigate class="text-xs font-semibold text-teal hover:underline">View details &rarr;</a>
                </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="px-6 py-8 text-center text-ink/50 text-sm">
                        No subscriptions found for this period.
                    </td>
                </tr>
            @endforelse
          </tbody>
        </table>
        @if($subscriptions->hasPages())
            <div class="px-6 py-4 border-t border-ink/10">
                {{ $subscriptions->links() }}
            </div>
        @endif
    </div>

</div>
