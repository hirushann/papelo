<?php

use App\Models\Paper;
use App\Models\Attempt;
use App\Models\AttemptAnswer;
use App\Models\Option;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Livewire\Volt\Component;
use Livewire\Attributes\Computed;
use Carbon\Carbon;

new class extends Component
{
    public Paper $paper;
    public Attempt $attempt;

    // Ordered list of questions with their options
    public $questions;

    // Store answers keyed by question ID: [question_id => selected_option_id]
    public array $answers = [];

    // Current question index (0-based)
    public int $currentIndex = 0;

    // Timer duration in seconds
    public int $durationSeconds = 0;

    public bool $submitted = false;

    public function mount(Paper $paper)
    {
        $this->paper = $paper;

        // 1. Verify access: must be purchased or free
        if (! $this->canAccessPaper()) {
            abort(403, 'You must purchase this paper to take the quiz.');
        }

        // 2. Load attempt or create a new one
        $this->loadOrCreateAttempt();

        // 3. Load all questions and options in order from cache
        $this->questions = Cache::remember("paper_{$this->paper->id}_questions", 3600, function () {
            return $this->paper->questions()->with('options')->get();
        });

        if ($this->questions->isEmpty()) {
            abort(404, 'This paper has no questions yet.');
        }

        // 4. Populate existing answers if resuming
        foreach ($this->attempt->answers as $ans) {
            $this->answers[$ans->question_id] = $ans->selected_option_id;
        }

        // 5. Calculate remaining time
        $elapsedSeconds = Carbon::now()->diffInSeconds($this->attempt->started_at);
        $totalSeconds = $this->paper->duration_minutes * 60;
        
        $this->durationSeconds = max(0, $totalSeconds - $elapsedSeconds);
        
        // If time is already up on a resumed attempt, force submit
        if ($this->durationSeconds <= 0 && $this->paper->duration_minutes > 0) {
            $this->submitQuiz();
        }
    }

    private function canAccessPaper(): bool
    {
        if ((float) $this->paper->price === 0.00) {
            return true;
        }

        return $this->paper->purchases()
            ->where('user_id', Auth::id())
            ->where('status', 'completed')
            ->exists();
    }

    private function loadOrCreateAttempt(): void
    {
        $existingAttempt = Attempt::where('user_id', Auth::id())
            ->where('paper_id', $this->paper->id)
            ->whereNull('completed_at')
            ->with('answers')
            ->first();

        if ($existingAttempt) {
            $this->attempt = $existingAttempt;
        } else {
            $this->attempt = Attempt::create([
                'user_id' => Auth::id(),
                'paper_id' => $this->paper->id,
                'started_at' => now(),
                'total_questions' => $this->paper->questions()->count(),
            ]);
            // Reload with empty answers collection
            $this->attempt->setRelation('answers', collect());
        }
    }

    #[Computed]
    public function currentQuestion()
    {
        return $this->questions[$this->currentIndex];
    }

    #[Computed]
    public function totalQuestions()
    {
        return count($this->questions);
    }

    #[Computed]
    public function progressPercent()
    {
        if ($this->totalQuestions === 0) return 0;
        return round((($this->currentIndex + 1) / $this->totalQuestions) * 100);
    }

    #[Computed]
    public function answeredCount()
    {
        // Filter out null/empty values
        return count(array_filter($this->answers));
    }

    public function nextQuestion(): void
    {
        if ($this->currentIndex < $this->totalQuestions - 1) {
            $this->currentIndex++;
        }
    }

    public function previousQuestion(): void
    {
        if ($this->currentIndex > 0) {
            $this->currentIndex--;
        }
    }

    public function goToQuestion(int $index): void
    {
        if ($index >= 0 && $index < $this->totalQuestions) {
            $this->currentIndex = $index;
        }
    }

    public function submitQuiz(): void
    {
        if ($this->submitted || $this->attempt->completed_at !== null) {
            return;
        }

        $this->submitted = true;
        
        // Wrap scoring in transaction
        DB::transaction(function () {
            $score = 0;
            
            // Delete existing attempt answers if we are resuming to prevent duplicates
            $this->attempt->answers()->delete();

            $attemptAnswersToInsert = [];

            foreach ($this->questions as $question) {
                $selectedOptionId = $this->answers[$question->id] ?? null;
                $isCorrect = false;

                if ($selectedOptionId) {
                    // Check if selected option is correct
                    $option = $question->options->firstWhere('id', $selectedOptionId);
                    if ($option && $option->is_correct) {
                        $isCorrect = true;
                        $score++;
                    }
                }

                if ($selectedOptionId) {
                    $attemptAnswersToInsert[] = [
                        'attempt_id' => $this->attempt->id,
                        'question_id' => $question->id,
                        'selected_option_id' => $selectedOptionId,
                        'is_correct' => $isCorrect,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }
            }
            
            if (!empty($attemptAnswersToInsert)) {
                AttemptAnswer::insert($attemptAnswersToInsert);
            }

            // Update attempt with final score
            $this->attempt->update([
                'completed_at' => now(),
                'score' => $score,
            ]);
        });

        $this->redirect(route('result.summary', $this->attempt));
    }
}; ?>

<div class="py-12" x-data="quizTimer({{ $durationSeconds }})">
    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">

        <flux:card class="space-y-6 relative overflow-visible p-0 sm:p-0">
            
            {{-- ── Header & Timer (Sticky) ────────────────────────────── --}}
            <div class="sticky top-0 z-20 bg-white dark:bg-slate-900 p-6 sm:px-8 border-b border-slate-200 dark:border-slate-700 flex flex-col sm:flex-row sm:items-center justify-between gap-4 shadow-sm rounded-t-xl">
                <div>
                    <flux:heading size="lg">{{ $paper->title }}</flux:heading>
                    <flux:text size="sm" class="mt-1">
                        {{ $paper->subject->name }} • {{ $paper->subject->medium }}
                    </flux:text>
                </div>
                
                @if($paper->duration_minutes > 0)
                    <div class="shrink-0 flex items-center gap-2">
                        <flux:text size="sm" class="font-medium text-slate-500">Time Remaining</flux:text>
                        <flux:badge 
                            :color="isWarning ? 'rose' : 'amber'" 
                            size="lg"
                            class="font-mono text-base font-bold transition-colors"
                            x-text="display"
                            x-bind:color="isWarning ? 'rose' : 'amber'"
                        >
                            {{ sprintf('%02d:%02d', floor($durationSeconds / 60), $durationSeconds % 60) }}
                        </flux:badge>
                    </div>
                @endif
            </div>

            <div class="px-6 sm:px-8 pb-8 space-y-6">

            {{-- ── Progress Bar ──────────────────────────────── --}}
            <div class="space-y-2">
                <div class="flex justify-between items-center text-sm">
                    <span class="font-medium text-slate-700 dark:text-slate-300">
                        Question {{ $currentIndex + 1 }} of {{ $this->totalQuestions }}
                    </span>
                    <span class="text-slate-500">
                        {{ $this->answeredCount }} answered
                    </span>
                </div>
                <flux:progress color="indigo" style="--flux-progress-percentage: {{ $this->progressPercent }}%;" />
            </div>

            {{-- ── Question Area ─────────────────────────────── --}}
            <div class="py-4">
                <div class="mb-6 space-y-4">
                    <flux:heading size="md" class="text-base sm:text-lg leading-relaxed">
                        {{ $this->currentQuestion->question_text }}
                    </flux:heading>

                    @if($this->currentQuestion->image_path)
                        <div class="mt-4">
                            <img 
                                src="{{ Storage::url($this->currentQuestion->image_path) }}" 
                                alt="Question Image" 
                                class="max-h-80 rounded-md border border-slate-200 dark:border-slate-700"
                            >
                        </div>
                    @endif
                </div>

                {{-- Options Radio Group --}}
                <flux:radio.group 
                    wire:model.live="answers.{{ $this->currentQuestion->id }}" 
                    class="space-y-3"
                >
                    @foreach($this->currentQuestion->options as $option)
                        <flux:radio 
                            value="{{ $option->id }}" 
                            label="{{ $option->option_text }}" 
                            class="p-4 rounded-xl border border-slate-200 dark:border-slate-700 hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors [&:has(:checked)]:border-indigo-500 [&:has(:checked)]:bg-indigo-50 dark:[&:has(:checked)]:bg-indigo-500/10"
                        />
                    @endforeach
                </flux:radio.group>
            </div>

            {{-- ── Navigation & Submit ───────────────────────── --}}
            <div class="pt-6 border-t border-slate-200 dark:border-slate-700 space-y-6">
                
                <div class="flex items-center justify-between">
                    <flux:button 
                        wire:click="previousQuestion" 
                        variant="outline" 
                        icon="chevron-left" 
                        :disabled="$currentIndex === 0"
                        wire:loading.attr="disabled"
                    >
                        Previous
                    </flux:button>
                    
                    <flux:button 
                        wire:click="nextQuestion" 
                        variant="outline" 
                        icon-trailing="chevron-right"
                        :disabled="$currentIndex === $this->totalQuestions - 1"
                        wire:loading.attr="disabled"
                    >
                        Next
                    </flux:button>
                </div>

                <div class="flex flex-col sm:flex-row items-center justify-between gap-4 pt-4">
                    {{-- Question Navigator Dots --}}
                    <div class="flex flex-wrap items-center justify-center gap-1.5 max-w-full overflow-x-auto pb-2 sm:pb-0">
                        @foreach($questions as $idx => $q)
                            @php
                                $isCurrent = $idx === $currentIndex;
                                $isAnswered = isset($answers[$q->id]);
                            @endphp
                            <button 
                                type="button"
                                wire:click="goToQuestion({{ $idx }})"
                                title="Question {{ $idx + 1 }}"
                                class="w-8 h-8 rounded-full flex items-center justify-center text-xs font-medium transition-all
                                @if($isCurrent)
                                    ring-2 ring-indigo-500 ring-offset-2 dark:ring-offset-slate-900 bg-indigo-500 text-white
                                @elseif($isAnswered)
                                    bg-emerald-100 text-emerald-700 dark:bg-emerald-500/20 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-500/30
                                @else
                                    bg-slate-100 text-slate-500 hover:bg-slate-200 dark:bg-slate-800 dark:text-slate-400 dark:hover:bg-slate-700
                                @endif
                                "
                            >
                                {{ $idx + 1 }}
                            </button>
                        @endforeach
                    </div>

                    <div class="shrink-0">
                        @if($this->answeredCount < $this->totalQuestions)
                            <flux:modal.trigger name="submit-warning">
                                <flux:button variant="primary" icon="check-circle">
                                    Submit Quiz
                                </flux:button>
                            </flux:modal.trigger>
                        @else
                            <flux:button 
                                wire:click="submitQuiz" 
                                wire:confirm="Are you sure you want to submit your quiz? You cannot change your answers after submission."
                                variant="primary" 
                                icon="check-circle"
                                wire:loading.attr="disabled"
                            >
                                Submit Quiz
                            </flux:button>
                        @endif
                    </div>
                </div>

            </div>
        </flux:card>

        {{-- Warning Modal --}}
        <flux:modal name="submit-warning" class="min-w-[22rem]">
            <form wire:submit="submitQuiz">
                <div class="space-y-6">
                    <div>
                        <flux:heading size="lg">Unanswered Questions!</flux:heading>
                        <flux:text class="text-sm text-slate-500 mt-2">
                            You have only answered {{ $this->answeredCount }} out of {{ $this->totalQuestions }} questions. Are you sure you want to submit your quiz now?
                        </flux:text>
                    </div>

                    <div class="flex gap-2">
                        <flux:spacer />
                        <flux:modal.close>
                            <flux:button variant="ghost">Resume Quiz</flux:button>
                        </flux:modal.close>
                        <flux:button type="submit" variant="danger">Submit Anyway</flux:button>
                    </div>
                </div>
            </form>
        </flux:modal>

    </div>
</div>

{{-- Alpine Component for Client-side Timer --}}
@script
<script>
    Alpine.data('quizTimer', (initialSeconds) => ({
        remaining: initialSeconds,
        interval: null,
        
        get minutes() {
            return Math.floor(this.remaining / 60);
        },
        
        get secs() {
            return this.remaining % 60;
        },
        
        get display() {
            return `${String(this.minutes).padStart(2, '0')}:${String(this.secs).padStart(2, '0')}`;
        },
        
        get isWarning() {
            // Under 60 seconds is a warning
            return this.remaining > 0 && this.remaining <= 60;
        },
        
        init() {
            if (this.remaining <= 0) return;
            
            this.interval = setInterval(() => {
                this.remaining--;
                
                if (this.remaining <= 0) {
                    clearInterval(this.interval);
                    this.remaining = 0;
                    
                    // Auto-submit when time is up
                    $wire.submitQuiz();
                }
            }, 1000);
        },
        
        destroy() {
            if (this.interval) {
                clearInterval(this.interval);
            }
        }
    }));
</script>
@endscript
