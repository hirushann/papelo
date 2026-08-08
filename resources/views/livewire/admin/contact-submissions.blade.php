<div>
    <div class="bg-white rounded-2xl border border-ink/10 overflow-hidden">
        <div class="px-6 py-5 border-b border-ink/10 flex items-center justify-between">
            <h2 class="font-display text-lg text-ink">Contact Messages</h2>
        </div>
        <table class="w-full text-sm text-left">
            <thead class="bg-paper/30 text-xs font-semibold uppercase tracking-wide text-ink/50 border-b border-ink/10">
                <tr>
                    <th class="px-6 py-3">Status</th>
                    <th class="px-6 py-3">Sender</th>
                    <th class="px-6 py-3">Category</th>
                    <th class="px-6 py-3">Message</th>
                    <th class="px-6 py-3">Date</th>
                    <th class="px-6 py-3 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-ink/5">
                @forelse($submissions as $submission)
                <tr class="{{ $submission->is_read ? 'bg-white text-ink/70' : 'bg-teal/5 text-ink font-medium' }}">
                    <td class="px-6 py-4 whitespace-nowrap">
                        @if($submission->is_read)
                            <span class="inline-flex items-center rounded-md bg-ink/10 px-2 py-1 text-xs font-medium text-ink/60">Read</span>
                        @else
                            <span class="inline-flex items-center rounded-md bg-teal/10 px-2 py-1 text-xs font-medium text-teal">New</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div>{{ $submission->name }}</div>
                        <div class="text-xs {{ $submission->is_read ? 'text-ink/40' : 'text-ink/60' }}">{{ $submission->email }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        {{ $submission->category }}
                    </td>
                    <td class="px-6 py-4">
                        <div class="max-w-md line-clamp-2" title="{{ $submission->message }}">
                            {{ $submission->message }}
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-xs">
                        {{ $submission->created_at->format('M d, Y h:i A') }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-xs">
                        @if($submission->is_read)
                            <button wire:click="markAsUnread({{ $submission->id }})" class="text-ink/50 hover:text-ink">Mark Unread</button>
                        @else
                            <button wire:click="markAsRead({{ $submission->id }})" class="text-teal hover:text-teal/80 font-semibold">Mark Read</button>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-8 text-center text-ink/50">No messages found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <div class="mt-4">
        {{ $submissions->links() }}
    </div>
</div>
