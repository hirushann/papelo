<div x-data="{ loading: null }"
     x-on:redirect-to-checkout.window="window.location.href = $event.detail.url">
  <style>
    h1, h2, .font-display { font-family: 'Fraunces', serif; font-variation-settings: 'opsz' 48, 'wght' 480, 'SOFT' 10, 'WONK' 0; }
    .bg-examsheet-quiet {
      background-color: #F5F1E6;
      background-image: repeating-linear-gradient(180deg, rgba(34,49,74,0.045) 0px, rgba(34,49,74,0.045) 1px, transparent 1px, transparent 32px);
    }
  </style>

  <div class="bg-examsheet-quiet text-ink antialiased min-h-screen">
    <!-- HEADER -->
    <header class="px-6 h-16 flex items-center justify-between max-w-4xl mx-auto w-full">
      <a href="{{ route('papers') }}" wire:navigate class="flex items-center gap-2 text-sm font-medium text-ink/60 hover:text-ink transition">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M15 18l-6-6 6-6"/></svg>
        Back to Papers
      </a>
      <div class="flex items-center gap-2">
        <svg viewBox="636 340 1124 1112" class="w-6 h-6"><g transform="translate(0,1792) scale(0.1,-0.1)" fill="#3F7D6B" stroke="none"><path d="M10915 12730 c-134 -31 -324 -89 -350 -106 -37 -24 -69 -97 -62 -145 11 -83 70 -132 152 -126 48 3 314 72 402 103 112 40 125 193 23 265 -38 27 -78 29 -165 9z"/><path d="M9963 12361 c-527 -265 -1098 -815 -1443 -1391 -197 -327 -251 -478 -200 -558 27 -44 97 -75 145 -66 70 13 93 40 176 201 350 685 781 1145 1434 1531 134 79 155 103 155 177 0 62 -31 112 -84 136 -59 27 -73 24 -183 -30z"/><path d="M7926 9653 c-32 -111 -66 -499 -66 -748 0 -2274 1914 -4118 4157 -4005 1243 63 2383 718 3087 1775 159 238 363 620 382 713 l7 32 -306 0 -305 0 -11 -44 c-13 -51 -53 -90 -109 -105 -34 -10 -651 -18 -2872 -37 l-725 -7 -485 -163 c-453 -153 -520 -174 -520 -158 0 7 53 190 250 864 79 267 166 566 195 665 29 99 121 410 204 692 83 281 151 519 151 527 0 26 -3026 24 -3034 -1z m2020 -1544 c31 -15 48 -32 63 -63 38 -76 23 -132 -59 -223 -131 -148 -201 -387 -170 -586 61 -402 382 -680 785 -681 185 0 339 54 476 167 84 70 98 77 156 77 123 0 195 -139 124 -239 -57 -80 -289 -221 -441 -269 -154 -49 -366 -62 -515 -33 -654 128 -1049 793 -841 1415 81 240 248 448 365 455 8 1 34 -8 57 -20z"/></g><g transform="translate(0,1792) scale(0.1,-0.1)" fill="#22314A" stroke="none"><path d="M14353 13763 c-81 -86 -283 -296 -448 -467 -165 -171 -399 -415 -520 -541 -121 -126 -328 -342 -460 -480 -277 -288 -1054 -1102 -1166 -1220 l-76 -81 -41 -129 c-22 -72 -68 -222 -102 -335 -60 -197 -89 -296 -235 -790 -37 -124 -100 -337 -141 -475 -438 -1461 -559 -1873 -551 -1881 4 -4 608 198 1707 571 914 310 1521 516 1679 569 l184 61 386 405 c212 223 501 524 641 670 262 272 1644 1721 1738 1821 28 31 52 61 52 66 0 6 -165 170 -367 365 -433 417 -469 451 -481 446 -6 -1 -180 -181 -388 -398 -208 -217 -594 -620 -859 -895 -264 -275 -633 -660 -820 -855 -488 -511 -605 -630 -616 -630 -17 0 -690 663 -687 677 2 7 190 207 418 445 229 238 683 712 1010 1053 327 341 736 768 910 948 173 181 317 336 318 344 4 17 -174 203 -194 203 -6 0 -37 -28 -70 -62 -70 -75 -641 -670 -1014 -1058 -146 -151 -521 -543 -835 -870 -724 -757 -757 -790 -770 -790 -15 0 -405 379 -405 394 0 6 86 101 192 211 106 110 359 373 563 585 204 212 523 545 710 740 186 195 402 420 480 500 286 295 715 749 715 757 0 16 -275 283 -292 283 -9 0 -83 -71 -165 -157z"/></g></svg>
        <span class="font-display text-lg text-ink">Papelo</span>
      </div>
      <div class="flex items-center gap-1.5 text-xs font-medium text-ink/40">
        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="4" y="11" width="16" height="9" rx="2"/><path d="M8 11V7a4 4 0 0 1 8 0v4"/></svg>
        Secure
      </div>
    </header>

    <main class="max-w-4xl mx-auto px-6 py-8">

      @if(session('error'))
        <div class="mb-6 bg-margin/10 border border-margin/20 rounded-xl p-4 text-sm text-margin">
          {{ session('error') }}
        </div>
      @endif

      {{-- ALREADY SUBSCRIBED --}}
      @if($currentSubscription && $currentSubscription->isActive())
        <div class="bg-white rounded-2xl border border-ink/10 p-8 text-center mb-8">
          <div class="inline-flex items-center gap-2 bg-teal/10 text-teal rounded-full px-4 py-1.5 text-sm font-semibold mb-4">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M20 6L9 17l-5-5"/></svg>
            Active subscription
          </div>
          <h1 class="font-display text-2xl text-ink mb-2">You're on the {{ $currentPlan->name }} plan</h1>
          <p class="text-ink/60 text-sm mb-1">Rs. {{ number_format($currentPlan->price, 0) }} / month</p>
          @if($currentSubscription->current_period_end)
            <p class="text-xs text-ink/40 mb-6">Renews {{ $currentSubscription->current_period_end->format('M j, Y') }}</p>
          @endif
          @if($currentSubscription->plan->hasLimit())
            <p class="text-sm text-ink/60 mb-6">
              Papers used this period: <span class="font-semibold text-ink">{{ $currentSubscription->attempts_used }}</span> / {{ $currentPlan->paper_limit }}
            </p>
          @endif
          <a href="{{ route('papers') }}" wire:navigate class="inline-flex items-center rounded-lg bg-teal text-paper font-semibold px-6 py-2.5 hover:bg-teal/90 transition">
            Browse Papers →
          </a>
        </div>
      @endif

      {{-- PLAN CARDS --}}
      <div class="text-center mb-8">
        <h1 class="font-display text-3xl text-ink mb-2">Choose your plan</h1>
        <p class="text-ink/60">All plans cover one exam level. Cancel anytime.</p>
      </div>

      <div class="grid md:grid-cols-3 gap-6 mb-10">
        @foreach($plans as $plan)
          @php
            $isCurrentPlan = $currentPlan && $currentPlan->id === $plan->id;
            $isPopular = $plan->slug === 'progress';
          @endphp
          <div class="rounded-2xl {{ $isPopular ? 'border-2 border-teal' : 'border border-ink/10' }} bg-white p-6 flex flex-col relative">
            @if($isPopular)
              <span class="absolute -top-3 left-6 bg-teal text-paper text-xs font-semibold rounded-full px-3 py-1">Most popular</span>
            @endif

            <h2 class="font-display text-lg text-ink mb-1">{{ $plan->name }}</h2>
            <p class="text-xs text-ink/50 mb-5">
              @if($plan->slug === 'practice') Steady weekly practice
              @elseif($plan->slug === 'progress') Serious exam prep
              @else Your final stretch before the exam
              @endif
            </p>

            <p class="mb-6">
              <span class="font-display text-3xl text-ink">Rs. {{ number_format($plan->price, 0) }}</span>
              <span class="text-ink/50 text-sm"> / month</span>
            </p>

            <div class="space-y-2.5 mb-8 flex-1 text-sm">
              @foreach($plan->features ?? [] as $feature)
                @if(str_starts_with($feature, 'Everything in'))
                  <p class="text-xs font-semibold text-ink/50 uppercase tracking-wide mb-1">{{ $feature }}</p>
                @else
                  <div class="flex items-start gap-2">
                    <svg class="w-4 h-4 mt-0.5 flex-shrink-0" fill="none" stroke="#3F7D6B" stroke-width="2.5" viewBox="0 0 24 24"><path d="M20 6L9 17l-5-5"/></svg>
                    {{ $feature }}
                  </div>
                @endif
              @endforeach
            </div>

            @if($isCurrentPlan)
              <span class="block text-center rounded-lg border border-teal/30 text-teal font-semibold py-2.5 cursor-default">Current plan</span>
            @elseif($currentSubscription && $currentSubscription->isActive())
              <button wire:click="subscribe({{ $plan->id }})"
                      x-on:click="loading = {{ $plan->id }}"
                      :disabled="loading"
                      class="block w-full text-center rounded-lg border border-ink/20 text-ink font-semibold py-2.5 hover:bg-ink hover:text-paper transition disabled:opacity-50">
                <span x-show="loading !== {{ $plan->id }}">Switch to {{ $plan->name }}</span>
                <span x-show="loading === {{ $plan->id }}" x-cloak>Redirecting…</span>
              </button>
            @else
              <button wire:click="subscribe({{ $plan->id }})"
                      x-on:click="loading = {{ $plan->id }}"
                      :disabled="loading"
                      class="block w-full text-center rounded-lg {{ $isPopular ? 'bg-teal text-paper hover:bg-teal/90' : 'border border-ink/20 text-ink hover:bg-ink hover:text-paper' }} font-semibold py-2.5 transition disabled:opacity-50">
                <span x-show="loading !== {{ $plan->id }}">Start {{ $plan->name }}</span>
                <span x-show="loading === {{ $plan->id }}" x-cloak>Redirecting…</span>
              </button>
            @endif
          </div>
        @endforeach
      </div>

      {{-- INFO --}}
      <div class="rounded-xl border border-ink/10 bg-white p-5 text-center">
        <div class="flex items-center justify-center gap-2 mb-2">
          <svg class="w-4 h-4 text-ink/50" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="4" y="11" width="16" height="9" rx="2"/><path d="M8 11V7a4 4 0 0 1 8 0v4"/></svg>
          <p class="text-sm font-medium text-ink">Payment processed securely by Lemon Squeezy</p>
        </div>
        <p class="text-xs text-ink/50 mb-3">You'll be taken to a secure payment page. Papelo never sees or stores your card details.</p>
        <div class="flex flex-wrap justify-center gap-2">
          <span class="text-[11px] font-semibold text-ink/50 border border-ink/15 rounded-md px-2 py-0.5">Visa</span>
          <span class="text-[11px] font-semibold text-ink/50 border border-ink/15 rounded-md px-2 py-0.5">Mastercard</span>
          <span class="text-[11px] font-semibold text-ink/50 border border-ink/15 rounded-md px-2 py-0.5">Apple Pay</span>
          <span class="text-[11px] font-semibold text-ink/50 border border-ink/15 rounded-md px-2 py-0.5">Google Pay</span>
        </div>
      </div>

      <p class="text-center text-xs text-ink/40 mt-4">By subscribing, you agree to Papelo's <a href="{{ route('terms') }}" class="underline">Terms</a> and <a href="{{ route('refund') }}" class="underline">Refund Policy</a>.</p>

    </main>
  </div>
</div>
