<?php

use App\Models\Subject;
use App\Models\Paper;
use App\Models\Question;
use App\Models\Option;
use Illuminate\Support\Str;
use Livewire\Volt\Component;
use Livewire\WithFileUploads;
use Livewire\Attributes\Validate;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;

new #[Layout('layouts.app')] class extends Component
{
    use WithFileUploads;

    // ── Step tracking ──────────────────────────────────
    public int $step = 1;

    // ── Subject fields ─────────────────────────────────
    public ?int $selectedSubjectId = null;
    public string $subjectName = '';
    public string $subjectLevel = '';
    public string $subjectMedium = '';
    public bool $creatingSubject = false;

    // ── Paper fields ───────────────────────────────────
    public ?int $selectedPaperId = null;
    public string $paperTitle = '';
    public string $paperYear = '';
    public string $paperPrice = '';
    public string $paperDuration = '';
    public bool $creatingPaper = false;

    // ── Question fields ────────────────────────────────
    public string $questionText = '';
    public $questionImage = null;
    public string $topicTag = '';
    public array $options = [
        ['text' => '', 'is_correct' => false],
        ['text' => '', 'is_correct' => false],
        ['text' => '', 'is_correct' => false],
        ['text' => '', 'is_correct' => false],
    ];
    public ?string $correctOption = null;

    // ── Edit mode ──────────────────────────────────────
    public ?int $editingQuestionId = null;

    // ── Flash messages ─────────────────────────────────
    public string $successMessage = '';

    // ── Computed properties ────────────────────────────

    #[Computed]
    public function subjects()
    {
        return Subject::orderBy('name')->get();
    }

    #[Computed]
    public function selectedSubject()
    {
        return $this->selectedSubjectId
            ? Subject::find($this->selectedSubjectId)
            : null;
    }

    #[Computed]
    public function papers()
    {
        return $this->selectedSubjectId
            ? Paper::where('subject_id', $this->selectedSubjectId)->orderByDesc('year')->get()
            : collect();
    }

    #[Computed]
    public function selectedPaper()
    {
        return $this->selectedPaperId
            ? Paper::find($this->selectedPaperId)
            : null;
    }

    #[Computed]
    public function questions()
    {
        return $this->selectedPaperId
            ? Question::where('paper_id', $this->selectedPaperId)
                ->with('options')
                ->orderBy('order_index')
                ->get()
            : collect();
    }

    // ── Subject actions ────────────────────────────────

    public function selectSubject(): void
    {
        if ($this->selectedSubjectId) {
            $this->step = 2;
            $this->resetPaperFields();
        }
    }

    public function toggleCreateSubject(): void
    {
        $this->creatingSubject = !$this->creatingSubject;
        $this->resetValidation();
    }

    public function createSubject(): void
    {
        $this->validate([
            'subjectName' => 'required|string|max:255',
            'subjectLevel' => 'required|in:scholarship,ol,al',
            'subjectMedium' => 'required|in:english,sinhala,tamil',
        ]);

        $subject = Subject::create([
            'name' => $this->subjectName,
            'level' => $this->subjectLevel,
            'medium' => $this->subjectMedium,
            'slug' => Str::slug($this->subjectName . '-' . $this->subjectLevel . '-' . $this->subjectMedium),
        ]);

        $this->selectedSubjectId = $subject->id;
        $this->creatingSubject = false;
        $this->subjectName = '';
        $this->subjectLevel = '';
        $this->subjectMedium = '';
        $this->step = 2;
        $this->successMessage = 'Subject created successfully!';
        unset($this->subjects);
    }

    // ── Paper actions ──────────────────────────────────

    public function selectPaper(): void
    {
        if ($this->selectedPaperId) {
            $this->step = 3;
        }
    }

    public function toggleCreatePaper(): void
    {
        $this->creatingPaper = !$this->creatingPaper;
        $this->resetValidation();
    }

    public function createPaper(): void
    {
        $this->validate([
            'paperTitle' => 'required|string|max:255',
            'paperYear' => 'required|integer|min:1990|max:' . (date('Y') + 1),
            'paperPrice' => 'required|numeric|min:0',
            'paperDuration' => 'required|integer|min:1',
        ]);

        $paper = Paper::create([
            'subject_id' => $this->selectedSubjectId,
            'title' => $this->paperTitle,
            'year' => (int) $this->paperYear,
            'price' => (float) $this->paperPrice,
            'duration_minutes' => (int) $this->paperDuration,
            'is_published' => false,
        ]);

        $this->selectedPaperId = $paper->id;
        $this->creatingPaper = false;
        $this->resetPaperFormFields();
        $this->step = 3;
        $this->successMessage = 'Paper created successfully!';
        unset($this->papers);
    }

    // ── Question actions ───────────────────────────────

    public function saveQuestion(): void
    {
        $this->validate([
            'questionText' => 'required|string',
            'questionImage' => 'nullable|image|max:2048',
            'topicTag' => 'nullable|string|max:100',
            'options.0.text' => 'required|string|max:1000',
            'options.1.text' => 'required|string|max:1000',
            'options.2.text' => 'required|string|max:1000',
            'options.3.text' => 'required|string|max:1000',
            'correctOption' => 'required|in:0,1,2,3',
        ], [
            'questionText.required' => 'The question text is required.',
            'options.0.text.required' => 'Option A is required.',
            'options.1.text.required' => 'Option B is required.',
            'options.2.text.required' => 'Option C is required.',
            'options.3.text.required' => 'Option D is required.',
            'correctOption.required' => 'You must select which option is the correct answer.',
        ]);

        // Handle image upload
        $imagePath = null;
        if ($this->questionImage) {
            $imagePath = $this->questionImage->store('question-images', 'public');
        }

        $nextOrder = $this->editingQuestionId
            ? Question::find($this->editingQuestionId)->order_index
            : (Question::where('paper_id', $this->selectedPaperId)->max('order_index') ?? 0) + 1;

        if ($this->editingQuestionId) {
            // Update existing question
            $question = Question::find($this->editingQuestionId);
            $question->update([
                'question_text' => $this->questionText,
                'image_path' => $imagePath ?? $question->image_path,
                'topic_tag' => $this->topicTag ?: null,
                'order_index' => $nextOrder,
            ]);

            // Delete existing options and recreate
            $question->options()->delete();
        } else {
            // Create new question
            $question = Question::create([
                'paper_id' => $this->selectedPaperId,
                'question_text' => $this->questionText,
                'image_path' => $imagePath,
                'topic_tag' => $this->topicTag ?: null,
                'order_index' => $nextOrder,
            ]);
        }

        // Create options
        foreach ($this->options as $index => $option) {
            Option::create([
                'question_id' => $question->id,
                'option_text' => $option['text'],
                'is_correct' => (int) $this->correctOption === $index,
                'order_index' => $index + 1,
            ]);
        }

        $this->successMessage = $this->editingQuestionId
            ? 'Question updated successfully!'
            : 'Question added successfully!';

        $this->resetQuestionFields();
        unset($this->questions);
    }

    public function editQuestion(int $questionId): void
    {
        $question = Question::with('options')->find($questionId);
        if (!$question) return;

        $this->editingQuestionId = $question->id;
        $this->questionText = $question->question_text;
        $this->topicTag = $question->topic_tag ?? '';
        $this->questionImage = null;

        $this->options = [];
        $this->correctOption = null;

        foreach ($question->options->sortBy('order_index')->values() as $index => $option) {
            $this->options[$index] = [
                'text' => $option->option_text,
                'is_correct' => $option->is_correct,
            ];
            if ($option->is_correct) {
                $this->correctOption = (string) $index;
            }
        }

        // Ensure we always have 4 options
        while (count($this->options) < 4) {
            $this->options[] = ['text' => '', 'is_correct' => false];
        }

        $this->dispatch('scroll-to-form');
    }

    public function deleteQuestion(int $questionId): void
    {
        Question::find($questionId)?->delete();

        // Reorder remaining questions
        $questions = Question::where('paper_id', $this->selectedPaperId)
            ->orderBy('order_index')
            ->get();

        foreach ($questions as $index => $question) {
            $question->update(['order_index' => $index + 1]);
        }

        $this->successMessage = 'Question deleted.';
        unset($this->questions);
    }

    public function cancelEdit(): void
    {
        $this->resetQuestionFields();
    }

    // ── Navigation ─────────────────────────────────────

    public function goToStep(int $step): void
    {
        if ($step === 1) {
            $this->step = 1;
        } elseif ($step === 2 && $this->selectedSubjectId) {
            $this->step = 2;
        } elseif ($step === 3 && $this->selectedPaperId) {
            $this->step = 3;
        }
    }

    // ── Helpers ────────────────────────────────────────

    private function resetPaperFields(): void
    {
        $this->selectedPaperId = null;
        $this->creatingPaper = false;
        $this->resetPaperFormFields();
    }

    private function resetPaperFormFields(): void
    {
        $this->paperTitle = '';
        $this->paperYear = '';
        $this->paperPrice = '';
        $this->paperDuration = '';
    }

    private function resetQuestionFields(): void
    {
        $this->editingQuestionId = null;
        $this->questionText = '';
        $this->questionImage = null;
        $this->topicTag = '';
        $this->correctOption = null;
        $this->options = [
            ['text' => '', 'is_correct' => false],
            ['text' => '', 'is_correct' => false],
            ['text' => '', 'is_correct' => false],
            ['text' => '', 'is_correct' => false],
        ];
        $this->resetValidation();
    }
}; ?>

<div class="py-12">
    <div class="max-w-5xl mx-auto sm:px-6 lg:px-8 space-y-6">

        {{-- ── Page Header ─────────────────────────────── --}}
        <div class="flex items-center justify-between">
            <div>
                <flux:heading size="xl">Question Manager</flux:heading>
                <flux:text class="mt-1">Create subjects, papers, and manage exam questions.</flux:text>
            </div>
        </div>

        {{-- ── Breadcrumb / Step Indicator ──────────────── --}}
        <div class="flex items-center gap-2 text-sm">
            <button
                wire:click="goToStep(1)"
                @class([
                    'font-medium transition-colors',
                    'text-zinc-800 dark:text-white' => $step >= 1,
                    'text-zinc-400' => $step < 1,
                ])
            >
                Subject
            </button>

            <flux:icon name="chevron-right" variant="mini" class="text-zinc-400 size-4" />

            <button
                wire:click="goToStep(2)"
                @class([
                    'font-medium transition-colors',
                    'text-zinc-800 dark:text-white' => $step >= 2,
                    'text-zinc-400' => $step < 2,
                ])
                @disabled(!$selectedSubjectId)
            >
                Paper
            </button>

            <flux:icon name="chevron-right" variant="mini" class="text-zinc-400 size-4" />

            <button
                wire:click="goToStep(3)"
                @class([
                    'font-medium transition-colors',
                    'text-zinc-800 dark:text-white' => $step >= 3,
                    'text-zinc-400' => $step < 3,
                ])
                @disabled(!$selectedPaperId)
            >
                Questions
            </button>
        </div>

        {{-- ── Success Message ──────────────────────────── --}}
        @if($successMessage)
            <div
                x-data="{ show: true }"
                x-init="setTimeout(() => { show = false; $wire.set('successMessage', '') }, 3000)"
                x-show="show"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
                class="flex items-center gap-2 rounded-lg bg-emerald-50 dark:bg-emerald-950/30 border border-emerald-200 dark:border-emerald-800 px-4 py-3 text-sm text-emerald-700 dark:text-emerald-300"
            >
                <flux:icon name="check-circle" variant="mini" class="size-5 text-emerald-500" />
                {{ $successMessage }}
            </div>
        @endif

        {{-- ══════════════════════════════════════════════ --}}
        {{-- STEP 1: Select or Create Subject               --}}
        {{-- ══════════════════════════════════════════════ --}}
        @if($step === 1)
        <flux:card>
            <div class="space-y-6">
                <flux:heading size="lg">Step 1 — Select a Subject</flux:heading>

                @if(!$creatingSubject)
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 items-end">
                        <flux:select
                            wire:model="selectedSubjectId"
                            label="Existing Subject"
                            placeholder="Choose a subject..."
                        >
                            @foreach($this->subjects as $subject)
                                <flux:select.option value="{{ $subject->id }}">
                                    {{ $subject->name }} ({{ strtoupper($subject->level) }} — {{ ucfirst($subject->medium) }})
                                </flux:select.option>
                            @endforeach
                        </flux:select>

                        <div class="flex gap-2">
                            <flux:button wire:click="selectSubject" variant="primary" icon="arrow-right">
                                Continue
                            </flux:button>
                            <flux:button wire:click="toggleCreateSubject" variant="ghost" icon="plus">
                                New Subject
                            </flux:button>
                        </div>
                    </div>
                @else
                    <div class="space-y-4 border border-zinc-200 dark:border-zinc-700 rounded-xl p-5 bg-zinc-50 dark:bg-zinc-900">
                        <flux:heading size="sm">Create New Subject</flux:heading>

                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                            <flux:input
                                wire:model="subjectName"
                                label="Subject Name"
                                placeholder="e.g. Mathematics"
                            />

                            <flux:select wire:model="subjectLevel" label="Level" placeholder="Select level...">
                                <flux:select.option value="scholarship">Scholarship (Grade 5)</flux:select.option>
                                <flux:select.option value="ol">O/L</flux:select.option>
                                <flux:select.option value="al">A/L</flux:select.option>
                            </flux:select>

                            <flux:select wire:model="subjectMedium" label="Medium" placeholder="Select medium...">
                                <flux:select.option value="english">English</flux:select.option>
                                <flux:select.option value="sinhala">Sinhala</flux:select.option>
                                <flux:select.option value="tamil">Tamil</flux:select.option>
                            </flux:select>
                        </div>

                        <div class="flex gap-2">
                            <flux:button wire:click="createSubject" variant="primary" icon="check">
                                Create Subject
                            </flux:button>
                            <flux:button wire:click="toggleCreateSubject" variant="ghost">
                                Cancel
                            </flux:button>
                        </div>
                    </div>
                @endif
            </div>
        </flux:card>
        @endif

        {{-- ══════════════════════════════════════════════ --}}
        {{-- STEP 2: Select or Create Paper                 --}}
        {{-- ══════════════════════════════════════════════ --}}
        @if($step === 2)
        <flux:card>
            <div class="space-y-6">
                <div class="flex items-center justify-between">
                    <div>
                        <flux:heading size="lg">Step 2 — Select a Paper</flux:heading>
                        <flux:text class="mt-0.5">
                            Subject: <strong>{{ $this->selectedSubject?->name }}</strong>
                            ({{ strtoupper($this->selectedSubject?->level ?? '') }} — {{ ucfirst($this->selectedSubject?->medium ?? '') }})
                        </flux:text>
                    </div>
                    <flux:button wire:click="goToStep(1)" variant="ghost" icon="arrow-left" size="sm">
                        Back
                    </flux:button>
                </div>

                @if(!$creatingPaper)
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 items-end">
                        <flux:select
                            wire:model="selectedPaperId"
                            label="Existing Paper"
                            placeholder="Choose a paper..."
                        >
                            @foreach($this->papers as $paper)
                                <flux:select.option value="{{ $paper->id }}">
                                    {{ $paper->title }} ({{ $paper->year }}) — Rs. {{ number_format($paper->price, 2) }}
                                </flux:select.option>
                            @endforeach
                        </flux:select>

                        <div class="flex gap-2">
                            <flux:button wire:click="selectPaper" variant="primary" icon="arrow-right">
                                Continue
                            </flux:button>
                            <flux:button wire:click="toggleCreatePaper" variant="ghost" icon="plus">
                                New Paper
                            </flux:button>
                        </div>
                    </div>
                @else
                    <div class="space-y-4 border border-zinc-200 dark:border-zinc-700 rounded-xl p-5 bg-zinc-50 dark:bg-zinc-900">
                        <flux:heading size="sm">Create New Paper</flux:heading>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <flux:input
                                wire:model="paperTitle"
                                label="Paper Title"
                                placeholder="e.g. 2024 Past Paper"
                            />

                            <flux:input
                                wire:model="paperYear"
                                label="Year"
                                type="number"
                                placeholder="e.g. 2024"
                            />

                            <flux:input
                                wire:model="paperPrice"
                                label="Price (LKR)"
                                type="number"
                                placeholder="e.g. 250.00"
                            />

                            <flux:input
                                wire:model="paperDuration"
                                label="Duration (minutes)"
                                type="number"
                                placeholder="e.g. 60"
                            />
                        </div>

                        <div class="flex gap-2">
                            <flux:button wire:click="createPaper" variant="primary" icon="check">
                                Create Paper
                            </flux:button>
                            <flux:button wire:click="toggleCreatePaper" variant="ghost">
                                Cancel
                            </flux:button>
                        </div>
                    </div>
                @endif
            </div>
        </flux:card>
        @endif

        {{-- ══════════════════════════════════════════════ --}}
        {{-- STEP 3: Manage Questions                       --}}
        {{-- ══════════════════════════════════════════════ --}}
        @if($step === 3)

            {{-- Paper context header ──────────────────── --}}
            <flux:card>
                <div class="flex items-center justify-between">
                    <div>
                        <flux:heading size="lg">Step 3 — Manage Questions</flux:heading>
                        <flux:text class="mt-0.5">
                            {{ $this->selectedSubject?->name }} —
                            <strong>{{ $this->selectedPaper?->title }}</strong>
                            ({{ $this->selectedPaper?->year }})
                            · {{ $this->questions->count() }} question(s)
                        </flux:text>
                    </div>
                    <flux:button wire:click="goToStep(2)" variant="ghost" icon="arrow-left" size="sm">
                        Back
                    </flux:button>
                </div>
            </flux:card>

            {{-- Question form ─────────────────────────── --}}
            <flux:card x-data @scroll-to-form.window="$el.scrollIntoView({ behavior: 'smooth', block: 'start' })">
                <form wire:submit="saveQuestion" class="space-y-6">
                    <flux:heading size="lg">
                        {{ $editingQuestionId ? 'Edit Question' : 'Add New Question' }}
                    </flux:heading>

                    {{-- Question Text --}}
                    <flux:textarea
                        wire:model="questionText"
                        label="Question Text"
                        placeholder="Enter the question..."
                        rows="3"
                    />

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        {{-- Image Upload --}}
                        <flux:input
                            wire:model="questionImage"
                            label="Question Image (optional)"
                            type="file"
                            accept="image/*"
                        />

                        {{-- Topic Tag --}}
                        <flux:input
                            wire:model="topicTag"
                            label="Topic Tag (optional)"
                            placeholder="e.g. Algebra, Grammar"
                        />
                    </div>

                    {{-- Image Preview --}}
                    @if($questionImage && !$errors->has('questionImage'))
                        <div class="flex items-center gap-3">
                            <img
                                src="{{ $questionImage->temporaryUrl() }}"
                                alt="Preview"
                                class="h-20 w-auto rounded-lg border border-zinc-200 dark:border-zinc-700 object-cover"
                            />
                            <flux:text class="text-xs">Image preview</flux:text>
                        </div>
                    @elseif($editingQuestionId)
                        @php $existingImage = \App\Models\Question::find($editingQuestionId)?->image_path @endphp
                        @if($existingImage)
                            <div class="flex items-center gap-3">
                                <img
                                    src="{{ Storage::url($existingImage) }}"
                                    alt="Current image"
                                    class="h-20 w-auto rounded-lg border border-zinc-200 dark:border-zinc-700 object-cover"
                                />
                                <flux:text class="text-xs">Current image (upload new to replace)</flux:text>
                            </div>
                        @endif
                    @endif

                    {{-- Answer Options ───────────────── --}}
                    <div class="space-y-4">
                        <flux:heading size="sm">Answer Options</flux:heading>
                        <flux:text class="text-sm">Enter all 4 options and select the correct answer.</flux:text>

                        <flux:radio.group wire:model="correctOption" label="Select the correct answer">
                            <div class="space-y-3">
                                @foreach(['A', 'B', 'C', 'D'] as $index => $label)
                                    <div class="flex items-start gap-3 p-3 rounded-lg border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 transition-all
                                        {{ $correctOption === (string) $index ? 'ring-2 ring-emerald-500 border-emerald-300 dark:border-emerald-700 bg-emerald-50 dark:bg-emerald-950/20' : '' }}
                                    ">
                                        <div class="pt-2">
                                            <flux:radio value="{{ $index }}" label="Option {{ $label }}" />
                                        </div>
                                        <div class="flex-1">
                                            <flux:input
                                                wire:model="options.{{ $index }}.text"
                                                placeholder="Enter option {{ $label }}..."
                                            />
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </flux:radio.group>

                        @error('correctOption')
                            <p class="text-sm text-red-500 flex items-center gap-1.5">
                                <flux:icon name="exclamation-circle" variant="mini" class="size-4" />
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    {{-- Form Actions ─────────────────── --}}
                    <div class="flex items-center gap-3 pt-2">
                        <flux:button type="submit" variant="primary" icon="{{ $editingQuestionId ? 'check' : 'plus' }}">
                            {{ $editingQuestionId ? 'Update Question' : 'Add Question' }}
                        </flux:button>

                        @if($editingQuestionId)
                            <flux:button wire:click="cancelEdit" variant="ghost">
                                Cancel Editing
                            </flux:button>
                        @endif
                    </div>
                </form>
            </flux:card>

            {{-- Questions list ────────────────────────── --}}
            @if($this->questions->count() > 0)
            <flux:card>
                <div class="space-y-4">
                    <flux:heading size="lg">
                        Questions ({{ $this->questions->count() }})
                    </flux:heading>

                    <flux:table>
                        <flux:table.columns>
                            <flux:table.column>#</flux:table.column>
                            <flux:table.column>Question</flux:table.column>
                            <flux:table.column>Topic</flux:table.column>
                            <flux:table.column>Options</flux:table.column>
                            <flux:table.column>Correct</flux:table.column>
                            <flux:table.column align="end">Actions</flux:table.column>
                        </flux:table.columns>

                        <flux:table.rows>
                            @foreach($this->questions as $question)
                                <flux:table.row :key="$question->id">
                                    <flux:table.cell variant="strong">
                                        {{ $question->order_index }}
                                    </flux:table.cell>

                                    <flux:table.cell>
                                        <div class="max-w-xs">
                                            <p class="truncate text-sm text-zinc-700 dark:text-zinc-300">
                                                {{ Str::limit($question->question_text, 60) }}
                                            </p>
                                            @if($question->image_path)
                                                <span class="inline-flex items-center gap-1 mt-1 text-xs text-zinc-400">
                                                    <flux:icon name="photo" variant="mini" class="size-3.5" />
                                                    Has image
                                                </span>
                                            @endif
                                        </div>
                                    </flux:table.cell>

                                    <flux:table.cell>
                                        @if($question->topic_tag)
                                            <flux:badge size="sm">{{ $question->topic_tag }}</flux:badge>
                                        @else
                                            <span class="text-zinc-400">—</span>
                                        @endif
                                    </flux:table.cell>

                                    <flux:table.cell>
                                        {{ $question->options->count() }}
                                    </flux:table.cell>

                                    <flux:table.cell>
                                        @php $correct = $question->options->firstWhere('is_correct', true) @endphp
                                        @if($correct)
                                            <flux:badge color="green" size="sm">
                                                {{ chr(64 + $correct->order_index) }}
                                            </flux:badge>
                                        @endif
                                    </flux:table.cell>

                                    <flux:table.cell align="end">
                                        <div class="flex items-center justify-end gap-1">
                                            <flux:button
                                                wire:click="editQuestion({{ $question->id }})"
                                                variant="ghost"
                                                icon="pencil-square"
                                                size="sm"
                                            />
                                            <flux:button
                                                wire:click="deleteQuestion({{ $question->id }})"
                                                wire:confirm="Are you sure you want to delete this question?"
                                                variant="ghost"
                                                icon="trash"
                                                size="sm"
                                                class="text-red-500 hover:text-red-700"
                                            />
                                        </div>
                                    </flux:table.cell>
                                </flux:table.row>
                            @endforeach
                        </flux:table.rows>
                    </flux:table>
                </div>
            </flux:card>
            @endif

        @endif
    </div>
</div>