<div>
  <style>
    .opt { display:flex; align-items:center; gap:0.85rem; border:1.5px solid rgba(34,49,74,0.12); border-radius:0.65rem; padding:0.75rem 1rem; font-size:0.9rem; }
    .opt.correct { border-color:#3F7D6B; background:rgba(63,125,107,0.07); color:#22314A; font-weight:600; }
    .opt.incorrect { border-color:#B5514A; background:rgba(181,81,74,0.06); color:#22314A; }
    .opt-icon { width:22px; height:22px; border-radius:50%; flex-shrink:0; display:flex; align-items:center; justify-content:center; }
    .opt.correct .opt-icon { background:#3F7D6B; }
    .opt.incorrect .opt-icon { background:#B5514A; }
    .opt.plain .opt-icon { border:2px solid rgba(34,49,74,0.2); }
    .jump-btn { width:30px; height:30px; border-radius:0.4rem; font-size:0.7rem; font-weight:600; display:flex; align-items:center; justify-content:center; flex-shrink:0; }
    .tab-btn { font-size:0.8rem; font-weight:600; padding:0.4rem 0.9rem; border-radius:9999px; color:rgba(34,49,74,0.5); cursor:pointer; }
    .tab-btn.active { background:#22314A; color:#F5F1E6; }
  </style>

  <!-- HEADER -->
  <header class="sticky top-0 z-50 bg-white border-b border-ink/10">
    <div class="max-w-3xl mx-auto px-6 h-16 flex items-center justify-between">
      <div>
        <p class="text-[11px] text-ink/40"><a href="{{ route('result.summary', $attempt->id) }}" wire:navigate class="hover:text-teal">Results</a> / Review</p>
        <h1 class="font-display text-lg text-ink -mt-0.5">{{ $attempt->paper->title }}</h1>
      </div>
      <span class="text-sm font-semibold text-ink/70">{{ $attempt->score }}% &middot; {{ $stats['correct'] }}/{{ $stats['total'] }} correct</span>
    </div>
  </header>

  <main class="max-w-3xl mx-auto px-6 py-8">

    <!-- FILTER TABS -->
    <div class="flex flex-wrap items-center gap-2 mb-5">
      <button wire:click="setFilter('all')" class="tab-btn {{ $filter === 'all' ? 'active' : '' }}">All {{ $stats['total'] }}</button>
      <button wire:click="setFilter('incorrect')" class="tab-btn {{ $filter === 'incorrect' ? 'active' : '' }}">Incorrect {{ $stats['incorrect'] }}</button>
      <button wire:click="setFilter('unanswered')" class="tab-btn {{ $filter === 'unanswered' ? 'active' : '' }}">Unanswered {{ $stats['unanswered'] }}</button>
      <button wire:click="setFilter('correct')" class="tab-btn {{ $filter === 'correct' ? 'active' : '' }}">Correct {{ $stats['correct'] }}</button>
    </div>

    <!-- QUESTION JUMP STRIP -->
    <div class="bg-white rounded-xl border border-ink/10 p-3 mb-8 overflow-x-auto">
      <div class="flex gap-1.5 w-max">
        @foreach($questionsData as $q)
          @php
            $bg = 'rgba(34,49,74,0.08)'; $color = 'rgba(34,49,74,0.4)'; // unanswered
            if ($q['status'] === 'correct') { $bg = '#3F7D6B'; $color = '#F5F1E6'; }
            if ($q['status'] === 'incorrect') { $bg = '#B5514A'; $color = '#F5F1E6'; }
          @endphp
          <a href="#q{{ $q['index'] }}" class="jump-btn" style="background:{{ $bg }};color:{{ $color }};">{{ $q['index'] }}</a>
        @endforeach
      </div>
    </div>

    <div class="space-y-6">
      @foreach($this->filteredQuestions as $q)
      <div id="q{{ $q['index'] }}" class="bg-white rounded-2xl border border-ink/10 p-6 scroll-mt-24">
        <div class="flex items-center justify-between mb-4">
          <span class="text-xs font-semibold text-ink/40 uppercase tracking-wide">Question {{ $q['index'] }} &middot; {{ $q['topic'] }}</span>
          
          @if($q['status'] === 'correct')
          <span class="inline-flex items-center gap-1 text-xs font-semibold text-teal bg-teal/10 rounded-full px-2.5 py-0.5">
            <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path d="M20 6L9 17l-5-5"/></svg> Correct
          </span>
          @elseif($q['status'] === 'incorrect')
          <span class="inline-flex items-center gap-1 text-xs font-semibold text-margin bg-margin/10 rounded-full px-2.5 py-0.5">
            <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path d="M18 6L6 18M6 6l12 12"/></svg> Incorrect
          </span>
          @else
          <span class="inline-flex items-center gap-1 text-xs font-semibold text-ink/40 bg-ink/5 rounded-full px-2.5 py-0.5">
            <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><circle cx="12" cy="12" r="9"/></svg> Unanswered
          </span>
          @endif
        </div>
        <p class="font-medium text-ink mb-4">{{ $q['text'] }}</p>
        
        <div class="space-y-2 mb-4">
          @foreach($q['options'] as $option)
            @php
              $isCorrect = $option->is_correct;
              $isSelected = $q['selected_option_id'] === $option->id;
              
              $class = 'plain';
              $icon = '';
              $label = '';
              
              if ($isCorrect) {
                  $class = 'correct';
                  $icon = '<svg class="w-3.5 h-3.5 text-paper" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path d="M20 6L9 17l-5-5"/></svg>';
                  $label = $isSelected ? '<span class="text-xs font-normal text-teal ml-auto">Correct answer (Your choice)</span>' : '<span class="text-xs font-normal text-teal ml-auto">Correct answer</span>';
              } elseif ($isSelected && !$isCorrect) {
                  $class = 'incorrect';
                  $icon = '<svg class="w-3.5 h-3.5 text-paper" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path d="M18 6L6 18M6 6l12 12"/></svg>';
                  $label = '<span class="text-xs font-normal text-margin ml-auto">Your answer</span>';
              }
            @endphp
            <div class="opt {{ $class }}">
              <span class="opt-icon">{!! $icon !!}</span> 
              {{ $option->option_text }} 
              {!! $label !!}
            </div>
          @endforeach
        </div>
        
        <div class="bg-paper/70 rounded-lg p-4 text-sm text-ink/70">
          <span class="font-semibold text-ink">Why: </span>(Explanation will be added here once the database schema is updated to support it).
        </div>
      </div>
      @endforeach
    </div>

    <p class="text-center text-xs text-ink/40 mt-8">Showing {{ count($this->filteredQuestions) }} of {{ $stats['total'] }} questions</p>

    <div class="flex flex-col sm:flex-row gap-3 mt-6">
      <a href="{{ route('result.summary', $attempt->id) }}" wire:navigate class="flex-1 text-center rounded-lg border border-ink/15 text-ink font-semibold py-3 hover:border-ink/30 transition">Back to results</a>
      <a href="{{ route('dashboard') }}" class="flex-1 text-center rounded-lg bg-teal text-paper font-semibold py-3 hover:bg-teal/90 transition">Practice another paper</a>
    </div>
  </main>

</div>
