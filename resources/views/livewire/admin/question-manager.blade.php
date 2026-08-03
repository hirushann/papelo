
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
                    'text-slate-800 dark:text-white' => $step >= 1,
                    'text-slate-400' => $step < 1,
                ])
            >
                Subject
            </button>

            <flux:icon name="chevron-right" variant="mini" class="text-slate-400 size-4" />

            <button
                wire:click="goToStep(2)"
                @class([
                    'font-medium transition-colors',
                    'text-slate-800 dark:text-white' => $step >= 2,
                    'text-slate-400' => $step < 2,
                ])
                @disabled(!$selectedSubjectId)
            >
                Paper
            </button>

            <flux:icon name="chevron-right" variant="mini" class="text-slate-400 size-4" />

            <button
                wire:click="goToStep(3)"
                @class([
                    'font-medium transition-colors',
                    'text-slate-800 dark:text-white' => $step >= 3,
                    'text-slate-400' => $step < 3,
                ])
                @disabled(!$selectedPaperId)
            >
                Questions
            </button>
        </div>

        {{-- ── Success Message ──────────────────────────── --}}
        @if($successMessage)
            <flux:callout
                color="emerald"
                icon="check-circle"
                x-data="{ show: true }"
                x-init="setTimeout(() => { show = false; $wire.set('successMessage', '') }, 3000)"
                x-show="show"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
            >
                {{ $successMessage }}
            </flux:callout>
        @endif

        {{-- ══════════════════════════════════════════════ --}}
        {{-- STEP 1: Select or Create Subject               --}}
        {{-- ══════════════════════════════════════════════ --}}
        @if($step === 1)
        <flux:card wire:key="step-1-card">
            <div class="space-y-6">
                <flux:heading size="lg">Step 1 — Select a Subject</flux:heading>

                @if(!$creatingSubject)
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 items-end">
                        <flux:select
                            wire:model="selectedSubjectId"
                            label="Existing Subject"
                        >
                            <flux:select.option value="">Choose a subject...</flux:select.option>
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
                    <div class="space-y-4 border border-slate-200 dark:border-slate-700 rounded-xl p-5 bg-slate-50 dark:bg-slate-900">
                        <flux:heading size="sm">Create New Subject</flux:heading>

                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                            <flux:input
                                wire:model="subjectName"
                                label="Subject Name"
                                placeholder="e.g. Mathematics"
                            />

                            <flux:select wire:model="subjectLevel" label="Level">
                                <flux:select.option value="">Select level...</flux:select.option>
                                <flux:select.option value="scholarship">Scholarship (Grade 5)</flux:select.option>
                                <flux:select.option value="ol">O/L</flux:select.option>
                                <flux:select.option value="al">A/L</flux:select.option>
                            </flux:select>

                            <flux:select wire:model="subjectMedium" label="Medium">
                                <flux:select.option value="">Select medium...</flux:select.option>
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
        <flux:card wire:key="step-2-card">
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
                        >
                            <flux:select.option value="">Choose a paper...</flux:select.option>
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
                    <div class="space-y-4 border border-slate-200 dark:border-slate-700 rounded-xl p-5 bg-slate-50 dark:bg-slate-900">
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
            <flux:card wire:key="step-3-header">
                <div class="flex items-center justify-between">
                    <div>
                        <flux:heading size="lg">Step 3 — Manage Questions</flux:heading>
                        <flux:text class="mt-0.5 flex items-center gap-2">
                            <span>
                                {{ $this->selectedSubject?->name }} —
                                <strong>{{ $this->selectedPaper?->title }}</strong>
                                ({{ $this->selectedPaper?->year }})
                                · {{ $this->questions->count() }} question(s)
                            </span>
                            
                            @if($this->selectedPaper?->is_published)
                                <flux:badge color="emerald" size="sm">Published</flux:badge>
                            @else
                                <flux:badge color="slate" size="sm">Draft</flux:badge>
                            @endif
                        </flux:text>
                    </div>
                    <div class="flex gap-2">
                        @if($this->selectedPaper)
                            <flux:button 
                                wire:click="togglePublishPaper" 
                                variant="{{ $this->selectedPaper->is_published ? 'ghost' : 'outline' }}" 
                                size="sm"
                            >
                                {{ $this->selectedPaper->is_published ? 'Unpublish' : 'Publish Paper' }}
                            </flux:button>
                        @endif

                        @if(!$showQuestionForm)
                            <flux:button wire:click="showAddForm" variant="primary" icon="plus" size="sm">
                                Add Question
                            </flux:button>
                        @endif
                        <flux:button wire:click="goToStep(2)" variant="ghost" icon="arrow-left" size="sm">
                            Back
                        </flux:button>
                    </div>
                </div>
            </flux:card>

            @if($showQuestionForm)
                {{-- Question form ─────────────────────────── --}}
                <flux:card wire:key="step-3-form" class="ring-2 ring-indigo-500/20">
                    <form wire:submit="saveQuestion" class="space-y-6">
                        <div class="flex justify-between items-center border-b border-slate-200 dark:border-slate-700 pb-4">
                            <flux:heading size="lg">
                                {{ $editingQuestionId ? 'Edit Question' : 'Add New Question' }}
                            </flux:heading>
                            <flux:button wire:click="cancelEdit" variant="ghost" size="sm" icon="x-mark">
                                Cancel
                            </flux:button>
                        </div>

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

                        {{-- Image Preview & Removal --}}
                        @if($questionImage && !$errors->has('questionImage'))
                            <div class="flex items-center gap-3 bg-slate-50 dark:bg-slate-900 p-3 rounded-lg">
                                <img
                                    src="{{ $questionImage->temporaryUrl() }}"
                                    alt="Preview"
                                    class="h-20 w-auto rounded-lg border border-slate-200 dark:border-slate-700 object-cover"
                                />
                                <flux:text class="text-xs">New image preview</flux:text>
                            </div>
                        @elseif($editingQuestionId)
                            @php $existingImage = \App\Models\Question::find($editingQuestionId)?->image_path @endphp
                            @if($existingImage)
                                <div class="flex items-center gap-4 bg-slate-50 dark:bg-slate-900 p-3 rounded-lg">
                                    <img
                                        src="{{ Storage::url($existingImage) }}"
                                        alt="Current image"
                                        class="h-20 w-auto rounded-lg border border-slate-200 dark:border-slate-700 object-cover {{ $removeImage ? 'opacity-50 grayscale' : '' }}"
                                    />
                                    <div class="space-y-1">
                                        <flux:text class="text-xs">Current image</flux:text>
                                        <flux:checkbox wire:model="removeImage" label="Remove this image" />
                                    </div>
                                </div>
                            @endif
                        @endif

                        {{-- Answer Options ───────────────── --}}
                        <div class="space-y-4 pt-4">
                            <div>
                                <flux:heading size="sm">Answer Options</flux:heading>
                                <flux:text class="text-sm">Enter all 4 options and select the correct answer.</flux:text>
                            </div>

                            <flux:radio.group wire:model.live="correctOption">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    @foreach(['A', 'B', 'C', 'D'] as $index => $label)
                                        <flux:card class="p-4 flex flex-col gap-3 transition-colors {{ $correctOption === (string)$index ? 'ring-2 ring-emerald-500 bg-emerald-50 dark:bg-emerald-950/30' : 'bg-slate-50 dark:bg-slate-900' }}">
                                            
                                            <div class="flex items-center justify-between">
                                                <span class="font-semibold text-slate-700 dark:text-slate-300">Option {{ $label }}</span>
                                                <flux:radio value="{{ $index }}" label="Mark as correct" />
                                            </div>
                                            
                                            <div>
                                                <flux:textarea 
                                                    wire:model="options.{{ $index }}.text" 
                                                    placeholder="Enter option {{ $label }} text..." 
                                                    rows="2" 
                                                    class="bg-white dark:bg-slate-800"
                                                />
                                                <flux:error name="options.{{ $index }}.text" class="mt-1" />
                                            </div>

                                        </flux:card>
                                    @endforeach
                                </div>
                            </flux:radio.group>
                            
                            <flux:error name="correctOption" class="mt-2" />
                        </div>

                        {{-- Form Actions ─────────────────── --}}
                        <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-200 dark:border-slate-700">
                            <flux:button wire:click="cancelEdit" variant="ghost">
                                Cancel
                            </flux:button>
                            <flux:button type="submit" variant="primary" icon="{{ $editingQuestionId ? 'check' : 'plus' }}">
                                {{ $editingQuestionId ? 'Update Question' : 'Add Question' }}
                            </flux:button>
                        </div>
                    </form>
                </flux:card>
            @else
                {{-- Questions list ────────────────────────── --}}
                @if($this->questions->count() > 0)
                <flux:card wire:key="step-3-list">
                    <div class="space-y-4">
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
                                                <p class="truncate text-sm text-slate-700 dark:text-slate-300">
                                                    {{ Str::limit($question->question_text, 60) }}
                                                </p>
                                                @if($question->image_path)
                                                    <span class="inline-flex items-center gap-1 mt-1 text-xs text-slate-400">
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
                                                <span class="text-slate-400">—</span>
                                            @endif
                                        </flux:table.cell>

                                        <flux:table.cell>
                                            {{ $question->options->count() }}
                                        </flux:table.cell>

                                        <flux:table.cell>
                                            @php $correct = $question->options->firstWhere('is_correct', true) @endphp
                                            @if($correct)
                                                <flux:badge color="emerald" size="sm">
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
                                                    class="text-rose-500 hover:text-rose-700"
                                                />
                                            </div>
                                        </flux:table.cell>
                                    </flux:table.row>
                                @endforeach
                            </flux:table.rows>
                        </flux:table>
                    </div>
                </flux:card>
                @else
                    <flux:card class="text-center py-12 border-dashed border-2">
                        <flux:icon name="document-text" class="mx-auto h-12 w-12 text-slate-400 mb-4" />
                        <flux:heading size="lg">No questions yet</flux:heading>
                        <p class="text-slate-500 mt-2 mb-6">This paper doesn't have any questions. Get started by adding one.</p>
                        <flux:button wire:click="showAddForm" variant="primary" icon="plus">
                            Add First Question
                        </flux:button>
                    </flux:card>
                @endif
            @endif

        @endif
    </div>
</div>