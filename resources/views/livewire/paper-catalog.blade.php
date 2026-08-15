<div class="bg-examsheet-quiet min-h-screen text-ink antialiased">
    <style>
      .bg-examsheet-quiet {
        background-color: #F5F1E6;
        background-image: repeating-linear-gradient(180deg, rgba(34,49,74,0.045) 0px, rgba(34,49,74,0.045) 1px, transparent 1px, transparent 32px);
      }
      .filter-check { display: flex; align-items: center; gap: 0.55rem; font-size: 0.85rem; color: rgba(34,49,74,0.75); cursor: pointer; padding: 0.2rem 0; }
      .filter-check input { accent-color: #3F7D6B; width: 15px; height: 15px; }
      .filter-check:hover { color: #22314A; }
    </style>

    <!-- HEADER -->
    <header class="sticky top-0 z-50 bg-paper/95 backdrop-blur-sm border-b border-ink/10">
      <div class="max-w-6xl mx-auto px-6 h-16 flex items-center justify-between">
        <a href="{{ auth()->check() ? route('dashboard') : url('/') }}" wire:navigate class="flex items-center gap-2">
          <svg viewBox="636 340 1124 1112" class="w-7 h-7"><g transform="translate(0,1792) scale(0.1,-0.1)" fill="#3F7D6B" stroke="none"><path d="M10915 12730 c-134 -31 -324 -89 -350 -106 -37 -24 -69 -97 -62 -145 11 -83 70 -132 152 -126 48 3 314 72 402 103 112 40 125 193 23 265 -38 27 -78 29 -165 9z"/><path d="M9963 12361 c-527 -265 -1098 -815 -1443 -1391 -197 -327 -251 -478 -200 -558 27 -44 97 -75 145 -66 70 13 93 40 176 201 350 685 781 1145 1434 1531 134 79 155 103 155 177 0 62 -31 112 -84 136 -59 27 -73 24 -183 -30z"/><path d="M7926 9653 c-32 -111 -66 -499 -66 -748 0 -2274 1914 -4118 4157 -4005 1243 63 2383 718 3087 1775 159 238 363 620 382 713 l7 32 -306 0 -305 0 -11 -44 c-13 -51 -53 -90 -109 -105 -34 -10 -651 -18 -2872 -37 l-725 -7 -485 -163 c-453 -153 -520 -174 -520 -158 0 7 53 190 250 864 79 267 166 566 195 665 29 99 121 410 204 692 83 281 151 519 151 527 0 26 -3026 24 -3034 -1z m2020 -1544 c31 -15 48 -32 63 -63 38 -76 23 -132 -59 -223 -131 -148 -201 -387 -170 -586 61 -402 382 -680 785 -681 185 0 339 54 476 167 84 70 98 77 156 77 123 0 195 -139 124 -239 -57 -80 -289 -221 -441 -269 -154 -49 -366 -62 -515 -33 -654 128 -1049 793 -841 1415 81 240 248 448 365 455 8 1 34 -8 57 -20z"/></g><g transform="translate(0,1792) scale(0.1,-0.1)" fill="#22314A" stroke="none"><path d="M14353 13763 c-81 -86 -283 -296 -448 -467 -165 -171 -399 -415 -520 -541 -121 -126 -328 -342 -460 -480 -277 -288 -1054 -1102 -1166 -1220 l-76 -81 -41 -129 c-22 -72 -68 -222 -102 -335 -60 -197 -89 -296 -235 -790 -37 -124 -100 -337 -141 -475 -438 -1461 -559 -1873 -551 -1881 4 -4 608 198 1707 571 914 310 1521 516 1679 569 l184 61 386 405 c212 223 501 524 641 670 262 272 1644 1721 1738 1821 28 31 52 61 52 66 0 6 -165 170 -367 365 -433 417 -469 451 -481 446 -6 -1 -180 -181 -388 -398 -208 -217 -594 -620 -859 -895 -264 -275 -633 -660 -820 -855 -488 -511 -605 -630 -616 -630 -17 0 -690 663 -687 677 2 7 190 207 418 445 229 238 683 712 1010 1053 327 341 736 768 910 948 173 181 317 336 318 344 4 17 -174 203 -194 203 -6 0 -37 -28 -70 -62 -70 -75 -641 -670 -1014 -1058 -146 -151 -521 -543 -835 -870 -724 -757 -757 -790 -770 -790 -15 0 -405 379 -405 394 0 6 86 101 192 211 106 110 359 373 563 585 204 212 523 545 710 740 186 195 402 420 480 500 286 295 715 749 715 757 0 16 -275 283 -292 283 -9 0 -83 -71 -165 -157z m-1982 -3565 c1075 -1041 1370 -1331 1367 -1345 -2 -12 -1034 -381 -1315 -470 l-52 -16 -338 329 c-186 181 -371 361 -412 400 l-73 70 22 75 c71 243 380 1269 389 1292 6 15 17 27 25 27 7 0 182 -163 387 -362z m-790 -1470 c60 -56 109 -105 109 -110 0 -9 -23 -39 -298 -395 -140 -183 -192 -239 -192 -205 0 4 13 50 29 102 228 749 215 710 232 710 6 0 60 -46 120 -102z"/></g></svg>
          <span class="font-display text-xl text-ink">Papelooo</span>
        </a>
        
        @auth
        <nav class="hidden md:flex items-center gap-8 text-sm font-medium text-ink/80">
          <a href="{{ route('dashboard') }}" wire:navigate class="{{ request()->routeIs('dashboard') ? 'text-teal' : 'hover:text-ink' }}">Dashboard</a>
          <a href="{{ route('papers') }}" wire:navigate class="{{ request()->routeIs('papers') ? 'text-teal' : 'hover:text-ink' }}">Papers</a>
          <a href="{{ route('progress') }}" wire:navigate class="{{ request()->routeIs('progress') ? 'text-teal' : 'hover:text-ink' }}">Progress</a>
        </nav>
        
        <div x-data="{ open: false }" class="relative">
          <button @click="open = !open" @click.outside="open = false" class="flex items-center gap-2.5 hover:bg-ink/5 p-1.5 -m-1.5 rounded-xl transition">
            <div class="w-8 h-8 rounded-full bg-teal flex items-center justify-center text-paper text-xs font-semibold">{{ strtoupper(substr(auth()->user()->name, 0, 2)) }}</div>
            <span class="text-sm font-medium text-ink hidden sm:inline">{{ explode(' ', auth()->user()->name)[0] }}</span>
            <svg class="w-4 h-4 text-ink/40" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M6 9l6 6 6-6"/></svg>
          </button>
          
          <div x-show="open" style="display: none;" class="absolute right-0 top-full mt-2 w-48 bg-white rounded-xl shadow-lg border border-ink/10 py-2 z-50">
            <div class="px-4 py-2 border-b border-ink/10 mb-2">
              <p class="text-xs font-semibold text-ink truncate">{{ auth()->user()->name }}</p>
              <p class="text-xs text-ink/60 truncate">{{ auth()->user()->email }}</p>
            </div>
            @if(auth()->user()->is_admin)
            <a href="{{ route('admin.questions') }}" wire:navigate class="block px-4 py-1.5 text-sm text-ink/80 hover:bg-ink/5 hover:text-ink">Admin Panel</a>
            @endif
            <a href="{{ route('profile') }}" wire:navigate class="block px-4 py-1.5 text-sm text-ink/80 hover:bg-ink/5 hover:text-ink">Profile Settings</a>
            <button wire:click="logout" class="w-full text-left px-4 py-1.5 text-sm text-margin hover:bg-margin/5 transition mt-2 border-t border-ink/10 pt-2">Log out</button>
          </div>
        </div>
        @else
        <div class="flex items-center gap-4">
          <a href="{{ route('login') }}" class="text-sm font-medium text-ink/70 hover:text-ink">Log in</a>
          <a href="{{ route('register') }}" class="text-sm font-medium bg-ink text-paper rounded-lg px-4 py-2 hover:bg-ink/90 transition">Sign up</a>
        </div>
        @endauth
      </div>
    </header>

    <!-- PAGE INTRO -->
    <section>
      <div class="max-w-6xl mx-auto px-6 py-14">
        <h1 class="font-display text-4xl text-ink mb-3">Past Papers</h1>
        <p class="text-ink/60 max-w-xl mb-6">Browse every subject and year — free to preview, no account needed. Sign up when you're ready to attempt one for real.</p>
      </div>
    </section>

    <!-- CATALOG -->
    <section class="max-w-6xl mx-auto px-6 pb-12 grid md:grid-cols-[220px_1fr] gap-10">
      <!-- FILTERS -->
      <aside class="space-y-8">
        <div>
          <h3 class="text-xs font-semibold uppercase tracking-wide text-ink/40 mb-3">Exam level</h3>
          <label class="filter-check">
            <input type="radio" wire:model.live="filterLevel" value="" name="level"> All Levels
          </label>
          <label class="filter-check">
            <input type="radio" wire:model.live="filterLevel" value="scholarship" name="level"> Grade 5 Scholarship
          </label>
          <label class="filter-check">
            <input type="radio" wire:model.live="filterLevel" value="ol" name="level"> O/L
          </label>
          <label class="filter-check">
            <input type="radio" wire:model.live="filterLevel" value="al" name="level"> A/L
          </label>
        </div>

        <div class="mt-8 space-y-2">
          <h3 class="text-xs font-semibold uppercase tracking-wide text-ink/40 mb-3">Medium</h3>
          <label class="filter-check">
            <input type="radio" wire:model.live="filterMedium" value="" name="medium"> All Mediums
          </label>
          <label class="filter-check">
            <input type="radio" wire:model.live="filterMedium" value="english" name="medium"> English
          </label>
          <label class="filter-check">
            <input type="radio" wire:model.live="filterMedium" value="sinhala" name="medium"> Sinhala
          </label>
          <label class="filter-check">
            <input type="radio" wire:model.live="filterMedium" value="tamil" name="medium"> Tamil
          </label>
        </div>

        <div class="mt-8 space-y-2">
          <h3 class="text-xs font-semibold uppercase tracking-wide text-ink/40 mb-3">Year</h3>
          <label class="filter-check">
            <input type="radio" wire:model.live="filterYear" value="" name="year"> All Years
          </label>
          @foreach($this->availableYears as $year)
          <label class="filter-check">
            <input type="radio" wire:model.live="filterYear" value="{{ $year }}" name="year"> {{ $year }}
          </label>
          @endforeach
        </div>

        <div class="mt-8 space-y-2">
          <h3 class="text-xs font-semibold uppercase tracking-wide text-ink/40 mb-3">Subject</h3>
          <label class="filter-check">
            <input type="radio" wire:model.live="filterSubject" value="" name="subject"> All Subjects
          </label>
          @foreach($this->availableSubjects as $subject)
          <label class="filter-check">
            <input type="radio" wire:model.live="filterSubject" value="{{ $subject->id }}" name="subject"> {{ $subject->name }}
          </label>
          @endforeach
        </div>
      </aside>

      <!-- RESULTS -->
      <div>
        <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-5">
          @forelse($this->groupedPapers as $level => $subjects)
            @foreach($subjects as $subjectName => $papers)
              @foreach($papers as $paper)
                <!-- Card -->
                <div class="bg-white rounded-xl border border-ink/10 p-5 flex flex-col hover:border-teal/40 hover:shadow-md hover:shadow-ink/5 transition">
                  <span class="inline-block w-fit text-[11px] font-semibold uppercase tracking-wide text-{{ $this->levelBadgeColor($level) }} bg-{{ $this->levelBadgeColor($level) }}/10 rounded-full px-2.5 py-0.5 mb-3">{{ $this->levelLabel($level) }}</span>
                  <h3 class="font-display text-lg text-ink mb-1">{{ $subjectName }}</h3>
                  <p class="text-xs text-ink/50 mb-4">{{ $paper->year }} &middot; {{ $paper->questions_count }} questions &middot; {{ $paper->duration_minutes }} min</p>
                  
                  <div class="mt-auto flex items-center justify-between">
                    @if($this->hasAccess($paper))
                      <span class="text-xs font-semibold text-teal">Subscribed</span>
                      <a href="{{ route('quiz.take', $paper->id) }}" wire:navigate class="text-xs font-semibold text-teal hover:underline">Start →</a>
                    @else
                      <span class="text-xs font-medium text-ink/60">Rs. {{ number_format($paper->price, 0) }}</span>
                      <div class="flex items-center gap-3">
                        <a href="{{ route('quiz.take', ['paper' => $paper->id, 'preview' => '1']) }}" wire:navigate class="text-xs font-semibold text-gold hover:underline">Try free</a>
                        <a href="{{ route('subscribe') }}" wire:navigate class="text-xs font-semibold text-teal hover:underline">Subscribe →</a>
                      </div>
                    @endif
                  </div>
                </div>
              @endforeach
            @endforeach
          @empty
            <div class="col-span-full py-12 text-center text-ink/50">
                No papers found matching your filters.
            </div>
          @endforelse
        </div>
      </div>
    </section>
</div>
