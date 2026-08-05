<div>
  <x-slot name="customHeader">
    <header class="h-16 bg-white border-b border-ink/10 flex items-center justify-between px-8 flex-shrink-0">
      <h1 class="font-display text-xl text-ink">Settings</h1>
      <button wire:click="saveSettings" class="inline-flex items-center rounded-lg bg-teal text-paper text-sm font-semibold px-4 py-2 hover:bg-teal/90 transition">Save changes</button>
    </header>
  </x-slot>

  <style>
    .subnav-item { display:block; font-size:0.85rem; font-weight:500; color:rgba(34,49,74,0.55); padding:0.5rem 0.75rem; border-radius:0.5rem; }
    .subnav-item:hover { color:#22314A; background:rgba(34,49,74,0.04); }
    .subnav-item.active { color:#22314A; background:rgba(63,125,107,0.1); font-weight:600; }
    .field label { display:block; font-size:0.8rem; font-weight:600; color:rgba(34,49,74,0.75); margin-bottom:0.35rem; }
    .field p.hint { font-size:0.75rem; color:rgba(34,49,74,0.45); margin-top:0.35rem; }
    .field input, .field select {
      width:100%; border:1px solid rgba(34,49,74,0.15); border-radius:0.5rem; padding:0.6rem 0.85rem;
      font-size:0.875rem; color:#22314A; background:#fff;
    }
    .field input:focus, .field select:focus { outline:none; border-color:#3F7D6B; box-shadow:0 0 0 3px rgba(63,125,107,0.15); }
    .switch { position:relative; width:40px; height:22px; flex-shrink:0; }
    .switch input { opacity:0; width:0; height:0; }
    .switch .track { position:absolute; inset:0; background:rgba(34,49,74,0.15); border-radius:9999px; cursor:pointer; transition:.15s; }
    .switch .track::before { content:''; position:absolute; width:16px; height:16px; left:3px; top:3px; background:#fff; border-radius:50%; transition:.15s; }
    .switch input:checked + .track { background:#3F7D6B; }
    .switch input:checked + .track::before { transform:translateX(18px); }
    .settings-card { background:#fff; border:1px solid rgba(34,49,74,0.1); border-radius:1rem; }
  </style>

  <div class="grid lg:grid-cols-[200px_1fr] gap-10 max-w-4xl mx-auto">
    
    <!-- SUB NAV -->
    <nav class="space-y-1 sticky top-8 self-start hidden lg:block" x-data="{ activeSection: 'general' }" @scroll.window="
        const sections = ['general', 'payment', 'pricing', 'notifications', 'team', 'danger'];
        let current = 'general';
        for (const section of sections) {
            const el = document.getElementById(section);
            if (el && el.getBoundingClientRect().top <= 100) {
                current = section;
            }
        }
        activeSection = current;
    ">
      <a href="#general" :class="{'active': activeSection === 'general'}" class="subnav-item">General</a>
      <a href="#payment" :class="{'active': activeSection === 'payment'}" class="subnav-item">Payment</a>
      <a href="#pricing" :class="{'active': activeSection === 'pricing'}" class="subnav-item">Pricing</a>
      <a href="#notifications" :class="{'active': activeSection === 'notifications'}" class="subnav-item">Notifications</a>
      <a href="#team" :class="{'active': activeSection === 'team'}" class="subnav-item">Admin team</a>
      <a href="#danger" :class="{'active': activeSection === 'danger'}" class="subnav-item">Danger zone</a>
    </nav>

    <!-- SECTIONS -->
    <div class="space-y-8 pb-12">

      @if($successMessage)
        <div class="rounded-lg bg-teal/10 border border-teal/20 p-4 flex items-start gap-3">
          <svg class="w-5 h-5 text-teal shrink-0 mt-0.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M20 6 9 17l-5-5"/></svg>
          <p class="text-sm font-medium text-teal">{{ $successMessage }}</p>
        </div>
      @endif

      <!-- GENERAL -->
      <section id="general" class="settings-card p-6 scroll-mt-24">
        <h2 class="font-display text-lg text-ink mb-1">General</h2>
        <p class="text-xs text-ink/50 mb-6">Basic platform details.</p>
        <div class="space-y-4">
          <div class="field">
            <label>Platform name</label>
            <input type="text" wire:model="platformName">
          </div>
          <div class="field">
            <label>Support email</label>
            <input type="email" wire:model="supportEmail">
          </div>
          <div class="grid grid-cols-2 gap-4">
            <div class="field">
              <label>Timezone</label>
              <select wire:model="timezone">
                <option>Asia/Colombo (GMT+5:30)</option>
              </select>
            </div>
            <div class="field">
              <label>Currency</label>
              <select wire:model="currency">
                <option>LKR — Sri Lankan Rupee</option>
              </select>
            </div>
          </div>
        </div>
      </section>

      <!-- PAYMENT -->
      <section id="payment" class="settings-card p-6 scroll-mt-24">
        <h2 class="font-display text-lg text-ink mb-1">Payment — PayHere</h2>
        <p class="text-xs text-ink/50 mb-6">Credentials from your PayHere merchant dashboard.</p>
        <div class="space-y-4">
          <div class="flex items-center justify-between rounded-lg bg-paper/60 border border-ink/10 px-4 py-3 mb-2">
            <div>
              <p class="text-sm font-semibold text-ink">Mode</p>
              <p class="text-xs text-ink/50">Sandbox uses PayHere's test environment — no real charges</p>
            </div>
            <select wire:model="payhereMode" class="text-sm border border-ink/15 rounded-lg px-3 py-1.5 bg-white focus:outline-none focus:border-teal focus:ring-1 focus:ring-teal">
              <option>Sandbox</option>
              <option>Live</option>
            </select>
          </div>
          <div class="field">
            <label>Merchant ID</label>
            <input type="text" wire:model="merchantId">
          </div>
          <div class="field">
            <label>Merchant secret</label>
            <input type="password" wire:model="merchantSecret">
            <p class="hint">Never shown in full after saving. Re-enter to change it.</p>
          </div>
          <div class="field">
            <label>Webhook / notify URL</label>
            <input type="text" value="https://papelo.lk/api/payhere/notify" readonly class="bg-paper/40 text-ink/50 focus:ring-0 cursor-default">
            <p class="hint">Set this exact URL in your PayHere merchant dashboard.</p>
          </div>
        </div>
      </section>

      <!-- PRICING -->
      <section id="pricing" class="settings-card p-6 scroll-mt-24">
        <h2 class="font-display text-lg text-ink mb-1">Pricing defaults</h2>
        <p class="text-xs text-ink/50 mb-6">Used when a new paper doesn't set its own price.</p>
        <div class="grid grid-cols-2 gap-4">
          <div class="field">
            <label>Price per paper (Rs.)</label>
            <input type="number" wire:model="defaultPrice">
          </div>
          <div class="field">
            <label>Monthly subscription (Rs.)</label>
            <input type="number" wire:model="defaultSubscription">
          </div>
        </div>
      </section>

      <!-- NOTIFICATIONS -->
      <section id="notifications" class="settings-card p-6 scroll-mt-24">
        <h2 class="font-display text-lg text-ink mb-1">Notifications</h2>
        <p class="text-xs text-ink/50 mb-6">What you get emailed about.</p>
        <div class="space-y-4">
          <div class="flex items-center justify-between">
            <div><p class="text-sm text-ink">New student sign-up</p></div>
            <label class="switch"><input type="checkbox" wire:model="notifyNewStudent"><span class="track"></span></label>
          </div>
          <div class="flex items-center justify-between">
            <div><p class="text-sm text-ink">Failed payment</p></div>
            <label class="switch"><input type="checkbox" wire:model="notifyFailedPayment"><span class="track"></span></label>
          </div>
          <div class="flex items-center justify-between">
            <div><p class="text-sm text-ink">New paper published</p></div>
            <label class="switch"><input type="checkbox" wire:model="notifyNewPaper"><span class="track"></span></label>
          </div>
          <div class="flex items-center justify-between">
            <div><p class="text-sm text-ink">Weekly summary email</p></div>
            <label class="switch"><input type="checkbox" wire:model="notifyWeeklySummary"><span class="track"></span></label>
          </div>
        </div>
      </section>

      <!-- ADMIN TEAM -->
      <section id="team" class="settings-card p-6 scroll-mt-24">
        <div class="flex items-center justify-between mb-1">
          <h2 class="font-display text-lg text-ink">Admin team</h2>
          <button class="text-xs font-semibold text-teal border border-teal/30 rounded-lg px-3 py-1.5 hover:bg-teal/5">+ Invite admin</button>
        </div>
        <p class="text-xs text-ink/50 mb-6">People with access to this dashboard.</p>
        <div class="divide-y divide-ink/5">
          <div class="flex items-center justify-between py-3">
            <div class="flex items-center gap-3">
              <div class="w-8 h-8 rounded-full bg-teal flex items-center justify-center text-paper text-xs font-semibold uppercase">
                {{ substr(auth()->user()->name ?? 'A', 0, 2) }}
              </div>
              <div><p class="text-sm font-medium text-ink">{{ auth()->user()->name ?? 'Admin' }}</p><p class="text-xs text-ink/40">{{ auth()->user()->email ?? 'you@papelo.lk' }}</p></div>
            </div>
            <span class="text-xs font-semibold text-ink/50 bg-ink/5 rounded-full px-2.5 py-0.5">Owner</span>
          </div>
        </div>
      </section>

      <!-- DANGER ZONE -->
      <section id="danger" class="settings-card p-6 border-margin/30 scroll-mt-24">
        <h2 class="font-display text-lg text-margin mb-1">Danger zone</h2>
        <p class="text-xs text-ink/50 mb-5">These actions are permanent.</p>
        <div class="flex items-center justify-between border border-margin/20 rounded-lg px-4 py-3">
          <div>
            <p class="text-sm font-medium text-ink">Export all platform data</p>
            <p class="text-xs text-ink/50">Download a full backup before making major changes.</p>
          </div>
          <button wire:click="exportData" class="text-xs font-semibold text-ink/70 border border-ink/15 rounded-lg px-3 py-1.5 hover:border-ink/30">Export</button>
        </div>
      </section>

    </div>
  </div>
</div>
