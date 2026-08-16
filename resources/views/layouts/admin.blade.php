<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="{{ csrf_token() }}">

<title>{{ config('app.name', 'Papelooo') }} - Admin</title>

<link rel="icon" href="{{ asset('images/papelooo-icon-tile.svg') }}" type="image/svg+xml">


<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght,SOFT,WONK@9..144,300..700,0..100,0..1&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

@vite(['resources/css/app.css', 'resources/js/app.js'])
<style>
  body { font-family: 'Inter', sans-serif; }
  .font-display { font-family: 'Fraunces', serif; font-variation-settings: 'opsz' 48, 'wght' 480, 'SOFT' 10, 'WONK' 0; }
  .nav-item { display: flex; align-items: center; gap: 0.7rem; padding: 0.55rem 0.85rem; border-radius: 0.5rem; font-size: 0.875rem; font-weight: 500; color: rgba(245,241,230,0.6); transition: all .15s; }
  .nav-item:hover { color: #F5F1E6; background: rgba(245,241,230,0.06); }
  .nav-item.active { color: #F5F1E6; background: rgba(63,125,107,0.35); }
  .nav-item svg { width: 17px; height: 17px; flex-shrink: 0; }
</style>
</head>
<body class="text-ink antialiased" style="background:#F7F4EC;">
<div class="flex min-h-screen">

  <!-- SIDEBAR -->
  <aside class="w-60 flex-shrink-0 bg-ink flex flex-col">
    <div class="h-16 flex items-center gap-2 px-5 border-b border-paper/10">
      <svg viewBox="636 340 1124 1112" class="w-7 h-7"><g transform="translate(0,1792) scale(0.1,-0.1)" fill="#3F7D6B" stroke="none"><path d="M10915 12730 c-134 -31 -324 -89 -350 -106 -37 -24 -69 -97 -62 -145 11 -83 70 -132 152 -126 48 3 314 72 402 103 112 40 125 193 23 265 -38 27 -78 29 -165 9z"/><path d="M9963 12361 c-527 -265 -1098 -815 -1443 -1391 -197 -327 -251 -478 -200 -558 27 -44 97 -75 145 -66 70 13 93 40 176 201 350 685 781 1145 1434 1531 134 79 155 103 155 177 0 62 -31 112 -84 136 -59 27 -73 24 -183 -30z"/><path d="M7926 9653 c-32 -111 -66 -499 -66 -748 0 -2274 1914 -4118 4157 -4005 1243 63 2383 718 3087 1775 159 238 363 620 382 713 l7 32 -306 0 -305 0 -11 -44 c-13 -51 -53 -90 -109 -105 -34 -10 -651 -18 -2872 -37 l-725 -7 -485 -163 c-453 -153 -520 -174 -520 -158 0 7 53 190 250 864 79 267 166 566 195 665 29 99 121 410 204 692 83 281 151 519 151 527 0 26 -3026 24 -3034 -1z m2020 -1544 c31 -15 48 -32 63 -63 38 -76 23 -132 -59 -223 -131 -148 -201 -387 -170 -586 61 -402 382 -680 785 -681 185 0 339 54 476 167 84 70 98 77 156 77 123 0 195 -139 124 -239 -57 -80 -289 -221 -441 -269 -154 -49 -366 -62 -515 -33 -654 128 -1049 793 -841 1415 81 240 248 448 365 455 8 1 34 -8 57 -20z"/></g><g transform="translate(0,1792) scale(0.1,-0.1)" fill="#F5F1E6" stroke="none"><path d="M14353 13763 c-81 -86 -283 -296 -448 -467 -165 -171 -399 -415 -520 -541 -121 -126 -328 -342 -460 -480 -277 -288 -1054 -1102 -1166 -1220 l-76 -81 -41 -129 c-22 -72 -68 -222 -102 -335 -60 -197 -89 -296 -235 -790 -37 -124 -100 -337 -141 -475 -438 -1461 -559 -1873 -551 -1881 4 -4 608 198 1707 571 914 310 1521 516 1679 569 l184 61 386 405 c212 223 501 524 641 670 262 272 1644 1721 1738 1821 28 31 52 61 52 66 0 6 -165 170 -367 365 -433 417 -469 451 -481 446 -6 -1 -180 -181 -388 -398 -208 -217 -594 -620 -859 -895 -264 -275 -633 -660 -820 -855 -488 -511 -605 -630 -616 -630 -17 0 -690 663 -687 677 2 7 190 207 418 445 229 238 683 712 1010 1053 327 341 736 768 910 948 173 181 317 336 318 344 4 17 -174 203 -194 203 -6 0 -37 -28 -70 -62 -70 -75 -641 -670 -1014 -1058 -146 -151 -521 -543 -835 -870 -724 -757 -757 -790 -770 -790 -15 0 -405 379 -405 394 0 6 86 101 192 211 106 110 359 373 563 585 204 212 523 545 710 740 186 195 402 420 480 500 286 295 715 749 715 757 0 16 -275 283 -292 283 -9 0 -83 -71 -165 -157z m-1982 -3565 c1075 -1041 1370 -1331 1367 -1345 -2 -12 -1034 -381 -1315 -470 l-52 -16 -338 329 c-186 181 -371 361 -412 400 l-73 70 22 75 c71 243 380 1269 389 1292 6 15 17 27 25 27 7 0 182 -163 387 -362z m-790 -1470 c60 -56 109 -105 109 -110 0 -9 -23 -39 -298 -395 -140 -183 -192 -239 -192 -205 0 4 13 50 29 102 228 749 215 710 232 710 6 0 60 -46 120 -102z"/><path d="M11547 13859 c-2238 -122 -4144 -1844 -4521 -4084 -53 -316 -60 -422 -61 -840 0 -419 9 -552 61 -854 202 -1198 843 -2272 1814 -3046 1045 -833 2369 -1188 3705 -994 1800 261 3370 1617 3920 3386 74 237 169 653 152 669 -7 8 -451 -78 -463 -90 -6 -6 -30 -89 -53 -185 -260 -1064 -933 -2018 -1863 -2639 -1509 -1008 -3451 -978 -4929 76 -1064 759 -1735 1911 -1881 3227 -20 183 -17 730 6 920 143 1222 712 2265 1657 3038 746 611 1659 942 2654 964 517 11 923 -47 1444 -207 73 -23 138 -40 145 -38 6 2 80 74 164 160 150 155 172 188 135 203 -654 259 -1367 373 -2086 334z"/><path d="M11885 13054 c-43 -22 -60 -40 -79 -82 -20 -45 -516 -2036 -516 -2071 0 -42 34 11 84 132 33 78 92 219 131 312 617 1457 645 1531 614 1612 -33 88 -155 138 -234 97z"/><path d="M16755 10170 c-35 -17 -1165 -860 -1683 -1254 -167 -127 -216 -177 -162 -163 12 3 208 106 438 230 372 200 951 511 1377 739 265 142 285 162 285 275 0 141 -135 233 -255 173z"/><path d="M16085 8665 c-176 -57 -603 -194 -950 -304 -346 -110 -749 -238 -895 -285 -146 -47 -354 -114 -462 -148 -125 -40 -198 -68 -198 -76 0 -10 8 -12 28 -8 29 7 28 6 1157 221 1976 377 1777 335 1826 384 90 90 67 252 -44 304 -67 32 -120 22 -462 -88z"/><path d="M9841 8118 c-41 -11 -132 -101 -189 -185 -250 -369 -243 -871 19 -1231 366 -502 1049 -607 1541 -237 134 101 161 156 120 248 -26 59 -70 87 -135 87 -58 0 -72 -7 -156 -77 -225 -186 -544 -222 -817 -93 -459 219 -599 828 -274 1193 82 91 97 147 59 223 -31 66 -95 93 -168 72z"/><path d="M13055 7545 l-960 -12 -417 -138 c-299 -99 -418 -143 -418 -153 0 -13 81 -14 648 -9 356 4 1135 12 1732 17 654 7 1100 15 1122 21 118 31 153 178 62 259 l-35 30 -387 -2 c-213 0 -819 -6 -1347 -13z"/></g></svg>
      <span class="font-display text-lg text-paper">Papelooo</span>
      <span class="text-[10px] font-semibold text-paper/40 border border-paper/20 rounded px-1.5 py-0.5 ml-auto">ADMIN</span>
    </div>

    <nav class="flex-1 px-3 py-5 space-y-1">
      <a href="{{ route('admin.dashboard') }}" wire:navigate class="nav-item {{ request()->routeIs('admin.dashboard') || request()->routeIs('admin.analytics') ? 'active' : '' }}">
        <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="3" width="7" height="9" rx="1"/><rect x="14" y="3" width="7" height="5" rx="1"/><rect x="14" y="12" width="7" height="9" rx="1"/><rect x="3" y="16" width="7" height="5" rx="1"/></svg>
        Dashboard
      </a>
      <a href="{{ route('admin.papers') }}" wire:navigate class="nav-item {{ request()->routeIs('admin.papers') ? 'active' : '' }}">
        <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M4 4h11l5 5v11a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V5a1 1 0 0 1 1-1z"/><path d="M14 4v5h5"/></svg>
        Papers
      </a>
      <a href="{{ route('admin.questions') }}" wire:navigate class="nav-item {{ request()->routeIs('admin.questions') ? 'active' : '' }}">
        <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="9"/><path d="M9 12l2 2 4-4"/></svg>
        Questions
      </a>
      <a href="{{ route('admin.users') }}" wire:navigate class="nav-item {{ request()->routeIs('admin.users') ? 'active' : '' }}">
        <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="9" cy="8" r="3.5"/><path d="M2.5 20a6.5 6.5 0 0 1 13 0"/><circle cx="18" cy="9" r="2.5"/><path d="M15.5 20a4.5 4.5 0 0 1 7.5-3.4"/></svg>
        Users
      </a>
      <a href="{{ route('admin.payments') }}" wire:navigate class="nav-item {{ request()->routeIs('admin.payments') ? 'active' : '' }}">
        <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="2" y="5" width="20" height="15" rx="2"/><path d="M2 10h20"/></svg>
        Payments
      </a>
      <a href="{{ route('admin.messages') }}" wire:navigate class="nav-item {{ request()->routeIs('admin.messages') ? 'active' : '' }}">
        <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg>
        Messages
      </a>

      <a href="{{ route('admin.settings') }}" wire:navigate class="nav-item {{ request()->routeIs('admin.settings') ? 'active' : '' }}">
        <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 1 1-2.83 2.83l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-4 0v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 1 1-2.83-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1 0-4h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 1 1 2.83-2.83l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 4 0v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 1 1 2.83 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 0 4h-.09a1.65 1.65 0 0 0-1.51 1z"/></svg>
        Settings
      </a>
    </nav>

    <div class="p-3 border-t border-paper/10">
      <div x-data="{ dropdownOpen: false }" class="relative">
        <div @click="dropdownOpen = !dropdownOpen" @click.away="dropdownOpen = false" class="flex items-center gap-2.5 px-2 py-2 rounded-lg hover:bg-paper/5 cursor-pointer">
          <div class="w-8 h-8 rounded-full bg-teal flex items-center justify-center text-paper text-xs font-semibold flex-shrink-0 uppercase">
            {{ substr(auth()->user()->name ?? 'A', 0, 2) }}
          </div>
          <div class="min-w-0 flex-1">
            <p class="text-xs font-medium text-paper truncate">{{ auth()->user()->name ?? 'Admin' }}</p>
            <p class="text-[11px] text-paper/40 truncate">Admin</p>
          </div>
        </div>
        
        <div x-show="dropdownOpen" x-transition style="display:none;" class="absolute bottom-full left-0 mb-2 w-full bg-white rounded-lg shadow-lg border border-ink/10 overflow-hidden z-50">
          <a href="{{ route('profile') }}" class="block px-4 py-2 text-sm font-medium text-ink/80 hover:bg-paper/50">Profile</a>
          <div class="block px-4 py-2 text-sm font-medium text-ink/80 hover:bg-paper/50">
              <livewire:frontend-logout />
          </div>
        </div>
      </div>
    </div>
  </aside>

  <!-- MAIN -->
  <div class="flex-1 flex flex-col min-w-0 h-screen">

    @if(isset($customHeader))
      {{ $customHeader }}
    @else
      <!-- HEADER -->
      <header class="h-16 bg-white border-b border-ink/10 flex items-center justify-between px-8 flex-shrink-0">
        <h1 class="font-display text-xl text-ink">{{ $header ?? 'Dashboard' }}</h1>
        <div class="flex items-center gap-5">
          <div class="relative hidden sm:block">
            <input type="text" placeholder="Search students, papers…" class="w-64 text-sm rounded-lg border border-ink/15 pl-9 pr-3 py-2 focus:outline-none focus:ring-2 focus:ring-teal/30 focus:border-teal">
            <svg class="w-4 h-4 absolute left-3 top-2.5 text-ink/40" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="11" cy="11" r="7"/><path d="m21 21-4.35-4.35"/></svg>
          </div>
          <livewire:admin.header-notifications />
          {{ $headerActions ?? '' }}
        </div>
      </header>
    @endif

    <!-- CONTENT -->
    <main class="{{ $mainClass ?? 'flex-1 p-8 overflow-y-auto' }}">
      {{ $slot }}
    </main>
  </div>
</div>
@fluxScripts
</body>
</html>
