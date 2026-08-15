<div class="w-full h-full flex flex-col">
  <x-slot name="mainClass">flex-1 flex flex-col overflow-hidden p-0</x-slot>
  <x-slot name="customHeader">
    <!-- Hide layout header -->
  </x-slot>

  <style>
    .field label { display:block; font-size:0.75rem; font-weight:600; color:rgba(34,49,74,0.7); margin-bottom:0.35rem; }
    .field textarea, .field input { width:100%; border:1px solid rgba(34,49,74,0.15); border-radius:0.5rem; padding:0.6rem 0.8rem; font-size:0.875rem; color:#22314A; background:#fff; }
    .field textarea:focus, .field input:focus { outline:none; border-color:#3F7D6B; box-shadow:0 0 0 3px rgba(63,125,107,0.15); }
    .option-row { display:flex; align-items:center; gap:0.75rem; border:1.5px solid rgba(34,49,74,0.12); border-radius:0.6rem; padding:0.5rem 0.75rem; }
    .option-row.correct { border-color:#3F7D6B; background:rgba(63,125,107,0.06); }
    .option-radio { width:20px; height:20px; border-radius:50%; border:2px solid rgba(34,49,74,0.25); flex-shrink:0; cursor:pointer; }
    .option-row.correct .option-radio { border-color:#3F7D6B; background:#3F7D6B; }
    .option-row input[type=text] { border:none; flex:1; padding:0.2rem; font-size:0.9rem; background:transparent; }
    .option-row input[type=text]:focus { outline:none; }
    .topic-pill { font-size:0.78rem; font-weight:500; border:1px solid rgba(34,49,74,0.15); border-radius:9999px; padding:0.35rem 0.85rem; color:rgba(34,49,74,0.6); cursor:pointer;}
    .topic-pill.selected { background:#22314A; border-color:#22314A; color:#F5F1E6; }
    .q-row { display:flex; align-items:center; gap:0.5rem; padding:0.5rem 0.6rem; border-radius:0.5rem; font-size:0.78rem; cursor:pointer;}
    .q-row:hover { background:rgba(34,49,74,0.04); }
    .q-row.active { background:rgba(63,125,107,0.1); }
    .type-badge { font-size:0.6rem; font-weight:700; padding:0.1rem 0.35rem; border-radius:0.25rem; flex-shrink:0; }
    .type-pill { font-size:0.75rem; font-weight:600; padding:0.5rem 0.8rem; border-radius:0.5rem; border:1.5px solid rgba(34,49,74,0.15); color:rgba(34,49,74,0.55); cursor:pointer; display:flex; align-items:center; gap:0.4rem; }
    .type-pill.active { background:#22314A; border-color:#22314A; color:#F5F1E6; }
    .type-pill svg { width:14px; height:14px; }
    .qtype-fieldset { display:none; }
    .qtype-fieldset.active { display:block; }
    .img-slot { aspect-ratio:1; border:1.5px solid rgba(34,49,74,0.15); border-radius:0.6rem; display:flex; flex-direction:column; align-items:center; justify-content:center; gap:0.4rem; color:rgba(34,49,74,0.35); font-size:0.7rem; cursor:pointer; position:relative; }
    .img-slot.correct { border-color:#3F7D6B; border-width:2.5px; background:rgba(63,125,107,0.06); }
    .img-slot .letter { position:absolute; top:6px; left:6px; font-size:0.65rem; font-weight:700; color:rgba(34,49,74,0.4); }
    .blank-tag { display:inline-flex; align-items:center; justify-content:center; width:22px; height:22px; border-radius:0.3rem; background:rgba(63,125,107,0.15); color:#3F7D6B; font-size:0.7rem; font-weight:700; }
    .wordbank-chip { font-size:0.8rem; font-weight:500; border:1px solid rgba(34,49,74,0.15); border-radius:9999px; padding:0.3rem 0.75rem; background:#fff; display:inline-flex; align-items:center; gap:0.4rem; }
    .hidden { display:none !important; }
    .criterion-row { display:flex; align-items:center; gap:0.6rem; border:1px solid rgba(34,49,74,0.12); border-radius:0.5rem; padding:0.5rem 0.7rem; }
    .criterion-row input[type=text] { flex:1; border:none; background:transparent; font-size:0.85rem; }
    .criterion-row input[type=text]:focus { outline:none; }
    .criterion-row input[type=number] { width:52px; text-align:center; border:1px solid rgba(34,49,74,0.15); border-radius:0.4rem; padding:0.3rem; font-size:0.8rem; }
    .disclosure { border-radius:0.5rem; padding:0.6rem 0.9rem; font-size:0.75rem; font-weight:500; display:flex; align-items:start; gap:0.5rem; }
    .disclosure svg { width:15px; height:15px; flex-shrink:0; margin-top:1px; }
  </style>

  @if($paper_id && $this->paper)
    <header class="h-16 bg-white border-b border-ink/10 flex items-center justify-between px-8 flex-shrink-0 w-full">
      <div class="flex items-center gap-4">
        <div>
          <p class="text-[11px] text-ink/40"><a href="{{ route('admin.papers') }}" wire:navigate class="hover:text-teal">Papers</a> / {{ $this->paper->subject->level ?? '' }} {{ $this->paper->subject->name ?? '' }} {{ $this->paper->year ?? '' }}</p>
          <h1 class="font-display text-lg text-ink -mt-0.5">Question Editor</h1>
        </div>
      </div>
      <div class="flex items-center gap-3">
        <a href="{{ route('admin.papers') }}" wire:navigate class="inline-flex items-center rounded-lg bg-ink text-paper text-sm font-semibold px-4 py-2 hover:bg-ink/90 transition">Done</a>
      </div>
    </header>

    <div class="flex-1 flex overflow-hidden">
      <!-- QUESTION LIST -->
      <div class="w-72 flex-shrink-0 border-r border-ink/10 bg-white flex flex-col">
        <div class="p-4 border-b border-ink/10">
          <div class="flex items-center justify-between text-xs mb-2">
            <span class="font-semibold text-ink">Progress</span>
            <span class="text-ink/50">{{ $this->questions->count() }} / {{ $this->paper->questions_count ?? $this->questions->count() }} added</span>
          </div>
          @php
              $target = max($this->paper->questions_count ?? 0, 1);
              $progress = min(($this->questions->count() / $target) * 100, 100);
          @endphp
          <div class="h-1.5 rounded-full bg-ink/10"><div class="h-1.5 rounded-full bg-teal" style="width:{{ $progress }}%"></div></div>
        </div>
        <div class="flex-1 overflow-y-auto p-2">
          @foreach($this->questions as $index => $q)
            @php
              $bg = '#fef3e0'; $color = '#C79A46'; $shortType = strtoupper($q->type);
              if(in_array($q->type, ['mcq', 'mcqimg', 'match'])) { $bg = '#dbe9ff'; $color = '#2a5db0'; }
              elseif(in_array($q->type, ['short', 'cloze'])) { $bg = '#fef3e0'; $color = '#C79A46'; }
              elseif(in_array($q->type, ['essay', 'structured'])) { $bg = '#fce8e6'; $color = '#B5514A'; $shortType = 'SELF'; }
            @endphp
            <div wire:click="editQuestion({{ $q->id }})" class="q-row {{ $editingQuestionId === $q->id ? 'active' : '' }}">
              <span class="type-badge" style="background:{{ $bg }};color:{{ $color }};">{{ $shortType }}</span>
              <span class="text-ink/40 w-4">{{ $index + 1 }}</span>
              <span class="flex-1 truncate {{ $editingQuestionId === $q->id ? 'text-ink font-medium' : 'text-ink/70' }}">
                {{ $q->question_text ? Str::limit($q->question_text, 30) : 'Blank question' }}
              </span>
            </div>
          @endforeach
          
          @if(!$editingQuestionId && $this->questions->count() > 0)
            <div class="q-row active">
              <span class="text-ink/30 w-4">{{ $this->questions->count() + 1 }}</span>
              <span class="flex-1 truncate text-ink font-medium italic">New Question</span>
            </div>
          @endif
        </div>
        <div class="p-3 border-t border-ink/10">
          <button wire:click="showAddForm" class="w-full text-sm font-semibold text-teal border border-teal/30 rounded-lg py-2 hover:bg-teal/5 transition">+ New question</button>
        </div>
      </div>

      <!-- EDITOR -->
      <div class="flex-1 overflow-y-auto">
        <div class="max-w-2xl mx-auto px-8 py-8" x-data="{ showPassage: {{ $instruction ? 'true' : 'false' }} }">

          @if($successMessage)
            <div class="mb-4 p-3 bg-teal/10 border border-teal/20 text-teal text-sm rounded-lg flex items-center justify-between">
              {{ $successMessage }}
              <button wire:click="$set('successMessage', '')" class="text-teal/60 hover:text-teal">&times;</button>
            </div>
          @endif

          <!-- Validation Errors -->
          @if ($errors->any())
            <div class="mb-6 p-4 rounded-xl bg-margin/10 border border-margin/20 text-margin text-sm">
                <div class="font-semibold mb-1">Please fix the following errors:</div>
                <ul class="list-disc pl-5 space-y-0.5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
          @endif

          <div class="flex items-center justify-between mb-6">
            <span class="font-display text-xl text-ink">
              {{ $editingQuestionId ? 'Edit Question' : 'New Question' }}
            </span>
            <label class="flex items-center gap-2 text-xs font-medium text-ink/60 cursor-pointer">
              <input type="checkbox" x-model="showPassage" class="rounded border-ink/25 text-teal focus:ring-teal">
              Shares a diagram with other sub-parts
            </label>
          </div>

          <!-- SHARED PASSAGE/DIAGRAM -->
          <div x-show="showPassage" class="bg-paper/60 border border-ink/10 rounded-xl p-5 mb-6" style="display:none;">
            <div class="field">
              <label>Shared Instruction / Context</label>
              <textarea wire:model="instruction" rows="2" placeholder="e.g. Read the passage and answer questions 1-5..."></textarea>
            </div>
            <p class="text-[11px] text-ink/40 mt-2">Parts of this question all reference this same context.</p>
          </div>

          <!-- QUESTION TYPE SELECTOR -->
          <div class="mb-6">
            <label class="block text-xs font-semibold text-ink/70 mb-2.5">Question type</label>
            <div class="flex flex-wrap gap-2">
              <button wire:click="$set('type', 'mcq')" class="type-pill {{ $type === 'mcq' ? 'active' : '' }}"><svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="9"/><path d="M9 12l2 2 4-4"/></svg>Multiple Choice</button>
              <button wire:click="$set('type', 'mcqimg')" class="type-pill {{ $type === 'mcqimg' ? 'active' : '' }}"><svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="9" cy="9" r="2"/><path d="m21 15-5-5L5 21"/></svg>MCQ (Images)</button>
              <button wire:click="$set('type', 'match')" class="type-pill {{ $type === 'match' ? 'active' : '' }}"><svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="6" cy="6" r="2"/><circle cx="6" cy="18" r="2"/><circle cx="18" cy="6" r="2"/><circle cx="18" cy="18" r="2"/><path d="M8 6h8M8 18h8M6 8v8M18 8v8"/></svg>Matching</button>
              <button wire:click="$set('type', 'cloze')" class="type-pill {{ $type === 'cloze' ? 'active' : '' }}"><svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M4 6h16M4 12h10M4 18h7"/></svg>Fill-in / Cloze</button>
              <button wire:click="$set('type', 'short')" class="type-pill {{ $type === 'short' ? 'active' : '' }}"><svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 20h9"/><path d="M16.5 3.5a2.1 2.1 0 0 1 3 3L7 19l-4 1 1-4Z"/></svg>Short Answer</button>
              <button wire:click="$set('type', 'essay')" class="type-pill {{ $type === 'essay' ? 'active' : '' }}"><svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M4 4h11l5 5v11a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V5a1 1 0 0 1 1-1z"/><path d="M8 12h8M8 16h5"/></svg>Essay</button>
              <button wire:click="$set('type', 'structured')" class="type-pill {{ $type === 'structured' ? 'active' : '' }}"><svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M9 11l3 3L22 4"/><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"/></svg>Structured (Self-Marked)</button>
            </div>
          </div>

          <!-- ============ MCQ (TEXT) ============ -->
          @if($type === 'mcq')
          <fieldset class="qtype-fieldset active">
            <div class="field mb-5">
              <label>Question text</label>
              <textarea wire:model="questionText" rows="2"></textarea>
            </div>
            
            <div class="mb-5">
                <label class="block text-xs font-semibold text-ink/70 mb-2">Question image (optional)</label>
                @if ($questionImage)
                    <div class="relative inline-block border border-ink/10 rounded-lg overflow-hidden bg-ink/5">
                        <img src="{{ $questionImage->temporaryUrl() }}" class="h-32 w-auto object-contain">
                        <button wire:click="$set('questionImage', null)" class="absolute top-2 right-2 w-7 h-7 bg-white/90 rounded-full flex items-center justify-center text-margin hover:bg-white shadow-sm">&times;</button>
                    </div>
                @elseif($editingQuestionId && \App\Models\Question::find($editingQuestionId)?->image_path && !$removeImage)
                    <div class="relative inline-block border border-ink/10 rounded-lg overflow-hidden bg-ink/5">
                        <img src="{{ Storage::url(\App\Models\Question::find($editingQuestionId)->image_path) }}" class="h-32 w-auto object-contain">
                        <button wire:click="$set('removeImage', true)" class="absolute top-2 right-2 w-7 h-7 bg-white/90 rounded-full flex items-center justify-center text-margin hover:bg-white shadow-sm">&times;</button>
                    </div>
                @else
                    <input type="file" wire:model="questionImage" class="text-sm text-ink/60 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-semibold file:bg-teal/10 file:text-teal hover:file:bg-teal/20" accept="image/*">
                @endif
            </div>

            <label class="block text-xs font-semibold text-ink/70 mb-2.5">Answer options — click radio to mark correct</label>
            <div class="space-y-2.5 mb-4">
              @foreach($options as $index => $option)
                <div class="option-row {{ (string)$correctOption === (string)$index ? 'correct' : '' }}">
                  <span class="option-radio" wire:click="$set('correctOption', '{{ $index }}')"></span>
                  <input type="text" wire:model="options.{{ $index }}.text" placeholder="Option {{ $index + 1 }}">
                  @if((string)$correctOption === (string)$index)
                    <span class="text-[11px] font-semibold text-teal">Correct</span>
                  @endif
                  @if(count($options) > 2)
                    <button wire:click="removeOption({{ $index }})" class="text-ink/30 hover:text-margin ml-2">&times;</button>
                  @endif
                </div>
              @endforeach
            </div>
            <button wire:click="addOption" class="text-xs font-semibold text-ink/50 border border-ink/15 rounded-lg px-3 py-1.5">+ Add option</button>
          </fieldset>
          @endif

          <!-- ============ MCQ (IMAGE OPTIONS) ============ -->
          @if($type === 'mcqimg')
          <fieldset class="qtype-fieldset active">
            <div class="field mb-5">
              <label>Instruction</label>
              <textarea wire:model="questionText" rows="2" placeholder="(e.g. Keep the door closed.)"></textarea>
            </div>
            <label class="block text-xs font-semibold text-ink/70 mb-2.5">Picture options — click to mark correct match</label>
            <div class="grid grid-cols-3 gap-3 mb-3">
              @foreach($options as $index => $option)
                <div class="img-slot {{ (string)$correctOption === (string)$index ? 'correct' : '' }}" wire:click="$set('correctOption', '{{ $index }}')">
                  <span class="letter">{{ chr(97 + $index) }}</span>
                  <!-- Since we don't have image upload per option yet, just use text for now -->
                  <input type="text" wire:model.live="options.{{ $index }}.text" class="bg-transparent border-b border-ink/20 text-center text-sm w-3/4 mt-4 focus:outline-none" placeholder="Image URL or Word">
                  @if(count($options) > 2)
                    <button wire:click.stop="removeOption({{ $index }})" class="absolute top-1 right-2 text-ink/30 hover:text-margin">&times;</button>
                  @endif
                </div>
              @endforeach
            </div>
            <button wire:click="addOption" class="text-xs font-semibold text-ink/50 border border-ink/15 rounded-lg px-3 py-1.5">+ Add picture option</button>
          </fieldset>
          @endif

          <!-- ============ MATCHING ============ -->
          @if($type === 'match')
          <fieldset class="qtype-fieldset active">
            <p class="text-xs text-ink/50 mb-4">Add prompts on the left, choices on the right, then pick the correct match for each prompt. One extra unused choice is fine.</p>
            <div class="grid grid-cols-2 gap-4 mb-4">
              <div>
                <label class="block text-xs font-semibold text-ink/70 mb-2">Prompts</label>
                <div class="space-y-2">
                  @foreach($matchPrompts as $index => $prompt)
                    <div class="flex gap-1">
                      <input type="text" wire:model="matchPrompts.{{ $index }}.text" class="field-input w-full text-sm border border-ink/15 rounded-lg px-3 py-2" placeholder="Prompt {{ $index + 1 }}">
                      <button wire:click="removeMatchPrompt({{ $index }})" class="text-ink/40 hover:text-margin">&times;</button>
                    </div>
                  @endforeach
                  <button wire:click="addMatchPrompt" class="text-[10px] font-semibold text-teal">+ Add Prompt</button>
                </div>
              </div>
              <div>
                <label class="block text-xs font-semibold text-ink/70 mb-2">Choices</label>
                <div class="space-y-2">
                  @foreach($matchChoices as $index => $choice)
                    <div class="flex items-center gap-2">
                      <input type="text" wire:model="matchChoices.{{ $index }}.text" class="flex-1 text-sm border border-ink/15 rounded-lg px-3 py-2" placeholder="Choice {{ chr(65 + $index) }}">
                      <button wire:click="removeMatchChoice({{ $index }})" class="text-ink/40 hover:text-margin">&times;</button>
                    </div>
                  @endforeach
                  <button wire:click="addMatchChoice" class="text-[10px] font-semibold text-teal">+ Add Choice</button>
                </div>
              </div>
            </div>
          </fieldset>
          @endif

          <!-- ============ CLOZE / FILL-IN ============ -->
          @if($type === 'cloze')
          <fieldset class="qtype-fieldset active">
            <div class="field mb-5">
              <label>Passage with blanks</label>
              <textarea wire:model="clozeText" rows="4" placeholder="Cats are most active [blank_1] the beginning and also at the end [blank_2] the day."></textarea>
              <button wire:click="insertClozeBlank" class="text-xs font-semibold text-teal mt-2">+ Insert [blank_x] tag</button>
            </div>
            
            <label class="block text-xs font-semibold text-ink/70 mb-2">Word bank</label>
            <div class="flex flex-wrap gap-2 mb-4">
              @foreach($clozeWords as $index => $word)
                <span class="wordbank-chip">
                  <input type="text" wire:model="clozeWords.{{ $index }}" class="bg-transparent border-none w-16 text-sm focus:outline-none" placeholder="word">
                  <button wire:click="removeClozeWord({{ $index }})" class="text-ink/40 hover:text-margin">&times;</button>
                </span>
              @endforeach
              <button wire:click="addClozeWord" class="text-xs font-semibold text-teal border border-dashed border-teal/40 rounded-full px-3 py-1">+ Add word</button>
            </div>

            <label class="block text-xs font-semibold text-ink/70 mb-2">Correct word per blank</label>
            <div class="grid grid-cols-3 gap-2 text-sm">
              @foreach($clozeAnswers as $blankIndex => $ans)
                <div class="flex items-center gap-2">
                  <span class="blank-tag">{{ $blankIndex }}</span>
                  <input type="text" wire:model="clozeAnswers.{{ $blankIndex }}" class="flex-1 border border-ink/15 rounded-lg px-2 py-1.5 text-sm" placeholder="correct word">
                </div>
              @endforeach
            </div>
          </fieldset>
          @endif

          <!-- ============ SHORT ANSWER ============ -->
          @if($type === 'short')
          <fieldset class="qtype-fieldset active">
            <div class="disclosure mb-5" style="background:rgba(199,154,70,0.1); color:#8a6a2f;">
              <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="9"/><path d="M12 8v4M12 16h.01"/></svg>
              Checks the final answer only — method isn't graded. Students are shown the full worked solution afterward to compare their own steps against.
            </div>
            <div class="field mb-5">
              <label>Question text</label>
              <textarea wire:model="questionText" rows="2"></textarea>
            </div>
            <label class="block text-xs font-semibold text-ink/70 mb-2">Acceptable final answers</label>
            <div class="flex flex-wrap gap-2 mb-2">
              @foreach($shortAnswers as $index => $ans)
                <span class="wordbank-chip">
                  <input type="text" wire:model="shortAnswers.{{ $index }}" class="bg-transparent border-none w-20 text-sm focus:outline-none" placeholder="12xy²">
                  <button wire:click="removeShortAnswer({{ $index }})" class="text-ink/40 hover:text-margin">&times;</button>
                </span>
              @endforeach
              <button wire:click="addShortAnswer" class="text-xs font-semibold text-teal border border-dashed border-teal/40 rounded-full px-3 py-1">+ Add accepted form</button>
            </div>
            <p class="text-[11px] text-ink/40 mb-3">Add every way a correct answer might be typed — with/without spaces, ^ vs ², etc.</p>
            
            <div class="field">
              <label>Worked solution (shown after submission)</label>
              <textarea wire:model="modelSolution" rows="3" placeholder="3x = 3·x, 2xy = 2·x·y, 4y² = 2²·y² → LCM = 2²·3·x·y² = 12xy²"></textarea>
            </div>
          </fieldset>
          @endif

          <!-- ============ ESSAY ============ -->
          @if($type === 'essay')
          <fieldset class="qtype-fieldset active">
            <div class="rounded-lg bg-gold/10 border border-gold/30 px-4 py-2.5 text-xs font-medium text-ink/70 mb-5">
              Practice only — essay questions aren't included in the auto-marked score. Students see your model answer after submitting, for self-review.
            </div>
            <div class="field mb-5">
              <label>Prompt</label>
              <textarea wire:model="questionText" rows="2"></textarea>
            </div>
            <div class="grid grid-cols-2 gap-4 mb-5">
              <div class="field"><label>Minimum words</label><input type="number" wire:model="essayMinWords"></div>
              <div class="field"><label>Maximum words</label><input type="number" wire:model="essayMaxWords"></div>
            </div>
            <div class="field">
              <label>Model answer (shown after submission)</label>
              <textarea wire:model="modelSolution" rows="6" placeholder="Write or paste a model answer students can compare their own writing against..."></textarea>
            </div>
          </fieldset>
          @endif

          <!-- ============ STRUCTURED (SELF-MARKED) ============ -->
          @if($type === 'structured')
          <fieldset class="qtype-fieldset active">
            <div class="disclosure mb-5" style="background:rgba(181,81,74,0.08); color:#8a3d38;">
              <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="9"/><path d="M12 8v4M12 16h.01"/></svg>
              Self-marked, not auto-graded. There's no single typeable "correct answer" for proofs, constructions, or multi-step working — students compare their own attempt against the marking scheme and model solution, then honestly score themselves.
            </div>

            <div class="field mb-5">
              <label>Question text</label>
              <textarea wire:model="questionText" rows="4"></textarea>
            </div>

            <div class="mb-5">
                <label class="block text-xs font-semibold text-ink/70 mb-2">Question image (optional)</label>
                @if ($questionImage)
                    <div class="relative inline-block border border-ink/10 rounded-lg overflow-hidden bg-ink/5">
                        <img src="{{ $questionImage->temporaryUrl() }}" class="h-32 w-auto object-contain">
                        <button wire:click="$set('questionImage', null)" class="absolute top-2 right-2 w-7 h-7 bg-white/90 rounded-full flex items-center justify-center text-margin hover:bg-white shadow-sm">&times;</button>
                    </div>
                @elseif($editingQuestionId && \App\Models\Question::find($editingQuestionId)?->image_path && !$removeImage)
                    <div class="relative inline-block border border-ink/10 rounded-lg overflow-hidden bg-ink/5">
                        <img src="{{ Storage::url(\App\Models\Question::find($editingQuestionId)->image_path) }}" class="h-32 w-auto object-contain">
                        <button wire:click="$set('removeImage', true)" class="absolute top-2 right-2 w-7 h-7 bg-white/90 rounded-full flex items-center justify-center text-margin hover:bg-white shadow-sm">&times;</button>
                    </div>
                @else
                    <input type="file" wire:model="questionImage" class="text-sm text-ink/60 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-semibold file:bg-teal/10 file:text-teal hover:file:bg-teal/20" accept="image/*">
                @endif
            </div>

            <label class="block text-xs font-semibold text-ink/70 mb-2.5">Marking scheme — enter the same partial-credit breakdown the real marking scheme uses</label>
            <div class="space-y-2 mb-2">
              @foreach($structuredCriteria as $index => $criterion)
                <div class="criterion-row">
                  <input type="text" wire:model="structuredCriteria.{{ $index }}.text" placeholder="Criterion description">
                  <input type="number" wire:model="structuredCriteria.{{ $index }}.marks" class="w-16 text-center border rounded p-1">
                  <span class="text-xs text-ink/40">marks</span>
                  <button wire:click="removeStructuredCriterion({{ $index }})" class="text-ink/40 hover:text-margin">&times;</button>
                </div>
              @endforeach
            </div>
            <button wire:click="addStructuredCriterion" class="text-xs font-semibold text-teal border border-dashed border-teal/40 rounded-full px-3 py-1 mb-4">+ Add criterion</button>
            
            @php
              $totalMarks = collect($structuredCriteria)->sum(fn($c) => (int)($c['marks'] ?? 0));
            @endphp
            <div class="flex justify-end text-sm font-semibold text-ink mb-6">Total: {{ $totalMarks }} marks</div>

            <div class="field mb-5">
              <label>Model solution (full worked proof, shown after submission)</label>
              <textarea wire:model="modelSolution" rows="5" placeholder="Write out the complete step-by-step solution students will compare their own working against..."></textarea>
            </div>

            <label class="flex items-center gap-2 text-xs text-ink/60 mb-5">
              <input type="checkbox" wire:model="allowPhoto" class="rounded border-ink/25 text-teal focus:ring-teal">
              Allow students to upload a photo of hand-drawn working (for constructions/graphs)
            </label>
          </fieldset>
          @endif

          <!-- TOPIC + ACTIONS -->
          <div class="mt-6 mb-8">
            <label class="block text-xs font-semibold text-ink/70 mb-2.5">Topic tag</label>
            <div class="field">
              <input type="text" wire:model="topicTag" placeholder="e.g. Algebra, Geometry, etc.">
            </div>
          </div>

          <div class="flex items-center justify-between pt-5 border-t border-ink/10">
            @if($editingQuestionId)
              <button wire:click="deleteQuestion" wire:confirm="Are you sure you want to delete this question?" class="text-sm font-medium text-margin hover:underline">Delete question</button>
            @else
              <div></div>
            @endif
            
            <div class="flex items-center gap-3">
              <button wire:click="saveQuestion(false)" class="text-sm font-semibold text-ink/70 border border-ink/15 rounded-lg px-4 py-2.5 hover:bg-ink/5">Save</button>
              <button wire:click="saveQuestion(true)" class="text-sm font-semibold bg-teal text-paper rounded-lg px-4 py-2.5 hover:bg-teal/90">Save &amp; add next &rarr;</button>
            </div>
          </div>
        </div>
      </div>
    </div>
  @else
    <x-slot name="customHeader">
      <header class="h-16 bg-white border-b border-ink/10 flex items-center justify-between px-8 flex-shrink-0">
        <h1 class="font-display text-lg text-ink">Questions</h1>
      </header>
    </x-slot>
    <div class="flex flex-col items-center justify-center w-full flex-1 bg-[#F7F4EC] text-ink/60 h-full p-8">
      <svg class="w-16 h-16 mb-4 text-ink/20" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path d="M12 22c5.523 0 10-4.477 10-10S17.523 2 12 2 2 6.477 2 12s4.477 10 10 10z"/><path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"/><path d="M12 17h.01"/></svg>
      <p class="text-lg font-medium text-ink/80 mb-2">Select a paper to manage its questions</p>
      <p class="text-sm mb-6">Since questions are attached to a specific paper, you must select one first.</p>
      
      <div class="w-full max-w-sm">
        <select wire:model.live="paper_id" class="w-full border border-ink/15 rounded-lg px-4 py-3 text-sm bg-white text-ink/70 focus:outline-none focus:ring-2 focus:ring-teal/30 focus:border-teal cursor-pointer">
          <option value="">-- Choose a Paper --</option>
          @foreach($this->allPapers as $paperOption)
            <option value="{{ $paperOption->id }}">{{ $paperOption->title }} ({{ $paperOption->year }})</option>
          @endforeach
        </select>
      </div>

      <div class="mt-8 text-xs text-ink/40">
        Or <a href="{{ route('admin.papers') }}" wire:navigate class="hover:text-teal underline">go back to Papers</a>
      </div>
    </div>
  @endif
</div>