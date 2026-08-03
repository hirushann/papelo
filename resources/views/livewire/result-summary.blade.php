<?php

use App\Models\Attempt;
use Illuminate\Support\Facades\Auth;
use Livewire\Volt\Component;
use Livewire\Attributes\Computed;

new class extends Component
{
    public Attempt $attempt;

    public function mount(Attempt $attempt)
    {
        // 1. Authorize: Ensure the logged-in user owns this attempt
        if ($attempt->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access to this result summary.');
        }

        // 2. Completion Check: If the attempt is not complete, redirect to quiz taker
        if ($attempt->completed_at === null) {
            $this->redirect(route('quiz.take', $attempt->paper_id));
            return;
        }

        // 3. Eager load relations for performance
        $attempt->load([
            'paper.subject', 
            'answers.question.options', 
            'answers.selectedOption'
        ]);

        $this->attempt = $attempt;
    }

    #[Computed]
    public function scorePercentage()
    {
        return $this->attempt->scorePercentage();
    }

    #[Computed]
    public function topicBreakdown()
    {
        return $this->attempt->answers->groupBy(function ($answer) {
            $tag = $answer->question->topic_tag;
            return (!empty($tag)) ? trim($tag) : 'Uncategorized';
        })->map(function ($answers, $topicName) {
            $total = $answers->count();
            $correct = $answers->where('is_correct', true)->count();
            $percentage = $total > 0 ? round(($correct / $total) * 100) : 0;

            return [
                'name' => $topicName,
                'total' => $total,
                'correct' => $correct,
                'percentage' => $percentage,
            ];
        })->values()->sortByDesc('percentage')->toArray();
    }

    public function getProgressColor(int $percentage): string
    {
        if ($percentage >= 80) return 'emerald';
        if ($percentage >= 50) return 'amber';
        return 'rose';
    }
}; ?>

<div class="py-12">
    <div class="max-w-5xl mx-auto sm:px-6 lg:px-8 space-y-8">
        
        {{-- ── Header & Overall Score ─────────────────────── --}}
        <flux:card class="flex flex-col sm:flex-row sm:items-center justify-between gap-6">
            <div class="space-y-2">
                <flux:heading size="lg">{{ $attempt->paper->title }}</flux:heading>
                <flux:text class="text-slate-500">
                    {{ $attempt->paper->subject->name }} • {{ ucfirst($attempt->paper->subject->medium) }} Medium
                </flux:text>
                
                <div class="mt-4">
                    <div class="text-4xl font-bold tracking-tight text-slate-900 dark:text-white">
                        {{ $attempt->score }} <span class="text-2xl text-slate-400 font-medium">/ {{ $attempt->total_questions }} correct</span>
                    </div>
                    <div class="mt-1">
                        <flux:badge size="lg" color="{{ $this->getProgressColor($this->scorePercentage) }}">
                            {{ $this->scorePercentage }}% Overall
                        </flux:badge>
                    </div>
                </div>
            </div>

            <div class="shrink-0 flex flex-col items-center gap-2">
                <flux:button 
                    href="{{ route('quiz.take', $attempt->paper_id) }}" 
                    variant="primary" 
                    icon="arrow-path"
                    class="w-full sm:w-auto"
                >
                    Retry this paper
                </flux:button>
                <flux:text size="sm" class="text-slate-400">
                    This starts a fresh attempt
                </flux:text>
            </div>
        </flux:card>

        {{-- ── Topic Breakdown ────────────────────────────── --}}
        <div class="space-y-4">
            <flux:heading size="lg">Topic Breakdown</flux:heading>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @foreach($this->topicBreakdown as $topic)
                    <flux:card wire:key="topic-{{ Str::slug($topic['name']) }}">
                        <div class="flex justify-between items-center mb-2">
                            <flux:heading size="sm" class="truncate pe-4" title="{{ $topic['name'] }}">
                                {{ $topic['name'] }}
                            </flux:heading>
                            <span class="text-sm font-medium text-slate-500 shrink-0">
                                {{ $topic['correct'] }} / {{ $topic['total'] }}
                            </span>
                        </div>
                        <flux:progress 
                            color="{{ $this->getProgressColor($topic['percentage']) }}" 
                            style="--flux-progress-percentage: {{ $topic['percentage'] }}%;" 
                        />
                    </flux:card>
                @endforeach
            </div>
        </div>

        {{-- ── Detailed Question Review ───────────────────── --}}
        <div class="space-y-4">
            <flux:heading size="lg">Detailed Review</flux:heading>
            
            <flux:card class="overflow-hidden">
                <flux:table>
                    <flux:table.columns>
                        <flux:table.column>#</flux:table.column>
                        <flux:table.column>Question</flux:table.column>
                        <flux:table.column>Your Answer</flux:table.column>
                        <flux:table.column>Correct Answer</flux:table.column>
                        <flux:table.column>Result</flux:table.column>
                    </flux:table.columns>

                    <flux:table.rows>
                        @foreach($attempt->answers as $index => $answer)
                            @php
                                $questionNumber = $index + 1;
                                $correctOption = $answer->question->options->firstWhere('is_correct', true);
                            @endphp
                            <flux:table.row wire:key="answer-{{ $answer->id }}">
                                <flux:table.cell class="font-medium text-slate-500">
                                    {{ $questionNumber }}
                                </flux:table.cell>
                                
                                <flux:table.cell>
                                    <div class="max-w-xs sm:max-w-sm truncate" title="{{ $answer->question->question_text }}">
                                        {{ $answer->question->question_text }}
                                    </div>
                                    @if($answer->question->topic_tag)
                                        <flux:badge size="sm" class="mt-1">
                                            {{ $answer->question->topic_tag }}
                                        </flux:badge>
                                    @endif
                                </flux:table.cell>
                                
                                <flux:table.cell>
                                    @if($answer->selectedOption)
                                        <span class="{{ $answer->is_correct ? 'text-emerald-700 dark:text-emerald-400 font-medium' : 'text-rose-700 dark:text-rose-400 font-medium' }}">
                                            {{ Str::limit($answer->selectedOption->option_text, 30) }}
                                        </span>
                                    @else
                                        <flux:badge color="zinc" size="sm">Skipped</flux:badge>
                                    @endif
                                </flux:table.cell>
                                
                                <flux:table.cell class="text-slate-700 dark:text-slate-300">
                                    @if($correctOption)
                                        {{ Str::limit($correctOption->option_text, 30) }}
                                    @else
                                        <span class="text-slate-400 italic">N/A</span>
                                    @endif
                                </flux:table.cell>
                                
                                <flux:table.cell>
                                    @if($answer->is_correct)
                                        <flux:icon name="check-circle" variant="solid" class="size-6 text-emerald-500" />
                                    @else
                                        <flux:icon name="x-circle" variant="solid" class="size-6 text-rose-500" />
                                    @endif
                                </flux:table.cell>
                            </flux:table.row>
                        @endforeach
                    </flux:table.rows>
                </flux:table>
            </flux:card>
        </div>

    </div>
</div>
