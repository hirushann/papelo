<div x-data="{ activeTab: @entangle('activeTab') }">
  <style>
    h1, h2, .font-display { font-family: 'Fraunces', serif; font-variation-settings: 'opsz' 48, 'wght' 480, 'SOFT' 10, 'WONK' 0; }
    .bg-examsheet-ambient {
      background-color: #F7F4EC;
      background-image: repeating-linear-gradient(180deg, rgba(34,49,74,0.025) 0px, rgba(34,49,74,0.025) 1px, transparent 1px, transparent 32px);
    }
    .subnav-item { display:block; font-size:0.85rem; font-weight:500; color:rgba(34,49,74,0.55); padding:0.5rem 0.75rem; border-radius:0.5rem; transition: all 0.2s; cursor:pointer;}
    .subnav-item:hover { color:#22314A; background:rgba(34,49,74,0.04); }
    .subnav-item.active { color:#22314A; background:rgba(63,125,107,0.1); font-weight:600; }
    .field label { display:block; font-size:0.8rem; font-weight:600; color:rgba(34,49,74,0.75); margin-bottom:0.35rem; }
    .field input { width:100%; border:1px solid rgba(34,49,74,0.15); border-radius:0.5rem; padding:0.6rem 0.85rem; font-size:0.875rem; color:#22314A; background:#fff; transition: all 0.2s;}
    .field input:focus { outline:none; border-color:#3F7D6B; box-shadow:0 0 0 3px rgba(63,125,107,0.15); }
    .field .error { color:#B5514A; font-size:0.75rem; margin-top:0.25rem; display:block; }
    .level-pill { font-size:0.8rem; font-weight:600; padding:0.5rem 1rem; border-radius:9999px; border:1.5px solid rgba(34,49,74,0.15); color:rgba(34,49,74,0.6); cursor:pointer; transition: all 0.2s;}
    .level-pill.selected { background:#22314A; border-color:#22314A; color:#F5F1E6; }
    .settings-card { background:#fff; border:1px solid rgba(34,49,74,0.1); border-radius:1rem; }
  </style>

  <!-- NAV -->
  <header class="sticky top-0 z-50 bg-paper/95 backdrop-blur-sm border-b border-ink/10">
    <div class="max-w-6xl mx-auto px-6 h-16 flex items-center justify-between">
      <a href="{{ route('dashboard') }}" wire:navigate class="flex items-center gap-2">
        <svg viewBox="636 340 1124 1112" class="w-7 h-7"><g transform="translate(0,1792) scale(0.1,-0.1)" fill="#3F7D6B" stroke="none"><path d="M10915 12730 c-134 -31 -324 -89 -350 -106 -37 -24 -69 -97 -62 -145 11 -83 70 -132 152 -126 48 3 314 72 402 103 112 40 125 193 23 265 -38 27 -78 29 -165 9z"/><path d="M9963 12361 c-527 -265 -1098 -815 -1443 -1391 -197 -327 -251 -478 -200 -558 27 -44 97 -75 145 -66 70 13 93 40 176 201 350 685 781 1145 1434 1531 134 79 155 103 155 177 0 62 -31 112 -84 136 -59 27 -73 24 -183 -30z"/><path d="M7926 9653 c-32 -111 -66 -499 -66 -748 0 -2274 1914 -4118 4157 -4005 1243 63 2383 718 3087 1775 159 238 363 620 382 713 l7 32 -306 0 -305 0 -11 -44 c-13 -51 -53 -90 -109 -105 -34 -10 -651 -18 -2872 -37 l-725 -7 -485 -163 c-453 -153 -520 -174 -520 -158 0 7 53 190 250 864 79 267 166 566 195 665 29 99 121 410 204 692 83 281 151 519 151 527 0 26 -3026 24 -3034 -1z m2020 -1544 c31 -15 48 -32 63 -63 38 -76 23 -132 -59 -223 -131 -148 -201 -387 -170 -586 61 -402 382 -680 785 -681 185 0 339 54 476 167 84 70 98 77 156 77 123 0 195 -139 124 -239 -57 -80 -289 -221 -441 -269 -154 -49 -366 -62 -515 -33 -654 128 -1049 793 -841 1415 81 240 248 448 365 455 8 1 34 -8 57 -20z"/></g><g transform="translate(0,1792) scale(0.1,-0.1)" fill="#22314A" stroke="none"><path d="M14353 13763 c-81 -86 -283 -296 -448 -467 -165 -171 -399 -415 -520 -541 -121 -126 -328 -342 -460 -480 -277 -288 -1054 -1102 -1166 -1220 l-76 -81 -41 -129 c-22 -72 -68 -222 -102 -335 -60 -197 -89 -296 -235 -790 -37 -124 -100 -337 -141 -475 -438 -1461 -559 -1873 -551 -1881 4 -4 608 198 1707 571 914 310 1521 516 1679 569 l184 61 386 405 c212 223 501 524 641 670 262 272 1644 1721 1738 1821 28 31 52 61 52 66 0 6 -165 170 -367 365 -433 417 -469 451 -481 446 -6 -1 -180 -181 -388 -398 -208 -217 -594 -620 -859 -895 -264 -275 -633 -660 -820 -855 -488 -511 -605 -630 -616 -630 -17 0 -690 663 -687 677 2 7 190 207 418 445 229 238 683 712 1010 1053 327 341 736 768 910 948 173 181 317 336 318 344 4 17 -174 203 -194 203 -6 0 -37 -28 -70 -62 -70 -75 -641 -670 -1014 -1058 -146 -151 -521 -543 -835 -870 -724 -757 -757 -790 -770 -790 -15 0 -405 379 -405 394 0 6 86 101 192 211 106 110 359 373 563 585 204 212 523 545 710 740 186 195 402 420 480 500 286 295 715 749 715 757 0 16 -275 283 -292 283 -9 0 -83 -71 -165 -157z m-1982 -3565 c1075 -1041 1370 -1331 1367 -1345 -2 -12 -1034 -381 -1315 -470 l-52 -16 -338 329 c-186 181 -371 361 -412 400 l-73 70 22 75 c71 243 380 1269 389 1292 6 15 17 27 25 27 7 0 182 -163 387 -362z m-790 -1470 c60 -56 109 -105 109 -110 0 -9 -23 -39 -298 -395 -140 -183 -192 -239 -192 -205 0 4 13 50 29 102 228 749 215 710 232 710 6 0 60 -46 120 -102z"/><path d="M11547 13859 c-2238 -122 -4144 -1844 -4521 -4084 -53 -316 -60 -422 -61 -840 0 -419 9 -552 61 -854 202 -1198 843 -2272 1814 -3046 1045 -833 2369 -1188 3705 -994 1800 261 3370 1617 3920 3386 74 237 169 653 152 669 -7 8 -451 -78 -463 -90 -6 -6 -30 -89 -53 -185 -260 -1064 -933 -2018 -1863 -2639 -1509 -1008 -3451 -978 -4929 76 -1064 759 -1735 1911 -1881 3227 -20 183 -17 730 6 920 143 1222 712 2265 1657 3038 746 611 1659 942 2654 964 517 11 923 -47 1444 -207 73 -23 138 -40 145 -38 6 2 80 74 164 160 150 155 172 188 135 203 -654 259 -1367 373 -2086 334z"/><path d="M11885 13054 c-43 -22 -60 -40 -79 -82 -20 -45 -516 -2036 -516 -2071 0 -42 34 11 84 132 33 78 92 219 131 312 617 1457 645 1531 614 1612 -33 88 -155 138 -234 97z"/><path d="M16755 10170 c-35 -17 -1165 -860 -1683 -1254 -167 -127 -216 -177 -162 -163 12 3 208 106 438 230 372 200 951 511 1377 739 265 142 285 162 285 275 0 141 -135 233 -255 173z"/><path d="M16085 8665 c-176 -57 -603 -194 -950 -304 -346 -110 -749 -238 -895 -285 -146 -47 -354 -114 -462 -148 -125 -40 -198 -68 -198 -76 0 -10 8 -12 28 -8 29 7 28 6 1157 221 1976 377 1777 335 1826 384 90 90 67 252 -44 304 -67 32 -120 22 -462 -88z"/><path d="M9841 8118 c-41 -11 -132 -101 -189 -185 -250 -369 -243 -871 19 -1231 366 -502 1049 -607 1541 -237 134 101 161 156 120 248 -26 59 -70 87 -135 87 -58 0 -72 -7 -156 -77 -225 -186 -544 -222 -817 -93 -459 219 -599 828 -274 1193 82 91 97 147 59 223 -31 66 -95 93 -168 72z"/><path d="M13055 7545 l-960 -12 -417 -138 c-299 -99 -418 -143 -418 -153 0 -13 81 -14 648 -9 356 4 1135 12 1732 17 654 7 1100 15 1122 21 118 31 153 178 62 259 l-35 30 -387 -2 c-213 0 -819 -6 -1347 -13z"/></g></svg>
        <span class="font-display text-xl text-ink">Papelo</span>
      </a>
      <nav class="hidden md:flex items-center gap-8 text-sm font-medium text-ink/80">
        <a href="{{ route('dashboard') }}" wire:navigate class="hover:text-ink">Dashboard</a>
        <a href="{{ route('papers') }}" wire:navigate class="hover:text-ink">Papers</a>
        <a href="{{ route('progress') }}" wire:navigate class="hover:text-ink">Progress</a>
      </nav>
      <div class="flex items-center gap-2.5">
        <div class="w-8 h-8 rounded-full bg-teal flex items-center justify-center text-paper text-xs font-semibold">{{ strtoupper(substr($user->name, 0, 2)) }}</div>
        <span class="text-sm font-medium text-ink hidden sm:inline">{{ explode(' ', $user->name)[0] }}</span>
      </div>
    </div>
  </header>

  <main class="max-w-5xl mx-auto px-6 py-10">
    <h1 class="font-display text-3xl text-ink mb-8">Account Settings</h1>

    <div class="grid lg:grid-cols-[180px_1fr] gap-10">

      <!-- SUB NAV -->
      <nav class="space-y-1 sticky top-24 self-start hidden lg:block">
        <button @click="activeTab = 'account'" :class="{ 'active': activeTab === 'account' }" class="subnav-item w-full text-left">Account</button>
        <button @click="activeTab = 'billing'" :class="{ 'active': activeTab === 'billing' }" class="subnav-item w-full text-left">Billing</button>
      </nav>

      <div class="space-y-8">

        <!-- ACCOUNT -->
        <section x-show="activeTab === 'account'" class="settings-card p-6">
          <form wire:submit="updateProfile">
            <h2 class="font-display text-lg text-ink mb-1">Profile</h2>
            <p class="text-xs text-ink/50 mb-6">Your basic account details.</p>
            
            @if (session('profile-success'))
              <div class="mb-4 text-sm font-medium text-teal bg-teal/10 p-3 rounded-lg border border-teal/20">
                {{ session('profile-success') }}
              </div>
            @endif

            <div class="space-y-4 mb-6">
              <div class="grid sm:grid-cols-2 gap-4">
                <div class="field">
                  <label>Full name</label>
                  <input type="text" wire:model="name" required>
                  @error('name') <span class="error">{{ $message }}</span> @enderror
                </div>
                <div class="field">
                  <label>Email</label>
                  <input type="email" wire:model="email" required>
                  @error('email') <span class="error">{{ $message }}</span> @enderror
                </div>
              </div>
            </div>
            
            <button type="submit" class="text-sm font-semibold bg-teal text-paper rounded-lg px-5 py-2.5 hover:bg-teal/90 transition">
              Save changes
            </button>
          </form>

          <div class="border-t border-ink/10 mt-6 pt-6">
            <form wire:submit="updatePassword">
              <h3 class="text-sm font-semibold text-ink mb-4">Change password</h3>
              
              @if (session('password-success'))
                <div class="mb-4 text-sm font-medium text-teal bg-teal/10 p-3 rounded-lg border border-teal/20">
                  {{ session('password-success') }}
                </div>
              @endif
              
              <div class="space-y-4 max-w-sm mb-5">
                <div class="field">
                  <label>Current password</label>
                  <input type="password" wire:model="current_password" placeholder="••••••••" required>
                  @error('current_password') <span class="error">{{ $message }}</span> @enderror
                </div>
                <div class="field">
                  <label>New password</label>
                  <input type="password" wire:model="password" placeholder="At least 8 characters" required>
                  @error('password') <span class="error">{{ $message }}</span> @enderror
                </div>
                <div class="field">
                  <label>Confirm new password</label>
                  <input type="password" wire:model="password_confirmation" placeholder="••••••••" required>
                </div>
              </div>
              <button type="submit" class="text-sm font-semibold text-ink/70 border border-ink/15 rounded-lg px-5 py-2.5 hover:border-ink/30 transition bg-white">
                Update password
              </button>
            </form>
          </div>
        </section>

        <!-- BILLING -->
        <section x-show="activeTab === 'billing'" style="display: none;" class="settings-card p-6">
          <h2 class="font-display text-lg text-ink mb-1">Billing</h2>
          <p class="text-xs text-ink/50 mb-6">Your payment history.</p>

          <h3 class="text-sm font-semibold text-ink mb-3">Payment history</h3>
          <div class="border border-ink/10 rounded-xl overflow-hidden">
            @if(count($purchases) > 0)
            <table class="w-full text-sm">
              <thead>
                <tr class="text-left text-xs text-ink/40 border-b border-ink/10">
                  <th class="font-medium px-5 py-3">Item</th>
                  <th class="font-medium px-5 py-3">Amount</th>
                  <th class="font-medium px-5 py-3">Date</th>
                  <th class="font-medium px-5 py-3">Status</th>
                  <th class="font-medium px-5 py-3 text-right">Receipt</th>
                </tr>
              </thead>
              <tbody class="divide-y divide-ink/5">
                @foreach($purchases as $purchase)
                <tr>
                  <td class="px-5 py-3.5 text-ink/80">{{ $purchase->paper->title }} <span class="text-ink/40">({{ strtoupper($purchase->paper->subject->level ?? 'Unknown') }})</span></td>
                  <td class="px-5 py-3.5 font-medium text-ink">Rs. {{ number_format($purchase->amount_paid, 2) }}</td>
                  <td class="px-5 py-3.5 text-ink/40">{{ $purchase->created_at->format('M j, Y') }}</td>
                  <td class="px-5 py-3.5"><span class="text-[11px] font-semibold text-teal bg-teal/10 rounded-full px-2.5 py-0.5">Paid</span></td>
                  <td class="px-5 py-3.5 text-right"><a href="#" class="text-xs font-semibold text-teal hover:underline">Download</a></td>
                </tr>
                @endforeach
              </tbody>
            </table>
            @else
            <div class="px-5 py-8 text-center text-sm text-ink/50">
              No payment history found.
            </div>
            @endif
          </div>
        </section>

      </div>
    </div>
  </main>
</div>
