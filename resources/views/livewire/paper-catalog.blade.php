<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">

        {{-- ── Page Header ─────────────────────────────── --}}
        <div>
            <flux:heading size="xl">Exam Papers</flux:heading>
            <flux:text class="mt-1">Browse past exam papers by level and subject. Purchase a paper to start practicing.</flux:text>
        </div>

        {{-- ── Filter Bar ──────────────────────────────── --}}
        <flux:card wire:key="filter-bar">
            <div class="flex flex-col sm:flex-row items-start sm:items-end gap-4">
                <div class="w-full sm:w-48">
                    <flux:select
                        wire:model.live="filterLevel"
                        label="Level"
                        placeholder="All levels"
                    >
                        <flux:select.option value="">All levels</flux:select.option>
                        <flux:select.option value="scholarship">Grade 5 Scholarship</flux:select.option>
                        <flux:select.option value="ol">O/L</flux:select.option>
                        <flux:select.option value="al">A/L</flux:select.option>
                    </flux:select>
                </div>

                <div class="w-full sm:w-48">
                    <flux:select
                        wire:model.live="filterMedium"
                        label="Medium"
                        placeholder="All mediums"
                    >
                        <flux:select.option value="">All mediums</flux:select.option>
                        <flux:select.option value="english">English</flux:select.option>
                        <flux:select.option value="sinhala">Sinhala</flux:select.option>
                        <flux:select.option value="tamil">Tamil</flux:select.option>
                    </flux:select>
                </div>

                @if($filterLevel !== '' || $filterMedium !== '')
                    <flux:button wire:click="resetFilters" variant="ghost" icon="x-mark" size="sm">
                        Clear filters
                    </flux:button>
                @endif
            </div>
        </flux:card>

        {{-- ── Papers by Level → Subject ───────────────── --}}
        @forelse($this->groupedPapers as $level => $subjectGroups)
            <div wire:key="level-{{ $level }}" class="space-y-6 transition-opacity duration-200" wire:loading.class="opacity-50 pointer-events-none" wire:target="filterLevel, filterMedium, resetFilters">

                {{-- Level heading --}}
                <div class="flex items-center gap-3">
                    <flux:heading size="lg">{{ $this->levelLabel($level) }}</flux:heading>
                    <flux:badge color="{{ $this->levelBadgeColor($level) }}" size="sm">
                        {{ strtoupper($level) }}
                    </flux:badge>
                </div>

                @foreach($subjectGroups as $subjectName => $papers)
                    <div wire:key="subject-{{ $level }}-{{ Str::slug($subjectName) }}" class="space-y-4">

                        {{-- Subject subheading --}}
                        <flux:heading size="sm" class="text-slate-600 dark:text-slate-400">
                            {{ $subjectName }}
                        </flux:heading>

                        {{-- Paper grid --}}
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                            @foreach($papers as $paper)
                                <flux:card wire:key="paper-{{ $paper->id }}" class="flex flex-col justify-between">
                                    <div class="space-y-3">
                                        {{-- Top row: year badge + medium --}}
                                        <div class="flex items-center justify-between">
                                            <flux:badge size="sm">{{ $paper->year }}</flux:badge>
                                            <span class="text-xs font-medium text-slate-400 uppercase tracking-wide">
                                                {{ ucfirst($paper->subject->medium) }}
                                            </span>
                                        </div>

                                        {{-- Title --}}
                                        <flux:heading size="sm">
                                            {{ Str::limit($paper->title, 50) }}
                                        </flux:heading>

                                        {{-- Meta row --}}
                                        <div class="flex items-center gap-4 text-sm text-slate-500 dark:text-slate-400">
                                            <span class="inline-flex items-center gap-1">
                                                <flux:icon name="clock" variant="mini" class="size-4" />
                                                {{ $paper->duration_minutes }} min
                                            </span>
                                            <span class="inline-flex items-center gap-1">
                                                <flux:icon name="list-bullet" variant="mini" class="size-4" />
                                                {{ $paper->questions_count }} Qs
                                            </span>
                                        </div>

                                        {{-- Price --}}
                                        <div class="text-sm font-semibold">
                                            @if((float) $paper->price === 0.00)
                                                <span class="text-emerald-600 dark:text-emerald-400">Free</span>
                                            @else
                                                <span class="text-slate-700 dark:text-slate-300">Rs. {{ number_format($paper->price, 2) }}</span>
                                            @endif
                                        </div>
                                    </div>

                                    {{-- CTA --}}
                                    <div class="mt-4 pt-4 border-t border-slate-200 dark:border-slate-700">
                                        @if($this->isPurchased($paper))
                                            <flux:button
                                                href="{{ route('quiz.take', $paper) }}"
                                                variant="primary"
                                                icon="play"
                                                class="w-full justify-center"
                                            >
                                                Start Quiz
                                            </flux:button>
                                        @else
                                            <flux:button
                                                href="{{ route('paper.buy', $paper) }}"
                                                variant="outline"
                                                icon="shopping-cart"
                                                class="w-full justify-center"
                                            >
                                                Buy for Rs. {{ number_format($paper->price, 2) }}
                                            </flux:button>
                                        @endif
                                    </div>
                                </flux:card>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
        @empty
            {{-- Empty state --}}
            <flux:callout icon="information-circle">
                <flux:callout.heading>No papers found</flux:callout.heading>
                <flux:callout.text>
                    @if($filterLevel !== '' || $filterMedium !== '')
                        No published papers match your current filters. Try clearing your filters or check back later.
                    @else
                        There are no published exam papers available yet. Check back soon!
                    @endif
                </flux:callout.text>
            </flux:callout>
        @endforelse

    </div>
</div>
