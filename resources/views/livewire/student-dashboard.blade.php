<div>
  <style>
    h1, h2, h3, .font-display { font-family: 'Fraunces', serif; font-variation-settings: 'opsz' 56, 'wght' 480, 'SOFT' 15, 'WONK' 0; }
    .bg-examsheet-ambient {
      background-color: #F7F4EC;
      background-image: repeating-linear-gradient(180deg, rgba(34,49,74,0.025) 0px, rgba(34,49,74,0.025) 1px, transparent 1px, transparent 32px);
    }
  </style>

  <!-- NAV -->
  <header class="sticky top-0 z-50 bg-paper/95 backdrop-blur-sm border-b border-ink/10">
    <div class="max-w-6xl mx-auto px-6 h-16 flex items-center justify-between">
      <a href="{{ route('dashboard') }}" wire:navigate class="flex items-center gap-2">
        <svg viewBox="636 340 1124 1112" class="w-7 h-7"><g transform="translate(0,1792) scale(0.1,-0.1)" fill="#3F7D6B" stroke="none"><path d="M10915 12730 c-134 -31 -324 -89 -350 -106 -37 -24 -69 -97 -62 -145 11 -83 70 -132 152 -126 48 3 314 72 402 103 112 40 125 193 23 265 -38 27 -78 29 -165 9z"/><path d="M9963 12361 c-527 -265 -1098 -815 -1443 -1391 -197 -327 -251 -478 -200 -558 27 -44 97 -75 145 -66 70 13 93 40 176 201 350 685 781 1145 1434 1531 134 79 155 103 155 177 0 62 -31 112 -84 136 -59 27 -73 24 -183 -30z"/><path d="M7926 9653 c-32 -111 -66 -499 -66 -748 0 -2274 1914 -4118 4157 -4005 1243 63 2383 718 3087 1775 159 238 363 620 382 713 l7 32 -306 0 -305 0 -11 -44 c-13 -51 -53 -90 -109 -105 -34 -10 -651 -18 -2872 -37 l-725 -7 -485 -163 c-453 -153 -520 -174 -520 -158 0 7 53 190 250 864 79 267 166 566 195 665 29 99 121 410 204 692 83 281 151 519 151 527 0 26 -3026 24 -3034 -1z m2020 -1544 c31 -15 48 -32 63 -63 38 -76 23 -132 -59 -223 -131 -148 -201 -387 -170 -586 61 -402 382 -680 785 -681 185 0 339 54 476 167 84 70 98 77 156 77 123 0 195 -139 124 -239 -57 -80 -289 -221 -441 -269 -154 -49 -366 -62 -515 -33 -654 128 -1049 793 -841 1415 81 240 248 448 365 455 8 1 34 -8 57 -20z"/></g><g transform="translate(0,1792) scale(0.1,-0.1)" fill="#22314A" stroke="none"><path d="M14353 13763 c-81 -86 -283 -296 -448 -467 -165 -171 -399 -415 -520 -541 -121 -126 -328 -342 -460 -480 -277 -288 -1054 -1102 -1166 -1220 l-76 -81 -41 -129 c-22 -72 -68 -222 -102 -335 -60 -197 -89 -296 -235 -790 -37 -124 -100 -337 -141 -475 -438 -1461 -559 -1873 -551 -1881 4 -4 608 198 1707 571 914 310 1521 516 1679 569 l184 61 386 405 c212 223 501 524 641 670 262 272 1644 1721 1738 1821 28 31 52 61 52 66 0 6 -165 170 -367 365 -433 417 -469 451 -481 446 -6 -1 -180 -181 -388 -398 -208 -217 -594 -620 -859 -895 -264 -275 -633 -660 -820 -855 -488 -511 -605 -630 -616 -630 -17 0 -690 663 -687 677 2 7 190 207 418 445 229 238 683 712 1010 1053 327 341 736 768 910 948 173 181 317 336 318 344 4 17 -174 203 -194 203 -6 0 -37 -28 -70 -62 -70 -75 -641 -670 -1014 -1058 -146 -151 -521 -543 -835 -870 -724 -757 -757 -790 -770 -790 -15 0 -405 379 -405 394 0 6 86 101 192 211 106 110 359 373 563 585 204 212 523 545 710 740 186 195 402 420 480 500 286 295 715 749 715 757 0 16 -275 283 -292 283 -9 0 -83 -71 -165 -157z m-1982 -3565 c1075 -1041 1370 -1331 1367 -1345 -2 -12 -1034 -381 -1315 -470 l-52 -16 -338 329 c-186 181 -371 361 -412 400 l-73 70 22 75 c71 243 380 1269 389 1292 6 15 17 27 25 27 7 0 182 -163 387 -362z m-790 -1470 c60 -56 109 -105 109 -110 0 -9 -23 -39 -298 -395 -140 -183 -192 -239 -192 -205 0 4 13 50 29 102 228 749 215 710 232 710 6 0 60 -46 120 -102z"/><path d="M11547 13859 c-2238 -122 -4144 -1844 -4521 -4084 -53 -316 -60 -422 -61 -840 0 -419 9 -552 61 -854 202 -1198 843 -2272 1814 -3046 1045 -833 2369 -1188 3705 -994 1800 261 3370 1617 3920 3386 74 237 169 653 152 669 -7 8 -451 -78 -463 -90 -6 -6 -30 -89 -53 -185 -260 -1064 -933 -2018 -1863 -2639 -1509 -1008 -3451 -978 -4929 76 -1064 759 -1735 1911 -1881 3227 -20 183 -17 730 6 920 143 1222 712 2265 1657 3038 746 611 1659 942 2654 964 517 11 923 -47 1444 -207 73 -23 138 -40 145 -38 6 2 80 74 164 160 150 155 172 188 135 203 -654 259 -1367 373 -2086 334z"/><path d="M11885 13054 c-43 -22 -60 -40 -79 -82 -20 -45 -516 -2036 -516 -2071 0 -42 34 11 84 132 33 78 92 219 131 312 617 1457 645 1531 614 1612 -33 88 -155 138 -234 97z"/><path d="M16755 10170 c-35 -17 -1165 -860 -1683 -1254 -167 -127 -216 -177 -162 -163 12 3 208 106 438 230 372 200 951 511 1377 739 265 142 285 162 285 275 0 141 -135 233 -255 173z"/><path d="M16085 8665 c-176 -57 -603 -194 -950 -304 -346 -110 -749 -238 -895 -285 -146 -47 -354 -114 -462 -148 -125 -40 -198 -68 -198 -76 0 -10 8 -12 28 -8 29 7 28 6 1157 221 1976 377 1777 335 1826 384 90 90 67 252 -44 304 -67 32 -120 22 -462 -88z"/><path d="M9841 8118 c-41 -11 -132 -101 -189 -185 -250 -369 -243 -871 19 -1231 366 -502 1049 -607 1541 -237 134 101 161 156 120 248 -26 59 -70 87 -135 87 -58 0 -72 -7 -156 -77 -225 -186 -544 -222 -817 -93 -459 219 -599 828 -274 1193 82 91 97 147 59 223 -31 66 -95 93 -168 72z"/><path d="M13055 7545 l-960 -12 -417 -138 c-299 -99 -418 -143 -418 -153 0 -13 81 -14 648 -9 356 4 1135 12 1732 17 654 7 1100 15 1122 21 118 31 153 178 62 259 l-35 30 -387 -2 c-213 0 -819 -6 -1347 -13z"/></g></svg>
        <span class="font-display text-xl text-ink">Papelooo</span>
      </a>
      <nav class="hidden md:flex items-center gap-8 text-sm font-medium text-ink/80">
        <a href="{{ route('dashboard') }}" wire:navigate class="{{ request()->routeIs('dashboard') ? 'text-teal' : 'hover:text-ink' }}">Dashboard</a>
        <a href="{{ route('papers') }}" wire:navigate class="{{ request()->routeIs('papers') ? 'text-teal' : 'hover:text-ink' }}">Papers</a>
        <a href="{{ route('progress') }}" wire:navigate class="{{ request()->routeIs('progress') ? 'text-teal' : 'hover:text-ink' }}">Progress</a>
      </nav>
      
      <div x-data="{ open: false }" class="relative">
        <button @click="open = !open" @click.outside="open = false" class="flex items-center gap-2.5 hover:bg-ink/5 p-1.5 -m-1.5 rounded-xl transition">
          <div class="w-8 h-8 rounded-full bg-teal flex items-center justify-center text-paper text-xs font-semibold">{{ strtoupper(substr($user->name, 0, 2)) }}</div>
          <span class="text-sm font-medium text-ink hidden sm:inline">{{ explode(' ', $user->name)[0] }}</span>
          <svg class="w-4 h-4 text-ink/40" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M6 9l6 6 6-6"/></svg>
        </button>
        
        <div x-show="open" style="display: none;" class="absolute right-0 top-full mt-2 w-48 bg-white rounded-xl shadow-lg border border-ink/10 py-2 z-50">
          <div class="px-4 py-2 border-b border-ink/10 mb-2">
            <p class="text-xs font-semibold text-ink truncate">{{ $user->name }}</p>
            <p class="text-xs text-ink/60 truncate">{{ $user->email }}</p>
          </div>
          @if($user->is_admin)
          <a href="{{ route('admin.questions') }}" wire:navigate class="block px-4 py-1.5 text-sm text-ink/80 hover:bg-ink/5 hover:text-ink">Admin Panel</a>
          @endif
          <a href="{{ route('profile') }}" wire:navigate class="block px-4 py-1.5 text-sm text-ink/80 hover:bg-ink/5 hover:text-ink">Profile Settings</a>
          <button wire:click="logout" class="w-full text-left px-4 py-1.5 text-sm text-margin hover:bg-margin/5 transition mt-2 border-t border-ink/10 pt-2">Log out</button>
        </div>
      </div>
    </div>
  </header>

  <main class="max-w-6xl mx-auto px-6 py-10">

    <h1 class="font-display text-3xl text-ink mb-1">Welcome back, {{ explode(' ', $user->name)[0] }}</h1>
    <p class="text-ink/60 mb-8">Here's where you left off.</p>

    <!-- CONTINUE LAST ATTEMPT -->
    @if($incompleteAttempt)
    @php
      $answeredCount = \App\Models\AttemptAnswer::where('attempt_id', $incompleteAttempt->id)->count();
      $total = $incompleteAttempt->total_questions ?? 0;
      $timeElapsed = $incompleteAttempt->started_at->diffInMinutes(\Carbon\Carbon::now());
      $timeRemaining = max(0, ($incompleteAttempt->paper->duration_minutes ?? 60) - $timeElapsed);
    @endphp
    <div class="bg-white rounded-2xl border-2 border-teal p-6 mb-8 flex flex-col sm:flex-row items-start sm:items-center gap-5 justify-between">
      <div class="flex items-center gap-4">
        <div class="w-12 h-12 rounded-xl bg-teal/10 flex items-center justify-center flex-shrink-0">
          <svg class="w-6 h-6 text-teal" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 3"/></svg>
        </div>
        <div>
          <p class="text-xs font-semibold text-teal uppercase tracking-wide mb-0.5">Continue where you left off</p>
          <h2 class="font-display text-xl text-ink">{{ $incompleteAttempt->paper->title }}</h2>
          <p class="text-xs text-ink/50 mt-1">{{ $answeredCount }} of {{ $total }} questions answered &middot; {{ $timeRemaining }} minutes remaining</p>
        </div>
      </div>
      <a href="{{ route('quiz.take', $incompleteAttempt->paper_id) }}" wire:navigate class="w-full sm:w-auto text-center rounded-lg bg-teal text-paper font-semibold px-6 py-3 hover:bg-teal/90 transition flex-shrink-0">Resume paper</a>
    </div>
    @endif

    <!-- STATS -->
    <div class="grid grid-cols-3 gap-4 mb-8">
      <div class="bg-white rounded-xl border border-ink/10 p-4 text-center">
        <p class="font-display text-2xl text-ink">{{ $stats['papers_attempted'] }}</p>
        <p class="text-xs text-ink/50 mt-1">Papers attempted</p>
      </div>
      <div class="bg-white rounded-xl border border-ink/10 p-4 text-center">
        <p class="font-display text-2xl text-teal">{{ $stats['average_score'] }}%</p>
        <p class="text-xs text-ink/50 mt-1">Average score</p>
      </div>
      <div class="bg-white rounded-xl border border-ink/10 p-4 text-center">
        <p class="font-display text-2xl text-ink">{{ $stats['papers_this_week'] }}</p>
        <p class="text-xs text-ink/50 mt-1">Papers this week</p>
      </div>
    </div>

    <div class="grid lg:grid-cols-[1fr_320px] gap-6">

      <!-- RECENT SCORES -->
      <div class="bg-white rounded-2xl border border-ink/10 overflow-hidden">
        <div class="flex items-center justify-between px-6 py-4 border-b border-ink/10">
          <h2 class="font-display text-lg text-ink">Recent scores</h2>
          <a href="{{ route('progress') }}" wire:navigate class="text-xs font-semibold text-teal hover:underline">View all &rarr;</a>
        </div>
        @if(count($recentAttempts) > 0)
        <table class="w-full text-sm">
          <tbody class="divide-y divide-ink/5">
            @foreach($recentAttempts as $attempt)
            <tr>
              <td class="px-6 py-3.5">
                <p class="font-medium text-ink"><a href="{{ route('result.summary', $attempt->id) }}" wire:navigate class="hover:underline">{{ $attempt->paper->title }}</a></p>
                <p class="text-xs text-ink/40">{{ $attempt->completed_at->isToday() ? 'Today' : $attempt->completed_at->format('M j, Y') }}</p>
              </td>
              <td class="px-6 py-3.5 text-right"><span class="{{ $attempt->score >= 40 ? 'text-teal' : 'text-margin' }} font-semibold">{{ $attempt->score }}%</span></td>
            </tr>
            @endforeach
          </tbody>
        </table>
        @else
        <div class="px-6 py-8 text-center text-ink/50 text-sm">
            No completed papers yet. Start practicing!
        </div>
        @endif
      </div>

      <!-- SUGGESTED NEXT PAPER -->
      <div>
        @if($suggestedPaper)
        <div class="bg-white rounded-2xl border border-ink/10 p-5 mb-5">
          <p class="text-xs font-semibold text-gold uppercase tracking-wide mb-3">Suggested for you</p>
          <span class="inline-block text-[11px] font-semibold uppercase text-teal bg-teal/10 rounded-full px-2.5 py-0.5 mb-2">{{ strtoupper($suggestedPaper->level) }}</span>
          <h3 class="font-display text-lg text-ink mb-1">{{ $suggestedPaper->title }}</h3>
          <p class="text-xs text-ink/50 mb-4">A new challenge awaits you based on your recent activity.</p>
          <a href="{{ route('quiz.take', $suggestedPaper->id) }}" class="block text-center rounded-lg bg-teal text-paper text-sm font-semibold py-2.5 hover:bg-teal/90 transition">Start this paper</a>
        </div>
        @endif

        <div class="bg-white rounded-2xl border border-ink/10 p-5">
          <p class="text-xs font-semibold text-ink/40 uppercase tracking-wide mb-3">Quick links</p>
          <div class="space-y-1">
            <a href="{{ route('papers') }}" class="block text-sm text-ink/70 hover:text-teal py-1.5">Browse all past papers &rarr;</a>
            <a href="{{ route('progress') }}" wire:navigate class="block text-sm text-ink/70 hover:text-teal py-1.5">See full progress report &rarr;</a>
            <a href="{{ route('progress') }}" wire:navigate class="block text-sm text-ink/70 hover:text-teal py-1.5">Attempt history &rarr;</a>
          </div>
        </div>
      </div>
    </div>
  </main>
</div>
