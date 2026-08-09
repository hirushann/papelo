<div>
  <x-slot name="headerActions">
    <div class="flex items-center gap-5">
      <div class="relative hidden sm:block">
        <input type="text" wire:model.live.debounce.300ms="search" placeholder="Search papers…" class="w-64 text-sm rounded-lg border border-ink/15 pl-9 pr-3 py-2 focus:outline-none focus:ring-2 focus:ring-teal/30 focus:border-teal">
        <svg class="w-4 h-4 absolute left-3 top-2.5 text-ink/40" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="11" cy="11" r="7"/><path d="m21 21-4.35-4.35"/></svg>
      </div>
      <flux:modal.trigger name="create-paper">
        <button class="inline-flex items-center gap-1.5 rounded-lg bg-teal text-paper text-sm font-semibold px-4 py-2 hover:bg-teal/90 transition">
          <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M12 5v14M5 12h14"/></svg>
          Add Paper
        </button>
      </flux:modal.trigger>
    </div>
  </x-slot>

  @if(session('success'))
    <div class="mb-5 rounded-lg bg-teal/10 border border-teal/20 p-4 flex items-start gap-3">
      <svg class="w-5 h-5 text-teal shrink-0 mt-0.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M20 6 9 17l-5-5"/></svg>
      <p class="text-sm font-medium text-teal">{{ session('success') }}</p>
    </div>
  @endif

  <div class="space-y-5">
    <!-- TOOLBAR -->
    <div class="flex flex-wrap items-center justify-between gap-3">
      <div class="flex items-center gap-2">
        <button wire:click="setLevelFilter('')" class="text-xs {{ $levelFilter === '' ? 'font-semibold bg-ink text-paper' : 'font-medium border border-ink/15 text-ink/60 hover:border-teal/40' }} rounded-full px-3.5 py-1.5 transition">All levels</button>
        <button wire:click="setLevelFilter('scholarship')" class="text-xs {{ $levelFilter === 'scholarship' ? 'font-semibold bg-ink text-paper' : 'font-medium border border-ink/15 text-ink/60 hover:border-teal/40' }} rounded-full px-3.5 py-1.5 transition">Grade 5</button>
        <button wire:click="setLevelFilter('ol')" class="text-xs {{ $levelFilter === 'ol' ? 'font-semibold bg-ink text-paper' : 'font-medium border border-ink/15 text-ink/60 hover:border-teal/40' }} rounded-full px-3.5 py-1.5 transition">O/L</button>
        <button wire:click="setLevelFilter('al')" class="text-xs {{ $levelFilter === 'al' ? 'font-semibold bg-ink text-paper' : 'font-medium border border-ink/15 text-ink/60 hover:border-teal/40' }} rounded-full px-3.5 py-1.5 transition">A/L</button>
      </div>
      <div class="flex items-center gap-2">
        <button wire:click="exportCsv" class="inline-flex items-center gap-1.5 rounded-lg border border-ink/20 bg-white text-ink text-xs font-semibold px-3 py-1.5 hover:bg-ink/5 transition">
          <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" x2="12" y1="15" y2="3"/></svg>
          Export
        </button>
        <flux:modal.trigger name="import-paper">
          <button class="inline-flex items-center gap-1.5 rounded-lg border border-ink/20 bg-white text-ink text-xs font-semibold px-3 py-1.5 hover:bg-ink/5 transition">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" x2="12" y1="3" y2="15"/></svg>
            Import
          </button>
        </flux:modal.trigger>
        <select wire:model.live="statusFilter" class="text-sm border border-ink/15 rounded-lg px-3 py-1.5 bg-white text-ink/70">
          <option value="">All statuses</option>
          <option value="Published">Published</option>
          <option value="Draft">Draft</option>
        </select>
      </div>
    </div>

    <!-- TABLE -->
    <div class="bg-white rounded-2xl border border-ink/10 overflow-hidden">
      <table class="w-full text-sm">
        <thead>
          <tr class="text-left text-xs text-ink/40 border-b border-ink/10">
            <th class="font-medium px-6 py-3">Paper</th>
            <th class="font-medium px-6 py-3">Level</th>
            <th class="font-medium px-6 py-3">Medium</th>
            <th class="font-medium px-6 py-3">Questions</th>
            <th class="font-medium px-6 py-3">Price</th>
            <th class="font-medium px-6 py-3">Attempts</th>
            <th class="font-medium px-6 py-3">Status</th>
            <th class="font-medium px-6 py-3 text-right">Actions</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-ink/5">
          @forelse($papers as $paper)
            <tr class="hover:bg-paper/40">
              <td class="px-6 py-3.5"><p class="font-medium text-ink">{{ $paper->title }}</p><p class="text-xs text-ink/40">{{ $paper->year }}</p></td>
              <td class="px-6 py-3.5">
                @if($paper->subject->level === 'ol')
                  <span class="text-[11px] font-semibold uppercase text-teal bg-teal/10 rounded-full px-2.5 py-0.5">O/L</span>
                @elseif($paper->subject->level === 'al')
                  <span class="text-[11px] font-semibold uppercase text-margin bg-margin/10 rounded-full px-2.5 py-0.5">A/L</span>
                @elseif($paper->subject->level === 'scholarship')
                  <span class="text-[11px] font-semibold uppercase text-gold bg-gold/10 rounded-full px-2.5 py-0.5">Grade 5</span>
                @else
                  <span class="text-[11px] font-semibold uppercase text-ink/60 bg-ink/10 rounded-full px-2.5 py-0.5">{{ $paper->subject->level }}</span>
                @endif
              </td>
              <td class="px-6 py-3.5 capitalize text-ink/60">{{ $paper->subject->medium }}</td>
              <td class="px-6 py-3.5 text-ink/60">{{ $paper->questions_count }}</td>
              <td class="px-6 py-3.5 text-ink/60">Rs. {{ number_format($paper->price, 0) }}</td>
              <td class="px-6 py-3.5 text-ink/60">{{ $paper->attempts->count() }}</td>
              <td class="px-6 py-3.5">
                @if($paper->is_published)
                  <span class="text-[11px] font-semibold text-teal bg-teal/10 rounded-full px-2.5 py-0.5">Published</span>
                @else
                  <span class="text-[11px] font-semibold text-ink/50 bg-ink/5 rounded-full px-2.5 py-0.5">Draft</span>
                @endif
              </td>
              <td class="px-6 py-3.5">
                <div class="flex items-center justify-end gap-3 text-ink/40">
                  <a href="{{ route('admin.questions', ['paper_id' => $paper->id]) }}" class="hover:text-teal" title="Manage Questions">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 20h9"/><path d="M16.5 3.5a2.1 2.1 0 0 1 3 3L7 19l-4 1 1-4Z"/></svg>
                  </a>
                  <button class="hover:text-margin" title="Delete Paper"><svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M3 6h18"/><path d="M8 6V4a1 1 0 0 1 1-1h6a1 1 0 0 1 1 1v2m3 0-1 14a1 1 0 0 1-1 1H7a1 1 0 0 1-1-1L5 6"/></svg></button>
                </div>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="8" class="px-6 py-8 text-center text-ink/50 text-sm">
                No papers found matching your criteria.
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>
      <div class="px-6 py-4 border-t border-ink/10">
        {{ $papers->links() }}
      </div>
    </div>
  </div>

  <!-- CREATE MODAL USING FLUX -->
  <flux:modal name="create-paper" class="md:w-full md:max-w-lg">
      <form wire:submit="savePaper">
          <div class="flex items-center justify-between px-6 py-5 border-b border-ink/10">
              <h2 class="font-display text-lg text-ink">Add New Paper</h2>
              <flux:modal.close>
                <button type="button" class="text-ink/40 hover:text-ink text-xl leading-none">&times;</button>
              </flux:modal.close>
          </div>
          <div class="p-6 space-y-4 max-h-[70vh] overflow-y-auto">
              <div class="grid grid-cols-3 gap-4">
                  <div>
                      <flux:field>
                          <flux:label>Exam level</flux:label>
                          <flux:select wire:model="newLevel">
                              <option value="scholarship">Grade 5 Scholarship</option>
                              <option value="ol">O/L</option>
                              <option value="al">A/L</option>
                          </flux:select>
                          <flux:error name="newLevel" />
                      </flux:field>
                  </div>
                  <div>
                      <flux:field>
                          <flux:label>Medium</flux:label>
                          <flux:select wire:model="newMedium">
                              <option value="english">English</option>
                              <option value="sinhala">Sinhala</option>
                              <option value="tamil">Tamil</option>
                          </flux:select>
                          <flux:error name="newMedium" />
                      </flux:field>
                  </div>
                  <div>
                      <flux:field>
                          <flux:label>Year</flux:label>
                          <flux:input type="number" wire:model="newYear" min="2000" max="2100" />
                          <flux:error name="newYear" />
                      </flux:field>
                  </div>
              </div>
              
              <flux:field>
                  <flux:label>Subject</flux:label>
                  <flux:input wire:model="newSubject" placeholder="e.g. Science" />
                  <flux:error name="newSubject" />
              </flux:field>

              <div class="grid grid-cols-2 gap-4">
                  <flux:field>
                      <flux:label>Duration (minutes)</flux:label>
                      <flux:input type="number" wire:model="newDuration" min="1" />
                      <flux:error name="newDuration" />
                  </flux:field>
                  <flux:field>
                      <flux:label>Price (Rs.)</flux:label>
                      <flux:input type="number" wire:model="newPrice" min="0" />
                      <flux:error name="newPrice" />
                  </flux:field>
              </div>

              <flux:field>
                  <flux:label>Topics covered</flux:label>
                  <flux:input wire:model="newTitle" placeholder="Cells & Genetics, Forces & Motion, …" />
                  <flux:description>Leave blank to automatically use Subject and Year</flux:description>
                  <flux:error name="newTitle" />
              </flux:field>

              <flux:field>
                  <flux:label>Status</flux:label>
                  <flux:select wire:model="newStatus">
                      <option value="Draft">Draft</option>
                      <option value="Published">Published</option>
                  </flux:select>
                  <flux:error name="newStatus" />
              </flux:field>
              
              <div class="rounded-lg border-2 border-dashed border-ink/15 text-center py-6 text-sm text-ink/40">
                  Questions are added after the paper is created &rarr;<br><span class="text-ink/30">goes to the Question editor</span>
              </div>
          </div>
          
          <div class="flex items-center justify-end gap-3 px-6 py-4 border-t border-ink/10">
              <flux:modal.close>
                <button type="button" class="text-sm font-medium text-ink/60 hover:text-ink px-4 py-2">Cancel</button>
              </flux:modal.close>
              <button type="submit" class="text-sm font-semibold bg-teal text-paper rounded-lg px-5 py-2.5 hover:bg-teal/90 transition">
                  Create &amp; add questions
              </button>
          </div>
      </form>
  </flux:modal>

  <!-- IMPORT MODAL USING FLUX -->
  <flux:modal name="import-paper" class="md:w-full md:max-w-md">
      <form wire:submit="importCsv">
          <div class="flex items-center justify-between px-6 py-5 border-b border-ink/10">
              <h2 class="font-display text-lg text-ink">Import Papers via CSV</h2>
              <flux:modal.close>
                <button type="button" class="text-ink/40 hover:text-ink text-xl leading-none">&times;</button>
              </flux:modal.close>
          </div>
          <div class="p-6 space-y-5">
              @if(session('success'))
                <div class="rounded-lg bg-teal/10 border border-teal/20 p-4 flex items-start gap-3">
                  <svg class="w-5 h-5 text-teal shrink-0 mt-0.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M20 6 9 17l-5-5"/></svg>
                  <p class="text-sm font-medium text-teal">{{ session('success') }}</p>
                </div>
              @endif

              <div class="text-sm text-ink/70">
                <p class="mb-3">Upload a CSV file to bulk import past papers. If a subject doesn't exist, it will be automatically created.</p>
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
                  Import Papers
              </button>
          </div>
      </form>
  </flux:modal>
</div>
