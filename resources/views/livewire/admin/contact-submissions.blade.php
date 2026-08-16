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
                        <div class="max-w-md line-clamp-2 cursor-pointer hover:text-teal transition" wire:click="viewMessage({{ $submission->id }})" title="Click to view full message">
                            {{ $submission->message }}
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-xs">
                        {{ $submission->created_at->format('M d, Y h:i A') }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-xs">
                        <button wire:click="viewMessage({{ $submission->id }})" class="text-teal hover:text-teal/80 font-semibold mr-3">View</button>
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

    <flux:modal name="view-message" class="md:w-full md:max-w-lg">
        @if($viewingSubmission)
            <div class="mb-4">
                <div class="mb-4">
                    <h3 class="font-display text-xl text-ink">Message Details</h3>
                </div>
                
                <div class="space-y-4">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-wide text-ink/50">Sender</p>
                            <p class="text-sm text-ink">{{ $viewingSubmission->name }}</p>
                        </div>
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-wide text-ink/50">Email</p>
                            <a href="mailto:{{ $viewingSubmission->email }}" class="text-sm text-teal hover:underline">{{ $viewingSubmission->email }}</a>
                        </div>
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-wide text-ink/50">Category</p>
                            <p class="text-sm text-ink">{{ $viewingSubmission->category }}</p>
                        </div>
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-wide text-ink/50">Date</p>
                            <p class="text-sm text-ink">{{ $viewingSubmission->created_at->format('M d, Y h:i A') }}</p>
                        </div>
                    </div>
                    
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wide text-ink/50 mb-1">Message</p>
                        <div class="bg-gray-50 rounded-lg p-4 border border-ink/5 text-sm text-ink whitespace-pre-wrap">{{ $viewingSubmission->message }}</div>
                    </div>
                </div>
            </div>
            
            <div class="flex justify-end pt-4 border-t border-ink/10">
                <flux:modal.close>
                    <flux:button variant="ghost">Close</flux:button>
                </flux:modal.close>
            </div>
        @endif
    </flux:modal>
</div>
