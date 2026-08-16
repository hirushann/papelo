<div>
  <style>
    .option-row { display:flex; align-items:center; gap:1rem; border:1.5px solid rgba(34,49,74,0.14); border-radius:0.75rem; padding:1rem 1.25rem; cursor:pointer; transition: all .15s; }
    .option-row:hover { border-color:rgba(63,125,107,0.4); }
    .option-row.selected { border-color:#3F7D6B; background:rgba(63,125,107,0.06); }
    .option-bubble { width:26px; height:26px; border-radius:50%; border:2px solid rgba(34,49,74,0.25); flex-shrink:0; }
    .option-row.selected .option-bubble { border-color:#3F7D6B; background:#3F7D6B; }

    .qnav-btn { width:34px; height:34px; border-radius:0.5rem; font-size:0.75rem; font-weight:600; display:flex; align-items:center; justify-content:center; position:relative; }
    .qnav-btn.answered { background:#3F7D6B; color:#F5F1E6; }
    .qnav-btn.unanswered { background:#fff; color:rgba(34,49,74,0.5); border:1px solid rgba(34,49,74,0.15); }
    .qnav-btn.current { outline:2px solid #22314A; outline-offset:2px; }
    .qnav-btn.flagged::after { content:''; position:absolute; top:-3px; right:-3px; width:9px; height:9px; border-radius:50%; background:#C79A46; border:1.5px solid #F5F1E6; }
  </style>

  @php
    $currentQuestion = $questions[$currentQuestionIndex] ?? null;
    $answeredCount = count($answers);
    $unansweredCount = $questions->count() - $answeredCount;
    $flaggedCount = count($flagged);
  @endphp

  @if($showPreviewResults)
  <div class="bg-examsheet-quiet min-h-screen">
    <!-- MINIMAL HEADER -->
    <header class="px-6 h-16 flex items-center justify-between max-w-3xl mx-auto w-full">
      <a href="{{ route('dashboard') }}" wire:navigate class="flex items-center gap-2">
        <svg viewBox="636 340 1124 1112" class="w-6 h-6"><g transform="translate(0,1792) scale(0.1,-0.1)" fill="#3F7D6B" stroke="none"><path d="M10915 12730 c-134 -31 -324 -89 -350 -106 -37 -24 -69 -97 -62 -145 11 -83 70 -132 152 -126 48 3 314 72 402 103 112 40 125 193 23 265 -38 27 -78 29 -165 9z"/><path d="M9963 12361 c-527 -265 -1098 -815 -1443 -1391 -197 -327 -251 -478 -200 -558 27 -44 97 -75 145 -66 70 13 93 40 176 201 350 685 781 1145 1434 1531 134 79 155 103 155 177 0 62 -31 112 -84 136 -59 27 -73 24 -183 -30z"/><path d="M7926 9653 c-32 -111 -66 -499 -66 -748 0 -2274 1914 -4118 4157 -4005 1243 63 2383 718 3087 1775 159 238 363 620 382 713 l7 32 -306 0 -305 0 -11 -44 c-13 -51 -53 -90 -109 -105 -34 -10 -651 -18 -2872 -37 l-725 -7 -485 -163 c-453 -153 -520 -174 -520 -158 0 7 53 190 250 864 79 267 166 566 195 665 29 99 121 410 204 692 83 281 151 519 151 527 0 26 -3026 24 -3034 -1z m2020 -1544 c31 -15 48 -32 63 -63 38 -76 23 -132 -59 -223 -131 -148 -201 -387 -170 -586 61 -402 382 -680 785 -681 185 0 339 54 476 167 84 70 98 77 156 77 123 0 195 -139 124 -239 -57 -80 -289 -221 -441 -269 -154 -49 -366 -62 -515 -33 -654 128 -1049 793 -841 1415 81 240 248 448 365 455 8 1 34 -8 57 -20z"/></g><g transform="translate(0,1792) scale(0.1,-0.1)" fill="#22314A" stroke="none"><path d="M14353 13763 c-81 -86 -283 -296 -448 -467 -165 -171 -399 -415 -520 -541 -121 -126 -328 -342 -460 -480 -277 -288 -1054 -1102 -1166 -1220 l-76 -81 -41 -129 c-22 -72 -68 -222 -102 -335 -60 -197 -89 -296 -235 -790 -37 -124 -100 -337 -141 -475 -438 -1461 -559 -1873 -551 -1881 4 -4 608 198 1707 571 914 310 1521 516 1679 569 l184 61 386 405 c212 223 501 524 641 670 262 272 1644 1721 1738 1821 28 31 52 61 52 66 0 6 -165 170 -367 365 -433 417 -469 451 -481 446 -6 -1 -180 -181 -388 -398 -208 -217 -594 -620 -859 -895 -264 -275 -633 -660 -820 -855 -488 -511 -605 -630 -616 -630 -17 0 -690 663 -687 677 2 7 190 207 418 445 229 238 683 712 1010 1053 327 341 736 768 910 948 173 181 317 336 318 344 4 17 -174 203 -194 203 -6 0 -37 -28 -70 -62 -70 -75 -641 -670 -1014 -1058 -146 -151 -521 -543 -835 -870 -724 -757 -757 -790 -770 -790 -15 0 -405 379 -405 394 0 6 86 101 192 211 106 110 359 373 563 585 204 212 523 545 710 740 186 195 402 420 480 500 286 295 715 749 715 757 0 16 -275 283 -292 283 -9 0 -83 -71 -165 -157z m-1982 -3565 c1075 -1041 1370 -1331 1367 -1345 -2 -12 -1034 -381 -1315 -470 l-52 -16 -338 329 c-186 181 -371 361 -412 400 l-73 70 22 75 c71 243 380 1269 389 1292 6 15 17 27 25 27 7 0 182 -163 387 -362z m-790 -1470 c60 -56 109 -105 109 -110 0 -9 -23 -39 -298 -395 -140 -183 -192 -239 -192 -205 0 4 13 50 29 102 228 749 215 710 232 710 6 0 60 -46 120 -102z"/></g></svg>
        <span class="font-display text-lg text-ink">Papelooo</span>
      </a>
      <a href="{{ route('papers') }}" wire:navigate class="text-sm font-medium text-ink/60 hover:text-ink">Back to papers &rarr;</a>
    </header>

    <main class="max-w-3xl mx-auto px-6 py-10">
      <div class="text-center mb-10">
        <p class="text-xs font-semibold uppercase tracking-wide text-ink/40 mb-2">{{ $paper->title }} — Preview Results</p>

        <!-- SCORE RING -->
        @php
            $circumference = 553;
            $dashOffset = $circumference - ($previewScore / 100) * $circumference;
        @endphp
        <div class="relative w-52 h-52 mx-auto mb-6">
          <svg viewBox="0 0 200 200" class="w-full h-full -rotate-90">
            <circle cx="100" cy="100" r="88" fill="none" stroke="rgba(34,49,74,0.08)" stroke-width="14"/>
            <circle cx="100" cy="100" r="88" fill="none" stroke="#3F7D6B" stroke-width="14" stroke-linecap="round"
              stroke-dasharray="553" stroke-dashoffset="{{ $dashOffset }}"/>
          </svg>
          <div class="absolute inset-0 flex flex-col items-center justify-center">
            <span class="font-display text-5xl text-ink">{{ $previewScore }}%</span>
            <span class="text-xs text-ink/50 mt-1">{{ $previewCorrect }} / {{ $questions->count() }} correct</span>
          </div>
        </div>

        <p class="text-sm text-ink/50 mb-6">You've completed the free preview questions.</p>
      </div>

      <!-- STAT CHIPS -->
      <div class="grid grid-cols-3 gap-4 mb-8">
        <div class="bg-white rounded-xl border border-ink/10 p-4 text-center">
          <p class="font-display text-2xl text-teal">{{ $previewCorrect }}</p>
          <p class="text-xs text-ink/50 mt-1">Correct</p>
        </div>
        <div class="bg-white rounded-xl border border-ink/10 p-4 text-center">
          <p class="font-display text-2xl text-margin">{{ $previewIncorrect }}</p>
          <p class="text-xs text-ink/50 mt-1">Incorrect</p>
        </div>
        <div class="bg-white rounded-xl border border-ink/10 p-4 text-center">
          <p class="font-display text-2xl text-ink/40">{{ $previewUnanswered }}</p>
          <p class="text-xs text-ink/50 mt-1">Unanswered</p>
        </div>
      </div>

      <!-- UPSELL BOX -->
      <div class="bg-white rounded-2xl border-2 border-teal p-8 text-center shadow-lg mb-8">
        <div class="w-16 h-16 rounded-full bg-teal/10 flex items-center justify-center mx-auto mb-5">
          <svg class="w-8 h-8 text-teal" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
        </div>
        <h2 class="font-display text-2xl text-ink mb-2">Want the full picture?</h2>
        <p class="text-sm text-ink/60 mb-6 max-w-md mx-auto">Purchase the full paper to unlock all {{ $allQuestionsCount }} questions, get timed exam conditions, and see a detailed topic-by-topic breakdown of your weak areas.</p>
        <a href="{{ route('subscribe') }}" wire:navigate class="block w-full max-w-sm mx-auto rounded-lg bg-teal text-paper font-semibold py-3.5 hover:bg-teal/90 transition">
          Subscribe to unlock full paper
        </a>
      </div>
    </main>
  </div>
  @else
  <!-- MINIMAL EXAM HEADER -->
  <header class="sticky top-0 z-50 bg-white border-b border-ink/10">
    <div class="max-w-6xl mx-auto px-6 h-16 flex items-center justify-between">
      <div class="flex items-center gap-3">
        <svg viewBox="636 340 1124 1112" class="w-6 h-6 flex-shrink-0"><g transform="translate(0,1792) scale(0.1,-0.1)" fill="#3F7D6B" stroke="none"><path d="M10915 12730 c-134 -31 -324 -89 -350 -106 -37 -24 -69 -97 -62 -145 11 -83 70 -132 152 -126 48 3 314 72 402 103 112 40 125 193 23 265 -38 27 -78 29 -165 9z"/><path d="M9963 12361 c-527 -265 -1098 -815 -1443 -1391 -197 -327 -251 -478 -200 -558 27 -44 97 -75 145 -66 70 13 93 40 176 201 350 685 781 1145 1434 1531 134 79 155 103 155 177 0 62 -31 112 -84 136 -59 27 -73 24 -183 -30z"/><path d="M7926 9653 c-32 -111 -66 -499 -66 -748 0 -2274 1914 -4118 4157 -4005 1243 63 2383 718 3087 1775 159 238 363 620 382 713 l7 32 -306 0 -305 0 -11 -44 c-13 -51 -53 -90 -109 -105 -34 -10 -651 -18 -2872 -37 l-725 -7 -485 -163 c-453 -153 -520 -174 -520 -158 0 7 53 190 250 864 79 267 166 566 195 665 29 99 121 410 204 692 83 281 151 519 151 527 0 26 -3026 24 -3034 -1z m2020 -1544 c31 -15 48 -32 63 -63 38 -76 23 -132 -59 -223 -131 -148 -201 -387 -170 -586 61 -402 382 -680 785 -681 185 0 339 54 476 167 84 70 98 77 156 77 123 0 195 -139 124 -239 -57 -80 -289 -221 -441 -269 -154 -49 -366 -62 -515 -33 -654 128 -1049 793 -841 1415 81 240 248 448 365 455 8 1 34 -8 57 -20z"/></g><g transform="translate(0,1792) scale(0.1,-0.1)" fill="#22314A" stroke="none"><path d="M14353 13763 c-81 -86 -283 -296 -448 -467 -165 -171 -399 -415 -520 -541 -121 -126 -328 -342 -460 -480 -277 -288 -1054 -1102 -1166 -1220 l-76 -81 -41 -129 c-22 -72 -68 -222 -102 -335 -60 -197 -89 -296 -235 -790 -37 -124 -100 -337 -141 -475 -438 -1461 -559 -1873 -551 -1881 4 -4 608 198 1707 571 914 310 1521 516 1679 569 l184 61 386 405 c212 223 501 524 641 670 262 272 1644 1721 1738 1821 28 31 52 61 52 66 0 6 -165 170 -367 365 -433 417 -469 451 -481 446 -6 -1 -180 -181 -388 -398 -208 -217 -594 -620 -859 -895 -264 -275 -633 -660 -820 -855 -488 -511 -605 -630 -616 -630 -17 0 -690 663 -687 677 2 7 190 207 418 445 229 238 683 712 1010 1053 327 341 736 768 910 948 173 181 317 336 318 344 4 17 -174 203 -194 203 -6 0 -37 -28 -70 -62 -70 -75 -641 -670 -1014 -1058 -146 -151 -521 -543 -835 -870 -724 -757 -757 -790 -770 -790 -15 0 -405 379 -405 394 0 6 86 101 192 211 106 110 359 373 563 585 204 212 523 545 710 740 186 195 402 420 480 500 286 295 715 749 715 757 0 16 -275 283 -292 283 -9 0 -83 -71 -165 -157z m-1982 -3565 c1075 -1041 1370 -1331 1367 -1345 -2 -12 -1034 -381 -1315 -470 l-52 -16 -338 329 c-186 181 -371 361 -412 400 l-73 70 22 75 c71 243 380 1269 389 1292 6 15 17 27 25 27 7 0 182 -163 387 -362z m-790 -1470 c60 -56 109 -105 109 -110 0 -9 -23 -39 -298 -395 -140 -183 -192 -239 -192 -205 0 4 13 50 29 102 228 749 215 710 232 710 6 0 60 -46 120 -102z"/></g></svg>
        <div>
          <div class="flex items-center gap-2">
            <p class="text-sm font-semibold text-ink leading-tight">{{ $paper->title }}</p>
            @if($isPreview)
              <span class="text-[10px] font-semibold uppercase text-gold bg-gold/10 rounded-full px-2 py-0.5">Preview</span>
            @endif
          </div>
          <p class="text-xs text-ink/40 leading-tight">Question {{ $currentQuestionIndex + 1 }} of {{ $questions->count() }}@if($isPreview) <span class="text-gold">({{ $allQuestionsCount }} total)</span>@endif</p>
        </div>
      </div>

      @if(!$isPreview && $attempt)
      <!-- TIMER (Alpine.js) — only in full mode -->
      @php
          $endTimeMs = $attempt->started_at->addMinutes($paper->duration_minutes)->timestamp * 1000;
      @endphp
      <div 
        x-data="{ 
            endTime: {{ $endTimeMs }},
            timeLeft: '00:00',
            init() {
                this.updateTimer();
                setInterval(() => { this.updateTimer(); }, 1000);
            },
            updateTimer() {
                const now = new Date().getTime();
                const distance = this.endTime - now;
                
                if (distance <= 0) {
                    this.timeLeft = '00:00';
                    $wire.submit(); // Auto submit
                    return;
                }
                
                const m = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                const s = Math.floor((distance % (1000 * 60)) / 1000);
                this.timeLeft = (m < 10 ? '0' + m : m) + ':' + (s < 10 ? '0' + s : s);
            }
        }" 
        class="flex items-center gap-2 bg-paper rounded-lg px-4 py-2 border border-ink/10"
      >
        <svg class="w-4 h-4 text-ink/50" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 3"/></svg>
        <span x-text="timeLeft" class="font-display text-lg text-ink tabular-nums"></span>
      </div>
      @else
      <div class="flex items-center gap-2 bg-gold/10 rounded-lg px-4 py-2 border border-gold/20">
        <svg class="w-4 h-4 text-gold" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 3"/></svg>
        <span class="text-sm font-medium text-gold">Free preview</span>
      </div>
      @endif

      <button wire:click="saveAndExit" class="text-sm font-medium text-ink/60 hover:text-margin">{{ $isPreview ? 'Exit' : 'Save & Exit' }}</button>
    </div>
    <div class="h-1 bg-ink/5"><div class="h-1 bg-teal" style="width:{{ ($answeredCount / max(1, $questions->count())) * 100 }}%"></div></div>
  </header>

  <!-- MAIN -->
  <div class="max-w-6xl mx-auto px-6 py-10 grid lg:grid-cols-[1fr_260px] gap-10">

    <!-- QUESTION -->
    @if($currentQuestion)
    <div>
      <div class="flex items-center justify-between mb-6">
        <span class="text-xs font-semibold text-ink/40 uppercase tracking-wide">Question {{ $currentQuestionIndex + 1 }}</span>
        
        @if(!$isPreview)
        <button wire:click="toggleFlag({{ $currentQuestion->id }})" class="flex items-center gap-1.5 text-xs font-semibold {{ isset($flagged[$currentQuestion->id]) ? 'text-paper bg-gold' : 'text-gold hover:bg-gold/10' }} rounded-full px-3 py-1.5 transition">
          <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M4 15s1-1 4-1 5 2 8 2 4-1 4-1V3s-1 1-4 1-5-2-8-2-4 1-4 1z"/><path d="M4 22V3"/></svg>
          {{ isset($flagged[$currentQuestion->id]) ? 'Flagged' : 'Flag for review' }}
        </button>
        @endif
      </div>

      <h1 class="font-display text-2xl text-ink mb-8 leading-snug">{{ $currentQuestion->question_text }}</h1>
      
      @if($currentQuestion->image_path)
        <div class="mb-8">
            <img src="{{ Storage::url($currentQuestion->image_path) }}" alt="Question Image" class="max-w-full h-auto rounded-xl border border-ink/10">
        </div>
      @endif

      @if($currentQuestion->type === 'structured')
          @if(Auth::user()->hasFeature('structured_papers'))
              <div class="bg-teal/10 text-teal-900 p-5 rounded-xl border border-teal/20 mb-6">
                <p class="font-semibold mb-2">Structured Question</p>
                <p class="text-sm">Please write your answer on a piece of paper. The model solution will be provided during the review phase.</p>
              </div>
          @else
              <div class="bg-gold/10 text-gold-900 p-6 rounded-xl border border-gold/30 text-center mb-6">
                <svg class="w-10 h-10 text-gold mx-auto mb-3" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z" /></svg>
                <h3 class="font-display text-lg text-ink mb-2">Structured Questions Locked</h3>
                <p class="text-sm text-ink/70 mb-4 max-w-sm mx-auto">This is a structured question. Upgrade to the Pass tier to unlock self-marked structured questions and model solutions.</p>
                <a href="{{ route('subscribe') }}" class="inline-block bg-gold text-paper font-semibold rounded-lg px-6 py-2.5 hover:bg-gold/90 transition">Upgrade to Pass</a>
              </div>
          @endif
      @else
          <div class="space-y-3">
            @foreach($currentQuestion->options as $option)
            @php
                $isSelected = isset($answers[$currentQuestion->id]) && $answers[$currentQuestion->id] == $option->id;
            @endphp
            <div wire:click="selectOption({{ $currentQuestion->id }}, {{ $option->id }})" class="option-row {{ $isSelected ? 'selected' : '' }}">
              <span class="option-bubble"></span>
              <span class="{{ $isSelected ? 'text-ink font-medium' : 'text-ink/80' }}">{{ $option->option_text }}</span>
            </div>
            @endforeach
          </div>
      @endif

      <div class="flex items-center justify-between mt-10 pt-6 border-t border-ink/10">
        <button wire:click="prevQuestion" @if($currentQuestionIndex == 0) disabled @endif class="inline-flex items-center gap-1.5 text-sm font-semibold text-ink/70 border border-ink/15 rounded-lg px-5 py-2.5 hover:border-ink/30 disabled:opacity-50 disabled:cursor-not-allowed">
          <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M15 18l-6-6 6-6"/></svg>
          Previous
        </button>
        @if($currentQuestionIndex == $questions->count() - 1 && $isPreview)
          <button wire:click="nextQuestion" class="inline-flex items-center gap-1.5 text-sm font-semibold bg-gold text-paper rounded-lg px-5 py-2.5 hover:bg-gold/90 transition">
            See full paper
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M9 18l6-6-6-6"/></svg>
          </button>
        @else
          <button wire:click="nextQuestion" @if($currentQuestionIndex == $questions->count() - 1) disabled @endif class="inline-flex items-center gap-1.5 text-sm font-semibold bg-teal text-paper rounded-lg px-5 py-2.5 hover:bg-teal/90 transition disabled:opacity-50 disabled:cursor-not-allowed">
            Next
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M9 18l6-6-6-6"/></svg>
          </button>
        @endif
      </div>
    </div>
    @else
    <div>
      <p>No question available.</p>
    </div>
    @endif

    <!-- NAVIGATOR -->
    <aside>
      <div class="sticky top-24 bg-white rounded-2xl border border-ink/10 p-5">
        <h2 class="text-xs font-semibold text-ink/50 uppercase tracking-wide mb-4">Questions</h2>
        <div class="grid grid-cols-5 gap-2 mb-5">
          @foreach($questions as $index => $q)
          @php
            $isAnswered = isset($answers[$q->id]);
            $isFlagged = isset($flagged[$q->id]);
            $isCurrent = $currentQuestionIndex == $index;
            
            $classes = 'qnav-btn ';
            $classes .= $isAnswered ? 'answered ' : 'unanswered ';
            if ($isFlagged) $classes .= 'flagged ';
            if ($isCurrent) $classes .= 'current ';
          @endphp
          <button wire:click="goToQuestion({{ $index }})" class="{{ $classes }}">{{ $index + 1 }}</button>
          @endforeach
        </div>

        <div class="space-y-1.5 text-xs text-ink/60 mb-5 border-t border-ink/10 pt-4">
          <div class="flex items-center gap-2"><span class="w-2.5 h-2.5 rounded-sm bg-teal"></span> Answered — {{ $answeredCount }}</div>
          <div class="flex items-center gap-2"><span class="w-2.5 h-2.5 rounded-sm border border-ink/20 bg-white"></span> Unanswered — {{ $unansweredCount }}</div>
          @if(!$isPreview)
          <div class="flex items-center gap-2"><span class="w-2.5 h-2.5 rounded-full bg-gold"></span> Flagged — {{ $flaggedCount }}</div>
          @endif
        </div>

        @if($isPreview)
          <a href="{{ route('subscribe') }}" wire:navigate class="block w-full text-center text-sm font-semibold bg-teal text-paper rounded-lg py-3 hover:bg-teal/90 transition">Subscribe to unlock</a>
          <p class="text-[11px] text-ink/40 text-center mt-2">{{ $allQuestionsCount - $previewLimit }} more questions in full paper</p>
        @else
          <button wire:click="submit" class="w-full text-sm font-semibold bg-ink text-paper rounded-lg py-3 hover:bg-ink/90 transition">Submit paper</button>
          @if($unansweredCount > 0)
          <p class="text-[11px] text-ink/40 text-center mt-2">{{ $unansweredCount }} questions still unanswered</p>
          @else
          <p class="text-[11px] text-teal text-center mt-2 font-medium">All questions answered!</p>
          @endif
        @endif
      </div>
    </aside>
  </div>
  @endif
</div>
