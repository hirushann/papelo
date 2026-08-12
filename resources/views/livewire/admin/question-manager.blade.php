<div class="w-full h-full flex flex-col">
  <x-slot name="mainClass">flex-1 flex flex-col overflow-hidden p-0</x-slot>
  <x-slot name="customHeader">
    <!-- Hide layout header -->
  </x-slot>

  @if($paper_id && $this->paper)
    <header class="h-16 bg-white border-b border-ink/10 flex items-center justify-between px-8 flex-shrink-0 w-full">
      <div class="flex items-center gap-4">
        <button wire:click="$set('paper_id', '')" class="w-8 h-8 flex items-center justify-center rounded-lg hover:bg-ink/5 text-ink/40 hover:text-ink transition -ml-2" title="Back to paper selection">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" /></svg>
        </button>
        <div>
          <p class="text-[11px] text-ink/40"><a href="{{ route('admin.papers') }}" wire:navigate class="hover:text-teal">Papers</a> / {{ $this->paper->subject->level ?? '' }} {{ $this->paper->subject->name ?? '' }} {{ $this->paper->year ?? '' }}</p>
          <h1 class="font-display text-lg text-ink -mt-0.5">Question Editor</h1>
        </div>
      </div>
      <div class="flex items-center gap-3">
        <flux:modal.trigger name="import-questions">
          <button class="text-sm font-medium text-ink/60 hover:text-ink flex items-center gap-1.5 border border-ink/15 rounded-lg px-3 py-1.5 hover:bg-ink/5 transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" x2="12" y1="3" y2="15"/></svg>
            Import
          </button>
        </flux:modal.trigger>
        <button wire:click="exportCsv" class="text-sm font-medium text-ink/60 hover:text-ink flex items-center gap-1.5 border border-ink/15 rounded-lg px-3 py-1.5 hover:bg-ink/5 transition">
          <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" x2="12" y1="15" y2="3"/></svg>
          Export
        </button>
        <span class="w-px h-5 bg-ink/10 mx-1"></span>
        <a href="{{ route('papers') }}" class="text-sm font-medium text-ink/60 hover:text-ink" target="_blank">Preview catalog &rarr;</a>
        <a href="{{ route('admin.papers') }}" wire:navigate class="inline-flex items-center rounded-lg bg-ink text-paper text-sm font-semibold px-4 py-2 hover:bg-ink/90 transition">Done</a>
      </div>
    </header>

    <div class="flex-1 flex overflow-hidden h-[calc(100vh-4rem)]">
      <!-- QUESTION LIST SIDEBAR -->
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
            <div wire:click="editQuestion({{ $q->id }})" class="q-row {{ $editingQuestionId === $q->id ? 'active' : '' }} flex items-center gap-2 p-2 rounded-lg cursor-pointer hover:bg-ink/5">
              <span class="{{ $editingQuestionId === $q->id ? 'text-teal font-semibold' : 'text-ink/40' }} w-5 text-xs">{{ $index + 1 }}</span>
              <span class="flex-1 truncate text-xs {{ $editingQuestionId === $q->id ? 'text-ink font-medium' : 'text-ink/70' }}">
                {{ $q->question_text ? Str::limit($q->question_text, 30) : 'Blank question' }}
              </span>
              <span class="w-1.5 h-1.5 rounded-full bg-teal"></span>
            </div>
          @endforeach
          
          @if(!$editingQuestionId)
            <div class="q-row active flex items-center gap-2 p-2 rounded-lg cursor-pointer bg-ink/5">
              <span class="text-teal font-semibold w-5 text-xs">{{ $this->questions->count() + 1 }}</span>
              <span class="flex-1 truncate text-ink font-medium text-xs italic">New blank question</span>
            </div>
          @endif
        </div>
        <div class="p-3 border-t border-ink/10">
          <button wire:click="showAddForm" class="w-full text-sm font-semibold text-teal border border-teal/30 rounded-lg py-2 hover:bg-teal/5">
            + New blank question
          </button>
        </div>
      </div>

      <!-- EDITOR -->
      <div class="flex-1 overflow-y-auto bg-[#F7F4EC]">
        <div class="max-w-2xl mx-auto px-8 py-8">
          
          @if($successMessage)
            <div class="mb-6 rounded-lg bg-teal/10 border border-teal/20 p-4 flex items-start gap-3">
              <svg class="w-5 h-5 text-teal shrink-0 mt-0.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M20 6 9 17l-5-5"/></svg>
              <p class="text-sm font-medium text-teal">{{ $successMessage }}</p>
            </div>
          @endif

          <div class="flex items-center justify-between mb-6">
            <span class="font-display text-xl text-ink">
              Question {{ $editingQuestionId ? $this->questions->where('id', $editingQuestionId)->first()->order_index ?? ($this->questions->count() + 1) : ($this->questions->count() + 1) }}
            </span>
            <!-- Difficulty buttons removed per spec -->
          </div>

          <form wire:submit="saveQuestion(false)">
            <div class="mb-5">
              <label class="block text-xs font-semibold text-ink/70 mb-1.5">Question text</label>
              <textarea wire:model="questionText" rows="3" class="w-full border border-ink/15 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-teal focus:ring-1 focus:ring-teal"></textarea>
              @error('questionText') <span class="text-xs text-margin">{{ $message }}</span> @enderror
            </div>

            <div class="mb-6">
              @if($questionImage || (!empty($editingQuestionId) && $this->questions->where('id', $editingQuestionId)->first()->image_path && !$removeImage))
                  <div class="mb-3 relative inline-block">
                      <img src="{{ $questionImage ? $questionImage->temporaryUrl() : Storage::url($this->questions->where('id', $editingQuestionId)->first()->image_path) }}" class="h-32 rounded border border-ink/10">
                      <button type="button" wire:click="$set('removeImage', true); $set('questionImage', null)" class="absolute -top-2 -right-2 bg-white rounded-full shadow p-1 text-margin hover:bg-margin hover:text-white">
                          <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M18 6 6 18M6 6l12 12"/></svg>
                      </button>
                  </div>
              @else
                  <label class="text-xs font-semibold text-ink/50 border border-ink/15 rounded-lg px-3 py-1.5 hover:border-teal/40 hover:text-teal inline-flex items-center gap-1.5 cursor-pointer bg-white">
                      <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="9" cy="9" r="2"/><path d="m21 15-5-5L5 21"/></svg>
                      Attach diagram
                      <input type="file" wire:model="questionImage" class="hidden" accept="image/*">
                  </label>
              @endif
              @error('questionImage') <span class="block text-xs text-margin mt-1">{{ $message }}</span> @enderror
            </div>

            <div class="mb-6">
              <label class="block text-xs font-semibold text-ink/70 mb-2.5">Answer options &mdash; click a circle to mark it correct</label>
              <div class="space-y-2.5">
                @foreach([0, 1, 2, 3] as $index)
                  <div class="flex items-center gap-3 border-1.5 border-ink/12 rounded-lg p-2 transition {{ $correctOption === (string)$index ? 'border-teal bg-teal/5' : 'bg-white' }}">
                    <div class="shrink-0 flex items-center justify-center">
                      <input type="radio" wire:model.live="correctOption" value="{{ $index }}" id="opt_{{ $index }}" class="sr-only">
                      <label for="opt_{{ $index }}" class="w-5 h-5 rounded-full border-2 border-ink/25 flex items-center justify-center cursor-pointer {{ $correctOption === (string)$index ? 'border-teal bg-teal' : 'hover:border-teal/50' }}">
                        @if($correctOption === (string)$index)
                          <span class="w-2 h-2 rounded-full bg-paper"></span>
                        @endif
                      </label>
                    </div>
                    <input type="text" wire:model="options.{{ $index }}.text" placeholder="Option {{ $index + 1 }}" class="flex-1 border-none bg-transparent text-sm focus:outline-none focus:ring-0">
                    @if($correctOption === (string)$index)
                      <span class="text-[11px] font-semibold text-teal mr-1 shrink-0">Correct</span>
                    @endif
                  </div>
                  @error('options.'.$index.'.text') <span class="block text-xs text-margin">{{ $message }}</span> @enderror
                @endforeach
                @error('correctOption') <span class="block text-xs text-margin">{{ $message }}</span> @enderror
              </div>
            </div>

            <div class="mb-8">
              <label class="block text-xs font-semibold text-ink/70 mb-2.5">Topic tag</label>
              <div class="flex flex-wrap gap-2">
                <input type="text" wire:model="topicTag" placeholder="e.g. Cells & Genetics" class="text-xs font-medium border border-ink/15 rounded-full px-3 py-1.5 focus:outline-none focus:border-teal focus:ring-1 focus:ring-teal bg-white w-48">
                @error('topicTag') <span class="text-xs text-margin">{{ $message }}</span> @enderror
              </div>
            </div>

            <div class="flex items-center justify-between pt-5 border-t border-ink/10">
              @if($editingQuestionId)
                <button type="button" wire:click="deleteQuestion" wire:confirm="Are you sure you want to delete this question?" class="text-sm font-medium text-margin hover:underline">Delete question</button>
              @else
                <div></div>
              @endif
              <div class="flex items-center gap-3">
                <button type="submit" class="text-sm font-semibold text-ink/70 border border-ink/15 rounded-lg bg-white px-4 py-2.5 hover:border-ink/30 transition">Save</button>
                <button type="button" wire:click="saveQuestion(true)" class="text-sm font-semibold bg-teal text-paper rounded-lg px-4 py-2.5 hover:bg-teal/90 transition">Save &amp; add next &rarr;</button>
              </div>
            </div>
          </form>
          
        </div>
      </div>
    </div>
  @else
    <x-slot name="customHeader">
      <header class="h-16 bg-white border-b border-ink/10 flex items-center justify-between px-8 flex-shrink-0">
        <h1 class="font-display text-lg text-ink">Questions</h1>
      </header>
    </x-slot>
    <div class="flex flex-col items-center justify-center w-full flex-1 bg-[#F7F4EC] text-ink/60">
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

  <!-- IMPORT MODAL USING FLUX -->
  <flux:modal name="import-questions" class="md:w-full md:max-w-md">
      <form wire:submit="importCsv">
          <div class="flex items-center justify-between px-6 py-5 border-b border-ink/10">
              <h2 class="font-display text-lg text-ink">Import Questions</h2>
              <flux:modal.close>
                <button type="button" class="text-ink/40 hover:text-ink text-xl leading-none">&times;</button>
              </flux:modal.close>
          </div>
          <div class="p-6 space-y-5">
              @if($successMessage)
                <div class="rounded-lg bg-teal/10 border border-teal/20 p-4 flex items-start gap-3">
                  <svg class="w-5 h-5 text-teal shrink-0 mt-0.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M20 6 9 17l-5-5"/></svg>
                  <p class="text-sm font-medium text-teal">{{ $successMessage }}</p>
                </div>
              @endif

              <div class="text-sm text-ink/70">
                <p class="mb-3">Upload a CSV file to bulk import questions. Note: Images cannot be imported via CSV; you must add them manually after importing.</p>
                <button type="button" wire:click="downloadTemplate" class="text-teal font-semibold hover:underline flex items-center gap-1.5">
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" x2="12" y1="15" y2="3"/></svg>
                  Download CSV Template
                </button>
              </div>

              <flux:field>
                  <flux:label>CSV File</flux:label>
                  <input type="file" wire:model="importFile" accept=".csv" class="block w-full text-sm text-ink/70 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-teal/10 file:text-teal hover:file:bg-teal/20" />
                  <flux:error name="importFile" />
              </flux:field>
              
              <div wire:loading wire:target="importFile" class="text-xs text-teal font-medium">Uploading...</div>
              <div wire:loading wire:target="importCsv" class="text-xs text-teal font-medium">Processing CSV...</div>
          </div>
          
          <div class="flex items-center justify-end gap-3 px-6 py-4 border-t border-ink/10">
              <flux:modal.close>
                <button type="button" class="text-sm font-medium text-ink/60 hover:text-ink px-4 py-2">Cancel</button>
              </flux:modal.close>
              <button type="submit" class="text-sm font-semibold bg-teal text-paper rounded-lg px-5 py-2.5 hover:bg-teal/90 transition">
                  Import Questions
              </button>
          </div>
      </form>
  </flux:modal>
</div>