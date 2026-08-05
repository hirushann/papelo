<div x-data="{ open: false }" class="relative">
  <button @click="open = !open" @click.away="open = false" class="relative text-ink/50 hover:text-ink focus:outline-none">
    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M18 8a6 6 0 0 0-12 0c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 0 1-3.46 0"/></svg>
    @if(collect($notifications)->where('unread', true)->count() > 0)
      <span class="absolute -top-0.5 -right-0.5 w-2 h-2 bg-margin rounded-full"></span>
    @endif
  </button>

  <div x-show="open" style="display: none;" x-transition class="absolute right-0 mt-3 w-80 bg-white rounded-xl shadow-lg border border-ink/10 overflow-hidden z-50">
    <div class="flex items-center justify-between px-4 py-3 border-b border-ink/10 bg-[#F7F4EC]">
      <h3 class="font-display text-sm text-ink">Notifications</h3>
      @if(collect($notifications)->where('unread', true)->count() > 0)
        <button wire:click="markAllAsRead" class="text-[11px] font-semibold text-teal hover:underline focus:outline-none">Mark all as read</button>
      @endif
    </div>

    <div class="max-h-80 overflow-y-auto">
      @forelse($notifications as $notification)
        <div class="flex gap-3 px-4 py-3 border-b border-ink/5 hover:bg-paper/30 transition cursor-default {{ $notification['unread'] ? 'bg-teal/5' : '' }}">
          
          <div class="mt-0.5 shrink-0">
            @if($notification['type'] === 'user')
              <div class="w-7 h-7 rounded-full bg-teal/20 text-teal flex items-center justify-center">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
              </div>
            @elseif($notification['type'] === 'payment')
              <div class="w-7 h-7 rounded-full bg-gold/20 text-gold flex items-center justify-center">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
              </div>
            @elseif($notification['type'] === 'error')
              <div class="w-7 h-7 rounded-full bg-margin/20 text-margin flex items-center justify-center">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><path d="M12 8v4"/><path d="M12 16h.01"/></svg>
              </div>
            @endif
          </div>

          <div class="flex-1 min-w-0">
            <div class="flex items-start justify-between gap-2">
              <p class="text-sm font-semibold text-ink leading-tight">{{ $notification['title'] }}</p>
              @if($notification['unread'])
                <span class="w-1.5 h-1.5 rounded-full bg-teal shrink-0 mt-1"></span>
              @endif
            </div>
            <p class="text-[12px] text-ink/60 mt-0.5 leading-snug">{{ $notification['description'] }}</p>
            <div class="flex items-center justify-between mt-1.5">
              <span class="text-[10px] font-medium text-ink/40">{{ $notification['time'] }}</span>
              @if($notification['unread'])
                <button wire:click="markAsRead({{ $notification['id'] }})" class="text-[10px] font-semibold text-teal/70 hover:text-teal focus:outline-none">Mark read</button>
              @endif
            </div>
          </div>
        </div>
      @empty
        <div class="px-4 py-8 text-center text-ink/40">
          <svg class="w-10 h-10 mx-auto mb-2 opacity-50" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path d="M18 8a6 6 0 0 0-12 0c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 0 1-3.46 0"/></svg>
          <p class="text-sm">You're all caught up!</p>
        </div>
      @endforelse
    </div>
    
    <div class="px-4 py-2 bg-paper/30 border-t border-ink/10 text-center">
      <a href="#" class="text-[11px] font-semibold text-ink/50 hover:text-ink">View all notifications</a>
    </div>
  </div>
</div>
