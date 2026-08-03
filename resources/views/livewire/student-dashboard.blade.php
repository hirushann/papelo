<div class="space-y-6">
    <!-- Quick Stats -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <flux:card>
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-slate-500">Total Papers</p>
                    <h3 class="text-2xl font-bold text-slate-900">{{ $totalPurchased }}</h3>
                </div>
                <div class="p-3 bg-indigo-50 rounded-lg">
                    <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                </div>
            </div>
        </flux:card>

        <flux:card>
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-slate-500">Exams Taken</p>
                    <h3 class="text-2xl font-bold text-slate-900">{{ $totalAttempts }}</h3>
                </div>
                <div class="p-3 bg-emerald-50 rounded-lg">
                    <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                </div>
            </div>
        </flux:card>

        <flux:card>
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-slate-500">Average Score</p>
                    <h3 class="text-2xl font-bold text-slate-900">{{ $averagePercentage }}%</h3>
                </div>
                <div class="p-3 bg-amber-50 rounded-lg">
                    <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
                </div>
            </div>
        </flux:card>
    </div>

    <!-- Continue Learning & Recent Attempts Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        
        <!-- Continue Learning -->
        <flux:card class="flex flex-col h-full">
            <h3 class="text-lg font-semibold text-slate-900 mb-4">Continue Learning</h3>
            
            <div class="flex-1 space-y-4">
                @forelse($recentPurchases as $purchase)
                    <div class="flex items-center justify-between p-4 bg-slate-50 rounded-lg border border-slate-100">
                        <div class="flex-1 min-w-0 pr-4">
                            <h4 class="font-medium text-slate-900 truncate">{{ $purchase->paper->title }}</h4>
                            <p class="text-sm text-slate-500">{{ $purchase->paper->subject->name }} ({{ $purchase->paper->year }})</p>
                        </div>
                        <flux:button href="{{ route('quiz.take', $purchase->paper->id) }}" variant="primary" size="sm" wire:navigate>
                            Take Exam
                        </flux:button>
                    </div>
                @empty
                    <div class="text-center py-8">
                        <p class="text-slate-500 mb-4">You haven't purchased any papers yet.</p>
                        <flux:button href="{{ route('papers') }}" variant="primary" wire:navigate>
                            Browse Papers
                        </flux:button>
                    </div>
                @endforelse
            </div>
        </flux:card>

        <!-- Recent Attempts -->
        <flux:card class="flex flex-col h-full">
            <h3 class="text-lg font-semibold text-slate-900 mb-4">Recent Results</h3>
            
            <div class="flex-1 overflow-x-auto">
                @if($recentAttempts->isEmpty())
                    <div class="text-center py-8">
                        <p class="text-slate-500">You haven't taken any exams yet.</p>
                    </div>
                @else
                    <flux:table>
                        <flux:table.columns>
                            <flux:table.column>Paper</flux:table.column>
                            <flux:table.column>Score</flux:table.column>
                            <flux:table.column>Date</flux:table.column>
                        </flux:table.columns>
                        
                        <flux:table.rows>
                            @foreach($recentAttempts as $attempt)
                                @php
                                    $scorePct = $attempt->scorePercentage();
                                    $scoreColor = $scorePct >= 75 ? 'emerald' : ($scorePct >= 40 ? 'amber' : 'rose');
                                @endphp
                                <flux:table.row>
                                    <flux:table.cell class="font-medium text-slate-900 whitespace-nowrap">
                                        {{ $attempt->paper->title }}
                                    </flux:table.cell>
                                    <flux:table.cell>
                                        <flux:badge color="{{ $scoreColor }}" size="sm">
                                            {{ $attempt->score }} / {{ $attempt->total_questions }} ({{ $scorePct }}%)
                                        </flux:badge>
                                    </flux:table.cell>
                                    <flux:table.cell class="text-slate-500 whitespace-nowrap text-sm">
                                        {{ $attempt->created_at->format('M d, Y') }}
                                    </flux:table.cell>
                                </flux:table.row>
                            @endforeach
                        </flux:table.rows>
                    </flux:table>
                @endif
            </div>
        </flux:card>
    </div>
</div>
