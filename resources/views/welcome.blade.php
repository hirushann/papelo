<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Papelooo') }} — Practice past papers. Get instant results.</title>
        <meta name="description" content="Papelooo is the ultimate online platform for Sri Lankan students to practice past exam papers as interactive MCQs with instant scoring and topic analytics.">

        <link rel="icon" href="{{ asset('images/papelooo-icon-tile.svg') }}" type="image/svg+xml">
        <link rel="canonical" href="{{ url()->current() }}">


        <!-- Open Graph / Facebook -->
        <meta property="og:type" content="website">
        <meta property="og:url" content="{{ url()->current() }}">
        <meta property="og:title" content="{{ config('app.name', 'Papelooo') }} — Practice past papers. Get instant results.">
        <meta property="og:description" content="Papelooo is the ultimate online platform for Sri Lankan students to practice past exam papers as interactive MCQs with instant scoring and topic analytics.">
        
        <!-- Twitter -->
        <meta property="twitter:card" content="summary_large_image">
        <meta property="twitter:url" content="{{ url()->current() }}">
        <meta property="twitter:title" content="{{ config('app.name', 'Papelooo') }} — Practice past papers. Get instant results.">
        <meta property="twitter:description" content="Papelooo is the ultimate online platform for Sri Lankan students to practice past exam papers as interactive MCQs with instant scoring and topic analytics.">

        <!-- Structured Data / SEO -->
        <script type="application/ld+json">
        {
          "@context": "https://schema.org",
          "@type": "WebSite",
          "name": "Papelooo",
          "alternateName": ["Papelooo", "Papelooo"],
          "url": "{{ config('app.url') }}",
          "description": "Papelooo is the ultimate online platform for Sri Lankan students to practice past exam papers as interactive MCQs with instant scoring and topic analytics."
        }
        </script>
        <script type="application/ld+json">
        {
          "@context": "https://schema.org",
          "@type": "Organization",
          "name": "Papelooo",
          "alternateName": ["Papelooo", "Papelooo"],
          "url": "{{ config('app.url') }}",
          "logo": "{{ asset('images/papelooo-icon-tile.svg') }}",
          "contactPoint": {
            "@type": "ContactPoint",
            "email": "contact@papelooo.com",
            "contactType": "customer support"
          }
        }
        </script>
        <script type="application/ld+json">
        {
          "@context": "https://schema.org",
          "@type": "SoftwareApplication",
          "name": "Papelooo",
          "applicationCategory": "EducationalApplication",
          "operatingSystem": "WebBrowser",
          "offers": [
            {
              "@type": "Offer",
              "name": "Practice Plan",
              "price": "490.00",
              "priceCurrency": "LKR",
              "description": "Up to 15 papers a month, instant MCQ marking, and per-attempt topic breakdowns."
            },
            {
              "@type": "Offer",
              "name": "Progress Plan",
              "price": "990.00",
              "priceCurrency": "LKR",
              "description": "Unlimited papers, full topic breakdowns, trends over time, and suggested next papers."
            },
            {
              "@type": "Offer",
              "name": "Pass Plan",
              "price": "1490.00",
              "priceCurrency": "LKR",
              "description": "Unlimited papers plus structured and self-marked papers with model solutions and downloadable progress reports."
            }
          ]
        }
        </script>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght,SOFT,WONK@9..144,300..700,0..100,0..1&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

        <!-- Styles -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="bg-paper text-ink antialiased font-sans">

        {{-- ── Top Navigation ────────────────────────────────── --}}
        <header class="sticky top-0 z-50 bg-paper/95 backdrop-blur-sm border-b border-ink/10">
            <div class="max-w-6xl mx-auto px-6 h-16 flex items-center justify-between">
                <a href="/" class="flex items-center gap-2">
                    <svg viewBox="636 340 1124 1112" class="w-8 h-8"><g transform="translate(0,1792) scale(0.1,-0.1)" fill="#3F7D6B" stroke="none"><path d="M10915 12730 c-134 -31 -324 -89 -350 -106 -37 -24 -69 -97 -62 -145 11 -83 70 -132 152 -126 48 3 314 72 402 103 112 40 125 193 23 265 -38 27 -78 29 -165 9z"/><path d="M9963 12361 c-527 -265 -1098 -815 -1443 -1391 -197 -327 -251 -478 -200 -558 27 -44 97 -75 145 -66 70 13 93 40 176 201 350 685 781 1145 1434 1531 134 79 155 103 155 177 0 62 -31 112 -84 136 -59 27 -73 24 -183 -30z"/><path d="M7926 9653 c-32 -111 -66 -499 -66 -748 0 -2274 1914 -4118 4157 -4005 1243 63 2383 718 3087 1775 159 238 363 620 382 713 l7 32 -306 0 -305 0 -11 -44 c-13 -51 -53 -90 -109 -105 -34 -10 -651 -18 -2872 -37 l-725 -7 -485 -163 c-453 -153 -520 -174 -520 -158 0 7 53 190 250 864 79 267 166 566 195 665 29 99 121 410 204 692 83 281 151 519 151 527 0 26 -3026 24 -3034 -1z m2020 -1544 c31 -15 48 -32 63 -63 38 -76 23 -132 -59 -223 -131 -148 -201 -387 -170 -586 61 -402 382 -680 785 -681 185 0 339 54 476 167 84 70 98 77 156 77 123 0 195 -139 124 -239 -57 -80 -289 -221 -441 -269 -154 -49 -366 -62 -515 -33 -654 128 -1049 793 -841 1415 81 240 248 448 365 455 8 1 34 -8 57 -20z"/></g><g transform="translate(0,1792) scale(0.1,-0.1)" fill="#22314A" stroke="none"><path d="M14353 13763 c-81 -86 -283 -296 -448 -467 -165 -171 -399 -415 -520 -541 -121 -126 -328 -342 -460 -480 -277 -288 -1054 -1102 -1166 -1220 l-76 -81 -41 -129 c-22 -72 -68 -222 -102 -335 -60 -197 -89 -296 -235 -790 -37 -124 -100 -337 -141 -475 -438 -1461 -559 -1873 -551 -1881 4 -4 608 198 1707 571 914 310 1521 516 1679 569 l184 61 386 405 c212 223 501 524 641 670 262 272 1644 1721 1738 1821 28 31 52 61 52 66 0 6 -165 170 -367 365 -433 417 -469 451 -481 446 -6 -1 -180 -181 -388 -398 -208 -217 -594 -620 -859 -895 -264 -275 -633 -660 -820 -855 -488 -511 -605 -630 -616 -630 -17 0 -690 663 -687 677 2 7 190 207 418 445 229 238 683 712 1010 1053 327 341 736 768 910 948 173 181 317 336 318 344 4 17 -174 203 -194 203 -6 0 -37 -28 -70 -62 -70 -75 -641 -670 -1014 -1058 -146 -151 -521 -543 -835 -870 -724 -757 -757 -790 -770 -790 -15 0 -405 379 -405 394 0 6 86 101 192 211 106 110 359 373 563 585 204 212 523 545 710 740 186 195 402 420 480 500 286 295 715 749 715 757 0 16 -275 283 -292 283 -9 0 -83 -71 -165 -157z m-1982 -3565 c1075 -1041 1370 -1331 1367 -1345 -2 -12 -1034 -381 -1315 -470 l-52 -16 -338 329 c-186 181 -371 361 -412 400 l-73 70 22 75 c71 243 380 1269 389 1292 6 15 17 27 25 27 7 0 182 -163 387 -362z m-790 -1470 c60 -56 109 -105 109 -110 0 -9 -23 -39 -298 -395 -140 -183 -192 -239 -192 -205 0 4 13 50 29 102 228 749 215 710 232 710 6 0 60 -46 120 -102z"/><path d="M11547 13859 c-2238 -122 -4144 -1844 -4521 -4084 -53 -316 -60 -422 -61 -840 0 -419 9 -552 61 -854 202 -1198 843 -2272 1814 -3046 1045 -833 2369 -1188 3705 -994 1800 261 3370 1617 3920 3386 74 237 169 653 152 669 -7 8 -451 -78 -463 -90 -6 -6 -30 -89 -53 -185 -260 -1064 -933 -2018 -1863 -2639 -1509 -1008 -3451 -978 -4929 76 -1064 759 -1735 1911 -1881 3227 -20 183 -17 730 6 920 143 1222 712 2265 1657 3038 746 611 1659 942 2654 964 517 11 923 -47 1444 -207 73 -23 138 -40 145 -38 6 2 80 74 164 160 150 155 172 188 135 203 -654 259 -1367 373 -2086 334z"/><path d="M11885 13054 c-43 -22 -60 -40 -79 -82 -20 -45 -516 -2036 -516 -2071 0 -42 34 11 84 132 33 78 92 219 131 312 617 1457 645 1531 614 1612 -33 88 -155 138 -234 97z"/><path d="M16755 10170 c-35 -17 -1165 -860 -1683 -1254 -167 -127 -216 -177 -162 -163 12 3 208 106 438 230 372 200 951 511 1377 739 265 142 285 162 285 275 0 141 -135 233 -255 173z"/><path d="M16085 8665 c-176 -57 -603 -194 -950 -304 -346 -110 -749 -238 -895 -285 -146 -47 -354 -114 -462 -148 -125 -40 -198 -68 -198 -76 0 -10 8 -12 28 -8 29 7 28 6 1157 221 1976 377 1777 335 1826 384 90 90 67 252 -44 304 -67 32 -120 22 -462 -88z"/><path d="M9841 8118 c-41 -11 -132 -101 -189 -185 -250 -369 -243 -871 19 -1231 366 -502 1049 -607 1541 -237 134 101 161 156 120 248 -26 59 -70 87 -135 87 -58 0 -72 -7 -156 -77 -225 -186 -544 -222 -817 -93 -459 219 -599 828 -274 1193 82 91 97 147 59 223 -31 66 -95 93 -168 72z"/><path d="M13055 7545 l-960 -12 -417 -138 c-299 -99 -418 -143 -418 -153 0 -13 81 -14 648 -9 356 4 1135 12 1732 17 654 7 1100 15 1122 21 118 31 153 178 62 259 l-35 30 -387 -2 c-213 0 -819 -6 -1347 -13z"/></g></svg>
                    <span class="font-display text-2xl text-ink">Papelooo</span>
                </a>
                <nav class="hidden md:flex items-center gap-8 text-sm font-medium text-ink/80">
                    <a href="#how" class="hover:text-ink">How it works</a>
                    <a href="{{ route('papers') }}" class="hover:text-ink">Papers</a>
                    <a href="#pricing" class="hover:text-ink">Pricing</a>
                    <a href="{{ route('about') }}" class="hover:text-ink">About</a>
                </nav>
                <div class="flex items-center gap-3">
                    @auth
                        <a href="{{ route('dashboard') }}" class="inline-flex items-center rounded-lg bg-teal text-paper text-sm font-semibold px-4 py-2 hover:bg-teal/90 transition">Dashboard</a>
                    @else
                        <a href="{{ route('login') }}" class="hidden sm:inline text-sm font-medium text-ink/80 hover:text-ink">Log in</a>
                        <a href="{{ route('register') }}" class="inline-flex items-center rounded-lg bg-teal text-paper text-sm font-semibold px-4 py-2 hover:bg-teal/90 transition">Get Started</a>
                    @endauth
                </div>
            </div>
        </header>

        {{-- ── Hero ──────────────────────────────────────────── --}}
        <section class="bg-examsheet-hero">
            <div class="max-w-6xl mx-auto px-6 py-20 grid md:grid-cols-2 gap-12 items-center">
                <div>
                    <span class="inline-block text-xs font-semibold tracking-wide uppercase text-teal bg-teal/10 rounded-full px-3 py-1 mb-5">For Grade 5 Scholarship · O/L · A/L students</span>
                    <h1 class="text-4xl sm:text-5xl leading-[1.1] text-ink mb-5">Practice like it's exam day. Know your marks before you leave your desk.</h1>
                    <p class="text-lg text-ink/70 mb-8 max-w-md">Hundreds of real past papers, auto-marked the moment you finish — with a breakdown of exactly which topics still need work.</p>
                    <div class="flex flex-wrap items-center gap-4 mb-6">
                        <a href="#pricing" class="inline-flex items-center rounded-lg bg-teal text-paper font-semibold px-6 py-3 hover:bg-teal/90 transition">Start practicing from Rs. 490/mo</a>
                        <a href="#how" class="inline-flex items-center text-ink font-semibold px-2 py-3 hover:text-teal transition">See how it works →</a>
                    </div>
                    <p class="text-sm text-ink/50">500+ past papers · Instant results · No sign-up to browse</p>
                </div>

                <div class="relative">
                    <div class="bg-white rounded-2xl border border-ink/10 shadow-xl shadow-ink/5 p-6">
                        <div class="flex items-center justify-between mb-4 text-xs font-medium text-ink/50">
                            <span>2019 O/L · Science</span>
                            <span>Question 14</span>
                        </div>
                        <p class="font-semibold text-ink mb-4">Which of the following is <span class="text-margin">NOT</span> a renewable energy source?</p>
                        <div class="space-y-2.5">
                            <div class="flex items-center gap-3 rounded-lg border border-ink/10 px-3 py-2.5 text-sm text-ink/80">
                                <span class="w-5 h-5 rounded-full border-2 border-ink/25 flex-shrink-0"></span> Solar power
                            </div>
                            <div class="flex items-center gap-3 rounded-lg border border-ink/10 px-3 py-2.5 text-sm text-ink/80">
                                <span class="w-5 h-5 rounded-full border-2 border-ink/25 flex-shrink-0"></span> Wind power
                            </div>
                            <div class="flex items-center gap-3 rounded-lg border-2 border-teal bg-teal/10 px-3 py-2.5 text-sm font-semibold text-ink">
                                <span class="w-5 h-5 rounded-full bg-teal flex-shrink-0"></span> Natural gas
                            </div>
                            <div class="flex items-center gap-3 rounded-lg border border-ink/10 px-3 py-2.5 text-sm text-ink/80">
                                <span class="w-5 h-5 rounded-full border-2 border-ink/25 flex-shrink-0"></span> Hydropower
                            </div>
                        </div>
                    </div>

                    <div class="float absolute -bottom-8 -right-8 bg-ink text-paper rounded-xl shadow-xl p-5 w-48">
                        <p class="text-xs text-paper/60 mb-1">Your score</p>
                        <p class="font-display text-3xl mb-1">92%</p>
                        <p class="text-xs text-paper/60">18 / 20 correct</p>
                    </div>
                </div>
            </div>
        </section>

        {{-- ── Stats strip ───────────────────────────────────── --}}
        <section class="border-y border-ink/10">
            <div class="max-w-6xl mx-auto px-6 py-10 grid grid-cols-2 md:grid-cols-4 gap-8 text-center">
                <div><p class="font-display text-3xl text-teal">500+</p><p class="text-sm text-ink/60 mt-1">Past papers</p></div>
                <div><p class="font-display text-3xl text-teal">3</p><p class="text-sm text-ink/60 mt-1">Exam levels</p></div>
                <div><p class="font-display text-3xl text-teal">&lt;1s</p><p class="text-sm text-ink/60 mt-1">Auto-marking</p></div>
                <div><p class="font-display text-3xl text-teal">12</p><p class="text-sm text-ink/60 mt-1">Subjects</p></div>
            </div>
        </section>

        {{-- ── How it works ──────────────────────────────────── --}}
        <section id="how" class="max-w-6xl mx-auto px-6 py-24">
            <h2 class="font-display text-3xl text-ink text-center mb-16">Three steps to your result</h2>
            <div class="grid md:grid-cols-3 gap-10">
                <div>
                    <div class="w-10 h-10 rounded-full bg-teal text-paper font-display flex items-center justify-center mb-4">1</div>
                    <h3 class="font-display text-xl text-ink mb-2">Pick your paper</h3>
                    <p class="text-ink/60 text-sm leading-relaxed">Choose your exam level, year, and subject from the library.</p>
                </div>
                <div>
                    <div class="w-10 h-10 rounded-full bg-teal text-paper font-display flex items-center justify-center mb-4">2</div>
                    <h3 class="font-display text-xl text-ink mb-2">Sit it like the real thing</h3>
                    <p class="text-ink/60 text-sm leading-relaxed">Answer the MCQs the same way you would on exam day, timed.</p>
                </div>
                <div>
                    <div class="w-10 h-10 rounded-full bg-teal text-paper font-display flex items-center justify-center mb-4">3</div>
                    <h3 class="font-display text-xl text-ink mb-2">Get marked instantly</h3>
                    <p class="text-ink/60 text-sm leading-relaxed">See your score and a topic-by-topic breakdown the second you finish.</p>
                </div>
            </div>
        </section>

        {{-- ── Analytics feature ─────────────────────────────── --}}
        <section class="bg-examsheet-ambient">
            <div class="max-w-6xl mx-auto px-6 py-24 grid md:grid-cols-2 gap-14 items-center">
                <div>
                    <h2 class="font-display text-3xl text-ink mb-5">Know exactly what to revise next.</h2>
                    <p class="text-ink/70 mb-6 leading-relaxed">Every attempt feeds your topic report, so you stop guessing what to study and start closing the actual gaps.</p>
                    <ul class="space-y-3 text-sm text-ink/80">
                        <li class="flex items-start gap-3"><span class="w-1.5 h-1.5 rounded-full bg-teal mt-2 flex-shrink-0"></span> Topic-by-topic score breakdown</li>
                        <li class="flex items-start gap-3"><span class="w-1.5 h-1.5 rounded-full bg-teal mt-2 flex-shrink-0"></span> Track improvement across attempts</li>
                        <li class="flex items-start gap-3"><span class="w-1.5 h-1.5 rounded-full bg-teal mt-2 flex-shrink-0"></span> See exactly where you sit against the pass mark</li>
                    </ul>
                </div>
                <div class="bg-white rounded-2xl border border-ink/10 shadow-lg shadow-ink/5 p-6">
                    <p class="text-xs font-medium text-ink/50 mb-5">Science — topic breakdown</p>
                    <div class="space-y-4">
                        <div>
                            <div class="flex justify-between text-xs mb-1"><span class="text-ink/70">Cells &amp; Genetics</span><span class="font-semibold text-teal">88%</span></div>
                            <div class="h-2 rounded-full bg-ink/10"><div class="h-2 rounded-full bg-teal" style="width:88%"></div></div>
                        </div>
                        <div>
                            <div class="flex justify-between text-xs mb-1"><span class="text-ink/70">Forces &amp; Motion</span><span class="font-semibold text-teal">74%</span></div>
                            <div class="h-2 rounded-full bg-ink/10"><div class="h-2 rounded-full bg-teal" style="width:74%"></div></div>
                        </div>
                        <div>
                            <div class="flex justify-between text-xs mb-1"><span class="text-ink/70">Ecosystems</span><span class="font-semibold text-margin">52%</span></div>
                            <div class="h-2 rounded-full bg-ink/10"><div class="h-2 rounded-full bg-margin" style="width:52%"></div></div>
                        </div>
                        <div>
                            <div class="flex justify-between text-xs mb-1"><span class="text-ink/70">Energy &amp; Matter</span><span class="font-semibold text-margin">41%</span></div>
                            <div class="h-2 rounded-full bg-ink/10"><div class="h-2 rounded-full bg-margin" style="width:41%"></div></div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        {{-- ── Subjects / levels ─────────────────────────────── --}}
        <section id="papers" class="max-w-6xl mx-auto px-6 py-24">
            <h2 class="font-display text-3xl text-ink text-center mb-4">Every level, covered</h2>
            <p class="text-ink/60 text-center mb-14 max-w-lg mx-auto">From the Grade 5 Scholarship exam through to A/L, across every major stream.</p>
            <div class="grid md:grid-cols-3 gap-6">
                <div class="rounded-2xl border border-ink/10 p-7 bg-white">
                    <h3 class="font-display text-xl text-ink mb-3">Grade 5 Scholarship</h3>
                    <p class="text-sm text-ink/60 mb-4">Full-syllabus practice papers covering all three subject areas.</p>
                    <div class="flex flex-wrap gap-2">
                        <span class="text-xs bg-paper border border-ink/10 rounded-full px-3 py-1">Maths</span>
                        <span class="text-xs bg-paper border border-ink/10 rounded-full px-3 py-1">First Language</span>
                        <span class="text-xs bg-paper border border-ink/10 rounded-full px-3 py-1">Environment</span>
                    </div>
                </div>
                <div class="rounded-2xl border-2 border-teal p-7 bg-white relative">
                    <span class="absolute -top-3 left-7 bg-teal text-paper text-xs font-semibold rounded-full px-3 py-1">Most popular</span>
                    <h3 class="font-display text-xl text-ink mb-3">O/L</h3>
                    <p class="text-sm text-ink/60 mb-4">Past papers across every core and elective O/L subject.</p>
                    <div class="flex flex-wrap gap-2">
                        <span class="text-xs bg-paper border border-ink/10 rounded-full px-3 py-1">Science</span>
                        <span class="text-xs bg-paper border border-ink/10 rounded-full px-3 py-1">Maths</span>
                        <span class="text-xs bg-paper border border-ink/10 rounded-full px-3 py-1">English</span>
                        <span class="text-xs bg-paper border border-ink/10 rounded-full px-3 py-1">Sinhala</span>
                        <span class="text-xs bg-paper border border-ink/10 rounded-full px-3 py-1">Commerce</span>
                        <span class="text-xs bg-paper border border-ink/10 rounded-full px-3 py-1">History</span>
                    </div>
                </div>
                <div class="rounded-2xl border border-ink/10 p-7 bg-white">
                    <h3 class="font-display text-xl text-ink mb-3">A/L</h3>
                    <p class="text-sm text-ink/60 mb-4">Stream-specific papers for every major A/L pathway.</p>
                    <div class="flex flex-wrap gap-2">
                        <span class="text-xs bg-paper border border-ink/10 rounded-full px-3 py-1">Physical Science</span>
                        <span class="text-xs bg-paper border border-ink/10 rounded-full px-3 py-1">Biological Science</span>
                        <span class="text-xs bg-paper border border-ink/10 rounded-full px-3 py-1">Commerce</span>
                        <span class="text-xs bg-paper border border-ink/10 rounded-full px-3 py-1">Arts</span>
                    </div>
                </div>
            </div>
        </section>

        {{-- ── Pricing ────────────────────────────────────────── --}}
        <section id="pricing" class="max-w-6xl mx-auto px-6 py-24">
            <h2 class="font-display text-3xl text-ink text-center mb-14">Simple, exam-friendly pricing</h2>
            <div class="grid md:grid-cols-3 gap-6">

                <!-- PRACTICE -->
                <div class="rounded-2xl border border-ink/10 bg-white p-6 flex flex-col">
                    <h3 class="font-display text-lg text-ink mb-1">Practice</h3>
                    <p class="text-xs text-ink/50 mb-5">Steady weekly practice</p>
                    <p class="mb-6"><span class="font-display text-3xl text-ink">Rs. 490</span><span class="text-ink/50 text-sm"> / month</span></p>
                    <div class="space-y-2.5 mb-8 flex-1 text-sm">
                        <div class="flex items-start gap-2"><svg class="w-4 h-4 mt-0.5 flex-shrink-0" fill="none" stroke="#3F7D6B" stroke-width="2.5" viewBox="0 0 24 24"><path d="M20 6L9 17l-5-5"/></svg>Up to 15 new papers a month</div>
                        <div class="flex items-start gap-2"><svg class="w-4 h-4 mt-0.5 flex-shrink-0" fill="none" stroke="#3F7D6B" stroke-width="2.5" viewBox="0 0 24 24"><path d="M20 6L9 17l-5-5"/></svg>Instant MCQ &amp; short-answer marking</div>
                        <div class="flex items-start gap-2"><svg class="w-4 h-4 mt-0.5 flex-shrink-0" fill="none" stroke="#3F7D6B" stroke-width="2.5" viewBox="0 0 24 24"><path d="M20 6L9 17l-5-5"/></svg>Full attempt history</div>
                        <div class="flex items-start gap-2"><svg class="w-4 h-4 mt-0.5 flex-shrink-0" fill="none" stroke="#3F7D6B" stroke-width="2.5" viewBox="0 0 24 24"><path d="M20 6L9 17l-5-5"/></svg>Per-attempt topic breakdown</div>
                    </div>
                    <a href="{{ route('register') }}" class="block text-center rounded-lg border border-ink/20 text-ink font-semibold py-2.5 hover:bg-ink hover:text-paper transition">Start Practice</a>
                </div>

                <!-- PROGRESS -->
                <div class="rounded-2xl border-2 border-teal bg-white p-6 flex flex-col relative">
                    <span class="absolute -top-3 left-6 bg-teal text-paper text-xs font-semibold rounded-full px-3 py-1">Most popular</span>
                    <h3 class="font-display text-lg text-ink mb-1">Progress</h3>
                    <p class="text-xs text-ink/50 mb-5">Serious exam prep</p>
                    <p class="mb-6"><span class="font-display text-3xl text-ink">Rs. 990</span><span class="text-ink/50 text-sm"> / month</span></p>
                    <div class="space-y-2.5 mb-8 flex-1 text-sm">
                        <p class="text-xs font-semibold text-ink/50 uppercase tracking-wide mb-1">Everything in Practice, plus:</p>
                        <div class="flex items-start gap-2"><svg class="w-4 h-4 mt-0.5 flex-shrink-0" fill="none" stroke="#3F7D6B" stroke-width="2.5" viewBox="0 0 24 24"><path d="M20 6L9 17l-5-5"/></svg>Unlimited papers</div>
                        <div class="flex items-start gap-2"><svg class="w-4 h-4 mt-0.5 flex-shrink-0" fill="none" stroke="#3F7D6B" stroke-width="2.5" viewBox="0 0 24 24"><path d="M20 6L9 17l-5-5"/></svg>Full topic breakdown &amp; trends over time</div>
                        <div class="flex items-start gap-2"><svg class="w-4 h-4 mt-0.5 flex-shrink-0" fill="none" stroke="#3F7D6B" stroke-width="2.5" viewBox="0 0 24 24"><path d="M20 6L9 17l-5-5"/></svg>Suggested next paper</div>
                        <div class="flex items-start gap-2"><svg class="w-4 h-4 mt-0.5 flex-shrink-0" fill="none" stroke="#3F7D6B" stroke-width="2.5" viewBox="0 0 24 24"><path d="M20 6L9 17l-5-5"/></svg>Priority access to new papers</div>
                    </div>
                    <a href="{{ route('register') }}" class="block text-center rounded-lg bg-teal text-paper font-semibold py-2.5 hover:bg-teal/90 transition">Start Progress</a>
                </div>

                <!-- PASS -->
                <div class="rounded-2xl border border-ink/10 bg-white p-6 flex flex-col">
                    <h3 class="font-display text-lg text-ink mb-1">Pass</h3>
                    <p class="text-xs text-ink/50 mb-5">Your final stretch before the exam</p>
                    <p class="mb-6"><span class="font-display text-3xl text-ink">Rs. 1,490</span><span class="text-ink/50 text-sm"> / month</span></p>
                    <div class="space-y-2.5 mb-8 flex-1 text-sm">
                        <p class="text-xs font-semibold text-ink/50 uppercase tracking-wide mb-1">Everything in Progress, plus:</p>
                        <div class="flex items-start gap-2"><svg class="w-4 h-4 mt-0.5 flex-shrink-0" fill="none" stroke="#3F7D6B" stroke-width="2.5" viewBox="0 0 24 24"><path d="M20 6L9 17l-5-5"/></svg><div>Structured &amp; self-marked papers <span class="text-ink/40">(proofs, constructions)</span></div></div>
                        <div class="flex items-start gap-2"><svg class="w-4 h-4 mt-0.5 flex-shrink-0" fill="none" stroke="#3F7D6B" stroke-width="2.5" viewBox="0 0 24 24"><path d="M20 6L9 17l-5-5"/></svg>Downloadable progress report</div>
                    </div>
                    <a href="{{ route('register') }}" class="block text-center rounded-lg border border-ink/20 text-ink font-semibold py-2.5 hover:bg-ink hover:text-paper transition">Start Pass</a>
                </div>

            </div>
            
            <div class="text-center mt-12">
                <a href="{{ route('pricing') }}" class="text-sm font-semibold text-ink hover:text-teal underline underline-offset-4">See full pricing details &amp; FAQ</a>
            </div>
        </section>

        {{-- ── CTA ────────────────────────────────────────────── --}}
        <section class="bg-ink">
            <div class="max-w-6xl mx-auto px-6 py-20 text-center">
                <h2 class="font-display text-3xl sm:text-4xl text-paper mb-6">Your next test starts now.</h2>
                @auth
                    <a href="{{ route('papers') }}" class="inline-flex items-center rounded-lg bg-teal text-paper font-semibold px-8 py-3.5 hover:bg-teal/90 transition">Browse past papers</a>
                @else
                    <a href="{{ route('register') }}" class="inline-flex items-center rounded-lg bg-teal text-paper font-semibold px-8 py-3.5 hover:bg-teal/90 transition">Create free account</a>
                @endauth
            </div>
        </section>

        {{-- ── Footer ─────────────────────────────────────────── --}}
        <footer class="border-t-2 border-margin">
            <div class="max-w-6xl mx-auto px-6 py-14 grid sm:grid-cols-2 md:grid-cols-4 gap-10">
                <div>
                    <div class="flex items-center gap-2 mb-3">
                        <svg viewBox="636 340 1124 1112" class="w-6 h-6"><g transform="translate(0,1792) scale(0.1,-0.1)" fill="#3F7D6B" stroke="none"><path d="M10915 12730 c-134 -31 -324 -89 -350 -106 -37 -24 -69 -97 -62 -145 11 -83 70 -132 152 -126 48 3 314 72 402 103 112 40 125 193 23 265 -38 27 -78 29 -165 9z"/><path d="M9963 12361 c-527 -265 -1098 -815 -1443 -1391 -197 -327 -251 -478 -200 -558 27 -44 97 -75 145 -66 70 13 93 40 176 201 350 685 781 1145 1434 1531 134 79 155 103 155 177 0 62 -31 112 -84 136 -59 27 -73 24 -183 -30z"/><path d="M7926 9653 c-32 -111 -66 -499 -66 -748 0 -2274 1914 -4118 4157 -4005 1243 63 2383 718 3087 1775 159 238 363 620 382 713 l7 32 -306 0 -305 0 -11 -44 c-13 -51 -53 -90 -109 -105 -34 -10 -651 -18 -2872 -37 l-725 -7 -485 -163 c-453 -153 -520 -174 -520 -158 0 7 53 190 250 864 79 267 166 566 195 665 29 99 121 410 204 692 83 281 151 519 151 527 0 26 -3026 24 -3034 -1z m2020 -1544 c31 -15 48 -32 63 -63 38 -76 23 -132 -59 -223 -131 -148 -201 -387 -170 -586 61 -402 382 -680 785 -681 185 0 339 54 476 167 84 70 98 77 156 77 123 0 195 -139 124 -239 -57 -80 -289 -221 -441 -269 -154 -49 -366 -62 -515 -33 -654 128 -1049 793 -841 1415 81 240 248 448 365 455 8 1 34 -8 57 -20z"/></g><g transform="translate(0,1792) scale(0.1,-0.1)" fill="#22314A" stroke="none"><path d="M14353 13763 c-81 -86 -283 -296 -448 -467 -165 -171 -399 -415 -520 -541 -121 -126 -328 -342 -460 -480 -277 -288 -1054 -1102 -1166 -1220 l-76 -81 -41 -129 c-22 -72 -68 -222 -102 -335 -60 -197 -89 -296 -235 -790 -37 -124 -100 -337 -141 -475 -438 -1461 -559 -1873 -551 -1881 4 -4 608 198 1707 571 914 310 1521 516 1679 569 l184 61 386 405 c212 223 501 524 641 670 262 272 1644 1721 1738 1821 28 31 52 61 52 66 0 6 -165 170 -367 365 -433 417 -469 451 -481 446 -6 -1 -180 -181 -388 -398 -208 -217 -594 -620 -859 -895 -264 -275 -633 -660 -820 -855 -488 -511 -605 -630 -616 -630 -17 0 -690 663 -687 677 2 7 190 207 418 445 229 238 683 712 1010 1053 327 341 736 768 910 948 173 181 317 336 318 344 4 17 -174 203 -194 203 -6 0 -37 -28 -70 -62 -70 -75 -641 -670 -1014 -1058 -146 -151 -521 -543 -835 -870 -724 -757 -757 -790 -770 -790 -15 0 -405 379 -405 394 0 6 86 101 192 211 106 110 359 373 563 585 204 212 523 545 710 740 186 195 402 420 480 500 286 295 715 749 715 757 0 16 -275 283 -292 283 -9 0 -83 -71 -165 -157z m-1982 -3565 c1075 -1041 1370 -1331 1367 -1345 -2 -12 -1034 -381 -1315 -470 l-52 -16 -338 329 c-186 181 -371 361 -412 400 l-73 70 22 75 c71 243 380 1269 389 1292 6 15 17 27 25 27 7 0 182 -163 387 -362z m-790 -1470 c60 -56 109 -105 109 -110 0 -9 -23 -39 -298 -395 -140 -183 -192 -239 -192 -205 0 4 13 50 29 102 228 749 215 710 232 710 6 0 60 -46 120 -102z"/><path d="M11547 13859 c-2238 -122 -4144 -1844 -4521 -4084 -53 -316 -60 -422 -61 -840 0 -419 9 -552 61 -854 202 -1198 843 -2272 1814 -3046 1045 -833 2369 -1188 3705 -994 1800 261 3370 1617 3920 3386 74 237 169 653 152 669 -7 8 -451 -78 -463 -90 -6 -6 -30 -89 -53 -185 -260 -1064 -933 -2018 -1863 -2639 -1509 -1008 -3451 -978 -4929 76 -1064 759 -1735 1911 -1881 3227 -20 183 -17 730 6 920 143 1222 712 2265 1657 3038 746 611 1659 942 2654 964 517 11 923 -47 1444 -207 73 -23 138 -40 145 -38 6 2 80 74 164 160 150 155 172 188 135 203 -654 259 -1367 373 -2086 334z"/><path d="M11885 13054 c-43 -22 -60 -40 -79 -82 -20 -45 -516 -2036 -516 -2071 0 -42 34 11 84 132 33 78 92 219 131 312 617 1457 645 1531 614 1612 -33 88 -155 138 -234 97z"/><path d="M16755 10170 c-35 -17 -1165 -860 -1683 -1254 -167 -127 -216 -177 -162 -163 12 3 208 106 438 230 372 200 951 511 1377 739 265 142 285 162 285 275 0 141 -135 233 -255 173z"/><path d="M16085 8665 c-176 -57 -603 -194 -950 -304 -346 -110 -749 -238 -895 -285 -146 -47 -354 -114 -462 -148 -125 -40 -198 -68 -198 -76 0 -10 8 -12 28 -8 29 7 28 6 1157 221 1976 377 1777 335 1826 384 90 90 67 252 -44 304 -67 32 -120 22 -462 -88z"/><path d="M9841 8118 c-41 -11 -132 -101 -189 -185 -250 -369 -243 -871 19 -1231 366 -502 1049 -607 1541 -237 134 101 161 156 120 248 -26 59 -70 87 -135 87 -58 0 -72 -7 -156 -77 -225 -186 -544 -222 -817 -93 -459 219 -599 828 -274 1193 82 91 97 147 59 223 -31 66 -95 93 -168 72z"/><path d="M13055 7545 l-960 -12 -417 -138 c-299 -99 -418 -143 -418 -153 0 -13 81 -14 648 -9 356 4 1135 12 1732 17 654 7 1100 15 1122 21 118 31 153 178 62 259 l-35 30 -387 -2 c-213 0 -819 -6 -1347 -13z"/></g></svg>
                        <span class="font-display text-lg text-ink">Papelooo</span>
                    </div>
                    <p class="text-sm text-ink/50">Past papers, marked instantly, for Sri Lankan students.</p>
                </div>
                <div>
                    <p class="text-xs font-semibold uppercase tracking-wide text-ink/40 mb-3">Product</p>
                    <ul class="space-y-2 text-sm text-ink/70">
                        <li><a href="#papers" class="hover:text-ink">Past Papers</a></li>
                        <li><a href="#pricing" class="hover:text-ink">Pricing</a></li>
                        <li><a href="#how" class="hover:text-ink">How it works</a></li>
                    </ul>
                </div>
                <div>
                    <p class="text-xs font-semibold uppercase tracking-wide text-ink/40 mb-3">Company</p>
                    <ul class="space-y-2 text-sm text-ink/70">
                        <li><a href="{{ route('about') }}" class="hover:text-ink">About</a></li>
                        <li><a href="{{ route('contact') }}" class="hover:text-ink">Contact</a></li>
                    </ul>
                </div>
                <div>
                    <p class="text-xs font-semibold uppercase tracking-wide text-ink/40 mb-3">Legal</p>
                    <ul class="space-y-2 text-sm text-ink/70">
                        <li><a href="{{ route('terms') }}" class="hover:text-ink">Terms</a></li>
                        <li><a href="{{ route('privacy') }}" class="hover:text-ink">Privacy</a></li>
                        <li><a href="{{ route('refund') }}" class="hover:text-ink">Refund Policy</a></li>
                    </ul>
                </div>
            </div>
            <div class="border-t border-ink/10 py-6 text-center text-xs text-ink/40">© {{ date('Y') }} Papelooo. Made for Sri Lankan students.</div>
        </footer>

    </body>
</html>
