<div>
  <x-slot name="customHeader">
    <header class="h-16 bg-white border-b border-ink/10 flex items-center justify-between px-8 flex-shrink-0">
      <div>
        <p class="text-[11px] text-ink/40"><a href="{{ route('admin.users') }}" wire:navigate class="hover:text-teal">Users</a> / {{ $user->name }}</p>
        <h1 class="font-display text-lg text-ink -mt-0.5">Student Profile</h1>
      </div>
    </header>
  </x-slot>

  <div class="grid lg:grid-cols-[280px_1fr] gap-6 max-w-5xl">
    
    <!-- PROFILE CARD -->
    <aside class="space-y-4">
      <div class="bg-white rounded-2xl border border-ink/10 p-6 text-center">
        <div class="w-16 h-16 rounded-full bg-teal flex items-center justify-center text-paper font-display text-xl mx-auto mb-3 uppercase">
          {{ substr($user->name ?? 'A', 0, 2) }}
        </div>
        <h2 class="font-display text-lg text-ink">{{ $user->name }}</h2>
        <p class="text-xs text-ink/50 mb-3">{{ $user->email }}</p>
        <div class="flex items-center justify-center gap-1.5 mb-5">
          @if($currentSubscription && $currentSubscription->status === 'active')
            <span class="text-[11px] font-semibold text-teal bg-teal/10 rounded-full px-2.5 py-0.5">Active</span>
          @else
            <span class="text-[11px] font-semibold text-ink/50 bg-ink/5 rounded-full px-2.5 py-0.5">{{ $currentSubscription ? ucfirst($currentSubscription->status) : 'Inactive' }}</span>
          @endif
        </div>
        <dl class="text-left text-xs space-y-2 border-t border-ink/10 pt-4">
          <div class="flex justify-between"><dt class="text-ink/50">Plan</dt><dd class="font-medium text-ink">{{ $currentSubscription ? $currentSubscription->plan->name : 'No active plan' }}</dd></div>
          <div class="flex justify-between"><dt class="text-ink/50">Joined</dt><dd class="font-medium text-ink">{{ $user->created_at->format('M d, Y') }}</dd></div>
          <div class="flex justify-between"><dt class="text-ink/50">Last active</dt><dd class="font-medium text-ink">{{ $user->updated_at->diffForHumans() }}</dd></div>
        </dl>
      </div>
      <div class="bg-white rounded-2xl border border-ink/10 p-4 space-y-2">
        <button class="w-full text-left text-sm font-medium text-ink/70 hover:text-ink px-3 py-2 rounded-lg hover:bg-paper/60">Send password reset</button>
        <button class="w-full text-left text-sm font-medium text-margin hover:bg-margin/5 px-3 py-2 rounded-lg">Suspend account</button>
      </div>

      <div class="bg-white rounded-2xl border border-ink/10 p-5 mt-4">
        <h3 class="text-sm font-semibold text-ink mb-3">Grant Free Access</h3>
        <p class="text-xs text-ink/50 mb-4">Manually assign a plan to this student for free lifetime access.</p>
        <div class="space-y-3">
          <flux:select wire:model="selectedPlanId" placeholder="Select a plan...">
            @foreach($plans as $plan)
              <flux:select.option value="{{ $plan->id }}">{{ $plan->name }}</flux:select.option>
            @endforeach
          </flux:select>
          <flux:button wire:click="grantFreePlan" variant="primary" class="w-full">Grant Plan</flux:button>
        </div>
        @if(session('success'))
          <p class="text-xs text-teal mt-3 font-medium">{{ session('success') }}</p>
        @endif
      </div>
    </aside>

    <!-- MAIN COLUMN -->
    <div class="space-y-6 min-w-0">

      <!-- STATS -->
      <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
        <div class="bg-white rounded-2xl border border-ink/10 p-4">
          <p class="text-xs text-ink/50 mb-1.5">Current Plan</p>
          <p class="font-display text-xl text-ink">{{ $currentSubscription ? $currentSubscription->plan->name : 'None' }}</p>
        </div>
        <div class="bg-white rounded-2xl border border-ink/10 p-4">
          <p class="text-xs text-ink/50 mb-1.5">Attempts</p>
          <p class="font-display text-xl text-ink">{{ $attemptsCount }}</p>
        </div>
        <div class="bg-white rounded-2xl border border-ink/10 p-4">
          <p class="text-xs text-ink/50 mb-1.5">Avg. score</p>
          <p class="font-display text-xl text-ink">{{ $avgScore }}%</p>
        </div>
        <div class="bg-white rounded-2xl border border-ink/10 p-4">
          <p class="text-xs text-ink/50 mb-1.5">Attempts limit</p>
          <p class="font-display text-xl text-ink">{{ $currentSubscription ? ($currentSubscription->plan->paper_limit == 0 ? 'Unlimited' : $currentSubscription->plan->paper_limit) : '0' }}</p>
        </div>
      </div>

      <!-- ATTEMPT HISTORY -->
      <div class="bg-white rounded-2xl border border-ink/10 overflow-hidden">
        <div class="px-6 py-4 border-b border-ink/10">
          <h2 class="font-display text-lg text-ink">Attempt history</h2>
        </div>
        @if($recentAttempts->count() > 0)
        <table class="w-full text-sm">
          <thead>
            <tr class="text-left text-xs text-ink/40 border-b border-ink/10">
              <th class="font-medium px-6 py-3">Paper</th>
              <th class="font-medium px-6 py-3">Score</th>
              <th class="font-medium px-6 py-3">Date</th>
              <th class="font-medium px-6 py-3 text-right">Review</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-ink/5">
            @foreach($recentAttempts as $attempt)
            <tr>
              <td class="px-6 py-3.5 text-ink/80">{{ $attempt->paper->subject->level ?? '' }} {{ $attempt->paper->subject->name ?? '' }} {{ $attempt->paper->year ?? '' }}</td>
              <td class="px-6 py-3.5">
                <span class="{{ $attempt->score >= 75 ? 'text-teal' : ($attempt->score >= 40 ? 'text-ink/80' : 'text-margin') }} font-semibold">{{ $attempt->score }}%</span>
              </td>
              <td class="px-6 py-3.5 text-ink/40">{{ $attempt->created_at->format('M d, Y') }}</td>
              <td class="px-6 py-3.5 text-right"><a href="#" class="text-xs font-semibold text-teal hover:underline">View →</a></td>
            </tr>
            @endforeach
          </tbody>
        </table>
        <div class="px-6 py-3 border-t border-ink/10 text-xs text-ink/40">Showing {{ $recentAttempts->count() }} of {{ $attemptsCount }} attempts</div>
        @else
        <div class="px-6 py-8 text-center text-ink/50 text-sm">
            No attempts recorded yet.
        </div>
        @endif
      </div>

      <!-- PAYMENT HISTORY -->
      <div class="bg-white rounded-2xl border border-ink/10 overflow-hidden">
        <div class="px-6 py-4 border-b border-ink/10">
          <h2 class="font-display text-lg text-ink">Payment history</h2>
        </div>
        @if($recentSubscriptions->count() > 0)
        <table class="w-full text-sm">
          <thead>
            <tr class="text-left text-xs text-ink/40 border-b border-ink/10">
              <th class="font-medium px-6 py-3">Subscription</th>
              <th class="font-medium px-6 py-3">Plan</th>
              <th class="font-medium px-6 py-3">Price</th>
              <th class="font-medium px-6 py-3">Status</th>
              <th class="font-medium px-6 py-3">Since</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-ink/5">
            @foreach($recentSubscriptions as $subscription)
            @php
                $statusStyles = [
                    'active' => 'text-teal bg-teal/10',
                    'cancelled' => 'text-ink/50 bg-ink/5',
                    'past_due' => 'text-margin bg-margin/10',
                    'expired' => 'text-ink/40 bg-ink/5',
                    'paused' => 'text-gold bg-gold/10',
                ][$subscription->status] ?? 'text-ink/50 bg-ink/5';
            @endphp
            <tr>
              <td class="px-6 py-3.5 text-ink/50 font-mono text-xs">
                  <a href="{{ route('admin.payments.show', $subscription->id) }}" wire:navigate class="hover:text-teal hover:underline">{{ $subscription->ls_subscription_id ?? 'N/A' }}</a>
              </td>
              <td class="px-6 py-3.5 text-ink/70">{{ $subscription->plan->name ?? 'Unknown' }}</td>
              <td class="px-6 py-3.5 font-medium text-ink">Rs. {{ number_format($subscription->plan->price ?? 0) }}</td>
              <td class="px-6 py-3.5">
                  <span class="text-[11px] font-semibold {{ $statusStyles }} rounded-full px-2.5 py-0.5">{{ ucfirst(str_replace('_', ' ', $subscription->status)) }}</span>
              </td>
              <td class="px-6 py-3.5 text-ink/40">{{ $subscription->created_at->format('M d, Y') }}</td>
            </tr>
            @endforeach
          </tbody>
        </table>
        <div class="px-6 py-3 border-t border-ink/10 text-xs text-ink/40">Showing {{ $recentSubscriptions->count() }} subscriptions</div>
        @else
        <div class="px-6 py-8 text-center text-ink/50 text-sm">
            No payments recorded yet.
        </div>
        @endif
      </div>

    </div>
  </div>
</div>
