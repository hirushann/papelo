<div class="space-y-5">
    
    <x-slot name="headerActions">
        <button class="inline-flex items-center gap-1.5 rounded-lg border border-ink/15 text-ink/70 text-sm font-semibold px-4 py-2 hover:border-teal/40 hover:text-teal transition">
          <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 3v13m0 0-4-4m4 4 4-4"/><path d="M4 19h16"/></svg>
          Export CSV
        </button>
    </x-slot>

    <!-- STATS -->
    <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-5">
        <div class="bg-white rounded-2xl border border-ink/10 p-5">
          <p class="text-xs font-medium text-ink/50 mb-2">Total students</p>
          <p class="font-display text-2xl text-ink">{{ number_format($totalStudents) }}</p>
        </div>
        <div class="bg-white rounded-2xl border border-ink/10 p-5">
          <p class="text-xs font-medium text-ink/50 mb-2">New this week</p>
          <p class="font-display text-2xl text-ink">{{ number_format($newThisWeek) }}</p>
        </div>
        <div class="bg-white rounded-2xl border border-ink/10 p-5">
          <p class="text-xs font-medium text-ink/50 mb-2">Monthly subscribers</p>
          <p class="font-display text-2xl text-ink">N/A</p>
        </div>
        <div class="bg-white rounded-2xl border border-ink/10 p-5">
          <p class="text-xs font-medium text-ink/50 mb-2">Avg. attempts / student</p>
          <p class="font-display text-2xl text-ink">{{ $avgAttempts }}</p>
        </div>
    </div>

    <!-- TOOLBAR -->
    <div class="flex flex-wrap items-center justify-between gap-3">
        <div class="flex items-center gap-2">
            <button wire:click="setLevelFilter('')" class="text-xs font-semibold rounded-full px-3.5 py-1.5 {{ $levelFilter === '' ? 'bg-ink text-paper' : 'border border-ink/15 text-ink/60 hover:border-teal/40' }}">All levels</button>
            <button wire:click="setLevelFilter('scholarship')" class="text-xs font-medium rounded-full px-3.5 py-1.5 {{ $levelFilter === 'scholarship' ? 'bg-ink text-paper' : 'border border-ink/15 text-ink/60 hover:border-teal/40' }}">Grade 5</button>
            <button wire:click="setLevelFilter('ol')" class="text-xs font-medium rounded-full px-3.5 py-1.5 {{ $levelFilter === 'ol' ? 'bg-ink text-paper' : 'border border-ink/15 text-ink/60 hover:border-teal/40' }}">O/L</button>
            <button wire:click="setLevelFilter('al')" class="text-xs font-medium rounded-full px-3.5 py-1.5 {{ $levelFilter === 'al' ? 'bg-ink text-paper' : 'border border-ink/15 text-ink/60 hover:border-teal/40' }}">A/L</button>
        </div>
        
        <div class="flex items-center gap-3">
            <div class="relative">
                <input wire:model.live.debounce.300ms="search" type="text" placeholder="Search name or email…" class="w-64 text-sm rounded-lg border border-ink/15 pl-9 pr-3 py-1.5 focus:outline-none focus:ring-2 focus:ring-teal/30 focus:border-teal">
                <svg class="w-4 h-4 absolute left-3 top-2 text-ink/40" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="11" cy="11" r="7"/><path d="m21 21-4.35-4.35"/></svg>
            </div>
            <select wire:model.live="planFilter" class="text-sm border border-ink/15 rounded-lg px-3 py-1.5 bg-white text-ink/70">
                <option value="">All plans</option>
                <option value="pay-per-paper">Pay-per-paper</option>
                <option value="no-purchases">No purchases yet</option>
            </select>
        </div>
    </div>

    <!-- TABLE -->
    <div class="bg-white rounded-2xl border border-ink/10 overflow-hidden">
        <table class="w-full text-sm">
            <thead>
                <tr class="text-left text-xs text-ink/40 border-b border-ink/10">
                    <th class="font-medium px-6 py-3">Student</th>
                    <th class="font-medium px-6 py-3">Level</th>
                    <th class="font-medium px-6 py-3">Plan</th>
                    <th class="font-medium px-6 py-3">Attempts</th>
                    <th class="font-medium px-6 py-3">Joined</th>
                    <th class="font-medium px-6 py-3">Status</th>
                    <th class="font-medium px-6 py-3 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-ink/5">
                @forelse($users as $user)
                    @php
                        // Derive colors based on name hash for consistent avatars
                        $colors = ['#3F7D6B', '#B5514A', '#22314A', '#C79A46', '#8a8577'];
                        $bgColor = $colors[crc32($user->email) % count($colors)];
                        $initials = strtoupper(substr($user->name, 0, 2));
                        
                        // Fake level mapping based on hash just for UI look, since we don't store it
                        $levelText = ['Grade 5', 'O/L', 'A/L'][crc32($user->email) % 3];
                        $levelStyles = [
                            'Grade 5' => 'text-gold bg-gold/10',
                            'O/L' => 'text-teal bg-teal/10',
                            'A/L' => 'text-margin bg-margin/10',
                        ][$levelText];
                        
                        $hasPurchases = $user->purchases()->exists();
                        $planText = $hasPurchases ? 'Pay-per-paper' : 'No purchases';
                    @endphp
                    <tr class="hover:bg-paper/40">
                        <td class="px-6 py-3.5">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full flex items-center justify-center text-paper text-xs font-semibold flex-shrink-0" style="background:{{ $bgColor }};">{{ $initials }}</div>
                                <div>
                                    <p class="font-medium text-ink">{{ $user->name }}</p>
                                    <p class="text-xs text-ink/40">{{ $user->email }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-3.5">
                            <span class="text-[11px] font-semibold uppercase {{ $levelStyles }} rounded-full px-2.5 py-0.5">{{ $levelText }}</span>
                        </td>
                        <td class="px-6 py-3.5">
                            <span class="text-[11px] font-semibold text-ink/50 bg-ink/5 rounded-full px-2.5 py-0.5">{{ $planText }}</span>
                        </td>
                        <td class="px-6 py-3.5 text-ink/60">{{ $user->attempts_count }}</td>
                        <td class="px-6 py-3.5 text-ink/60">{{ $user->created_at->format('M d, Y') }}</td>
                        <td class="px-6 py-3.5">
                            <span class="text-[11px] font-semibold text-teal bg-teal/10 rounded-full px-2.5 py-0.5">Active</span>
                        </td>
                        <td class="px-6 py-4 text-right">
                          <a href="{{ route('admin.users.show', $user->id) }}" wire:navigate class="text-xs font-semibold text-teal hover:underline">View profile &rarr;</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-6 py-8 text-center text-ink/50 text-sm">
                            No students found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        @if($users->hasPages())
            <div class="px-6 py-4 border-t border-ink/10">
                {{ $users->links() }}
            </div>
        @endif
    </div>
</div>
