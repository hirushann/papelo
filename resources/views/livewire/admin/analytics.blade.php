<div class="space-y-6">

    <x-slot name="headerActions">
        <select wire:model.live="timeRange" class="text-sm border border-ink/15 rounded-lg px-3 py-2 bg-white text-ink/70">
            <option value="7">Last 7 days</option>
            <option value="30">Last 30 days</option>
            <option value="90">Last 90 days</option>
        </select>
    </x-slot>

    <!-- KPI ROW -->
    <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-5">
    <div class="bg-white rounded-2xl border border-ink/10 p-5">
        <p class="text-xs font-medium text-ink/50 mb-2">Revenue</p>
        <p class="font-display text-2xl text-ink mb-1">Rs. {{ number_format($revenue) }}</p>
        <p class="text-xs {{ $revenueGrowth >= 0 ? 'text-teal' : 'text-margin' }} font-medium">
            {!! $revenueGrowth >= 0 ? '&uarr;' : '&darr;' !!} {{ abs($revenueGrowth) }}% vs prior period
        </p>
    </div>
    <div class="bg-white rounded-2xl border border-ink/10 p-5">
        <p class="text-xs font-medium text-ink/50 mb-2">New students</p>
        <p class="font-display text-2xl text-ink mb-1">{{ number_format($newStudents) }}</p>
        <p class="text-xs {{ $studentsGrowth >= 0 ? 'text-teal' : 'text-margin' }} font-medium">
            {!! $studentsGrowth >= 0 ? '&uarr;' : '&darr;' !!} {{ abs($studentsGrowth) }}% vs prior period
        </p>
    </div>
    <div class="bg-white rounded-2xl border border-ink/10 p-5">
        <p class="text-xs font-medium text-ink/50 mb-2">Papers attempted</p>
        <p class="font-display text-2xl text-ink mb-1">{{ number_format($papersAttempted) }}</p>
        <p class="text-xs {{ $attemptsGrowth >= 0 ? 'text-teal' : 'text-margin' }} font-medium">
            {!! $attemptsGrowth >= 0 ? '&uarr;' : '&darr;' !!} {{ abs($attemptsGrowth) }}% vs prior period
        </p>
    </div>
    <div class="bg-white rounded-2xl border border-ink/10 p-5">
        <p class="text-xs font-medium text-ink/50 mb-2">Preview &rarr; purchase (Mock)</p>
        <p class="font-display text-2xl text-ink mb-1">24%</p>
        <p class="text-xs text-margin font-medium">&darr; 2% vs prior period</p>
    </div>
    </div>

    <!-- REVENUE + BREAKDOWNS -->
    <div class="grid lg:grid-cols-[1fr_300px] gap-6">
    <div class="bg-white rounded-2xl border border-ink/10 p-6">
        <h2 class="font-display text-lg text-ink mb-6">Revenue trend</h2>
        <div class="flex items-end justify-between gap-2 h-44">
            @foreach($revenueTrendHeights as $index => $height)
                <div class="flex-1 {{ $index == 11 ? 'bg-teal' : 'bg-teal/20' }} rounded-t-md" style="height:{{ $height }}%"></div>
            @endforeach
        </div>
        <div class="flex justify-between text-[11px] text-ink/30 mt-2">
            <span>{{ now()->subDays((int)$timeRange)->format('M d') }}</span>
            <span>&bull;</span>
            <span>&bull;</span>
            <span>&bull;</span>
            <span>{{ now()->format('M d') }}</span>
        </div>
    </div>

    <div class="space-y-6">
        <div class="bg-white rounded-2xl border border-ink/10 p-5">
        <h3 class="text-sm font-semibold text-ink mb-4">Attempts by level</h3>
        <div class="space-y-2.5 text-xs">
            <div>
            <div class="flex justify-between mb-1"><span class="text-ink/60">O/L</span><span class="font-semibold text-ink">{{ $attemptsByLevel['ol'] }}%</span></div>
            <div class="h-1.5 rounded-full bg-ink/10"><div class="h-1.5 rounded-full bg-teal" style="width:{{ $attemptsByLevel['ol'] }}%"></div></div>
            </div>
            <div>
            <div class="flex justify-between mb-1"><span class="text-ink/60">A/L</span><span class="font-semibold text-ink">{{ $attemptsByLevel['al'] }}%</span></div>
            <div class="h-1.5 rounded-full bg-ink/10"><div class="h-1.5 rounded-full bg-margin" style="width:{{ $attemptsByLevel['al'] }}%"></div></div>
            </div>
            <div>
            <div class="flex justify-between mb-1"><span class="text-ink/60">Grade 5</span><span class="font-semibold text-ink">{{ $attemptsByLevel['scholarship'] }}%</span></div>
            <div class="h-1.5 rounded-full bg-ink/10"><div class="h-1.5 rounded-full bg-gold" style="width:{{ $attemptsByLevel['scholarship'] }}%"></div></div>
            </div>
        </div>
        </div>

        <div class="bg-white rounded-2xl border border-ink/10 p-5">
        <h3 class="text-sm font-semibold text-ink mb-4">Revenue by plan</h3>
        <div class="space-y-2.5 text-xs">
            <div>
            <div class="flex justify-between mb-1"><span class="text-ink/60">Monthly subscribers</span><span class="font-semibold text-ink">0%</span></div>
            <div class="h-1.5 rounded-full bg-ink/10"><div class="h-1.5 rounded-full bg-teal" style="width:0%"></div></div>
            </div>
            <div>
            <div class="flex justify-between mb-1"><span class="text-ink/60">Pay-per-paper</span><span class="font-semibold text-ink">100%</span></div>
            <div class="h-1.5 rounded-full bg-ink/10"><div class="h-1.5 rounded-full bg-teal/40" style="width:100%"></div></div>
            </div>
        </div>
        </div>
    </div>
    </div>

    <!-- WEAKEST TOPICS -->
    <div class="bg-white rounded-2xl border border-ink/10 p-6">
    <div class="flex items-center justify-between mb-1">
        <h2 class="font-display text-lg text-ink">Weakest topics, across all students</h2>
    </div>
    <p class="text-xs text-ink/50 mb-6">Average score by topic — the lowest ones are worth extra papers or clearer explanations.</p>
    <div class="grid sm:grid-cols-2 gap-x-10 gap-y-4">
        @forelse($weakestTopics as $topic)
        <div>
            <div class="flex justify-between text-sm mb-1">
                <span class="text-ink/70">{{ $topic->topic_tag }}</span>
                <span class="font-semibold {{ $topic->score < 60 ? 'text-margin' : 'text-ink' }}">{{ round($topic->score) }}%</span>
            </div>
            <div class="h-2 rounded-full bg-ink/10">
                <div class="h-2 rounded-full {{ $topic->score < 60 ? 'bg-margin' : 'bg-teal' }}" style="width:{{ round($topic->score) }}%"></div>
            </div>
        </div>
        @empty
        <div class="col-span-2 text-sm text-ink/50 py-4">No topic data available for this period.</div>
        @endforelse
    </div>
    </div>

    <!-- FUNNEL + TOP PAPERS -->
    <div class="grid lg:grid-cols-2 gap-6">
    <div class="bg-white rounded-2xl border border-ink/10 p-6">
        <h2 class="font-display text-lg text-ink mb-6">Conversion funnel</h2>
        @php
            // Calculate widths for the funnel bars relative to Visited (assuming Visited is 100%)
            // Since Visited is mocked to 18,420, we use it as base. If newStudents > 18420, cap at 100%
            $mockVisited = 18420;
            $mockPreviewed = 7940;
            $studentsPct = $mockVisited > 0 ? min(100, round(($newStudents / $mockVisited) * 100)) : 0;
            $purchasedPct = $mockVisited > 0 ? min(100, round(($purchasedCount / $mockVisited) * 100)) : 0;
        @endphp
        <div class="space-y-3">
        <div>
            <div class="flex justify-between text-xs mb-1"><span class="text-ink/60">Visited site (Mock)</span><span class="font-semibold text-ink">{{ number_format($mockVisited) }}</span></div>
            <div class="h-6 rounded-md bg-teal" style="width:100%"></div>
        </div>
        <div>
            <div class="flex justify-between text-xs mb-1"><span class="text-ink/60">Previewed a paper (Mock)</span><span class="font-semibold text-ink">{{ number_format($mockPreviewed) }}</span></div>
            <div class="h-6 rounded-md bg-teal" style="width:43%"></div>
        </div>
        <div>
            <div class="flex justify-between text-xs mb-1"><span class="text-ink/60">Created an account</span><span class="font-semibold text-ink">{{ number_format($newStudents) }}</span></div>
            <div class="h-6 rounded-md bg-teal" style="width:{{ max(2, $studentsPct) }}%"></div>
        </div>
        <div>
            <div class="flex justify-between text-xs mb-1"><span class="text-ink/60">Purchased</span><span class="font-semibold text-ink">{{ number_format($purchasedCount) }}</span></div>
            <div class="h-6 rounded-md bg-teal" style="width:{{ max(1, $purchasedPct) }}%"></div>
        </div>
        </div>
    </div>

    <div class="bg-white rounded-2xl border border-ink/10 overflow-hidden">
        <div class="px-6 py-4 border-b border-ink/10">
        <h2 class="font-display text-lg text-ink">Top papers by attempts</h2>
        </div>
        <table class="w-full text-sm">
        <tbody class="divide-y divide-ink/5">
            @forelse($topPapers as $paper)
            <tr>
                <td class="px-6 py-3 text-ink/80">{{ $paper->title }}</td>
                <td class="px-6 py-3 text-right font-semibold text-ink">{{ number_format($paper->attempts_count) }}</td>
            </tr>
            @empty
            <tr><td colspan="2" class="px-6 py-4 text-center text-ink/50 text-sm">No attempts recorded in this period.</td></tr>
            @endforelse
        </tbody>
        </table>
    </div>
    </div>

</div>
