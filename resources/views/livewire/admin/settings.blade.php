<div>
  <x-slot name="customHeader">
    <header class="h-16 bg-white border-b border-ink/10 flex items-center justify-between px-8 flex-shrink-0" x-data="{ saving: false }" @settings-saved.window="saving = false">
      <h1 class="font-display text-xl text-ink">Settings</h1>
      <button x-on:click="saving = true; $dispatch('save-settings')" class="inline-flex items-center justify-center min-w-[120px] rounded-lg bg-teal text-paper text-sm font-semibold px-4 py-2 hover:bg-teal/90 transition disabled:opacity-50" x-bind:disabled="saving">
        <span x-show="!saving">Save changes</span>
        <span x-show="saving" style="display:none;" class="flex items-center gap-2">
            <svg class="animate-spin -ml-1 mr-1 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            Saving...
        </span>
      </button>
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
    <div class="space-y-8 pb-12 relative">

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
            <input type="text" wire:model="merchantId" class="w-full border border-ink/15 rounded-lg py-[0.65rem] px-[0.9rem] text-[0.9rem] text-ink bg-white transition-colors focus:outline-none focus:border-teal focus:ring-[3px] focus:ring-teal/15 shadow-sm">
          </div>
          <div class="field">
            <label>Merchant secret</label>
            <input type="password" wire:model="merchantSecret" class="w-full border border-ink/15 rounded-lg py-[0.65rem] px-[0.9rem] text-[0.9rem] text-ink bg-white transition-colors focus:outline-none focus:border-teal focus:ring-[3px] focus:ring-teal/15 shadow-sm">
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
            <label class="block text-xs font-semibold text-ink/70 mb-1.5">Price per paper (Rs.)</label>
            <input type="number" wire:model="defaultPrice" class="w-full border border-ink/15 rounded-lg py-[0.65rem] px-[0.9rem] text-[0.9rem] text-ink bg-white transition-colors focus:outline-none focus:border-teal focus:ring-[3px] focus:ring-teal/15 shadow-sm">
          </div>
          <div class="field">
            <label class="block text-xs font-semibold text-ink/70 mb-1.5">Monthly subscription (Rs.)</label>
            <input type="number" wire:model="defaultSubscription" class="w-full border border-ink/15 rounded-lg py-[0.65rem] px-[0.9rem] text-[0.9rem] text-ink bg-white transition-colors focus:outline-none focus:border-teal focus:ring-[3px] focus:ring-teal/15 shadow-sm">
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
          <flux:modal.trigger name="invite-admin">
            <button class="text-xs font-semibold text-teal border border-teal/30 rounded-lg px-3 py-1.5 hover:bg-teal/5">+ Invite admin</button>
          </flux:modal.trigger>
        </div>
        <p class="text-xs text-ink/50 mb-6">People with access to this dashboard.</p>
        <div class="divide-y divide-ink/5">
          @foreach($this->admins as $index => $admin)
          <div class="flex items-center justify-between py-3">
            <div class="flex items-center gap-3">
              <div class="w-8 h-8 rounded-full bg-teal flex items-center justify-center text-paper text-xs font-semibold uppercase">
                {{ substr($admin->name ?? 'A', 0, 2) }}
              </div>
              <div><p class="text-sm font-medium text-ink">{{ $admin->name }}</p><p class="text-xs text-ink/40">{{ $admin->email }}</p></div>
            </div>
            @if($index === 0)
            <span class="text-xs font-semibold text-ink/50 bg-ink/5 rounded-full px-2.5 py-0.5">Owner</span>
            @else
            <span class="text-xs font-semibold text-ink/50 bg-ink/5 rounded-full px-2.5 py-0.5">Admin</span>
            @endif
          </div>
          @endforeach
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

  <!-- TOAST NOTIFICATION -->
  <div x-data="{ show: false, message: '' }" 
       @settings-saved.window="show = true; message = 'Settings saved successfully!'; setTimeout(() => show = false, 3000)"
       @export-started.window="show = true; message = 'Data export started. You will receive an email shortly.'; setTimeout(() => show = false, 4000)"
       @admin-invited.window="show = true; message = 'Admin invited successfully!'; setTimeout(() => show = false, 3000)"
       class="fixed bottom-6 right-8 z-50 pointer-events-none">
       
      <div x-show="show" 
           x-transition:enter="transition ease-out duration-300"
           x-transition:enter-start="opacity-0 translate-y-4"
           x-transition:enter-end="opacity-100 translate-y-0"
           x-transition:leave="transition ease-in duration-200"
           x-transition:leave-start="opacity-100 translate-y-0"
           x-transition:leave-end="opacity-0 translate-y-4"
           style="display:none;" 
           class="rounded-lg bg-ink text-white px-5 py-3 shadow-xl flex items-center gap-3">
          <svg class="w-5 h-5 text-teal" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
          <p class="text-sm font-medium" x-text="message"></p>
      </div>
  </div>

  <!-- MODALS -->
  <flux:modal name="invite-admin" class="md:w-full md:max-w-md">
      <div class="p-6">
          <div class="flex items-center justify-between mb-6">
              <h2 class="font-display text-xl text-ink">Invite admin</h2>
              <flux:modal.close>
                  <button class="text-ink/40 hover:text-ink transition"><svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg></button>
              </flux:modal.close>
          </div>
          
          <form wire:submit.prevent="inviteAdmin" class="space-y-4">
              <div>
                  <label class="block text-xs font-semibold text-ink/70 mb-1.5">Name</label>
                  <input type="text" wire:model="inviteName" required class="w-full border border-ink/15 rounded-lg py-[0.65rem] px-[0.9rem] text-[0.9rem] text-ink bg-white transition-colors focus:outline-none focus:border-teal focus:ring-[3px] focus:ring-teal/15 shadow-sm">
                  <x-input-error :messages="$errors->get('inviteName')" class="mt-1" />
              </div>
              <div>
                  <label class="block text-xs font-semibold text-ink/70 mb-1.5">Email</label>
                  <input type="email" wire:model="inviteEmail" required class="w-full border border-ink/15 rounded-lg py-[0.65rem] px-[0.9rem] text-[0.9rem] text-ink bg-white transition-colors focus:outline-none focus:border-teal focus:ring-[3px] focus:ring-teal/15 shadow-sm">
                  <x-input-error :messages="$errors->get('inviteEmail')" class="mt-1" />
              </div>
              <div>
                  <label class="block text-xs font-semibold text-ink/70 mb-1.5">Password</label>
                  <input type="password" wire:model="invitePassword" required minlength="8" class="w-full border border-ink/15 rounded-lg py-[0.65rem] px-[0.9rem] text-[0.9rem] text-ink bg-white transition-colors focus:outline-none focus:border-teal focus:ring-[3px] focus:ring-teal/15 shadow-sm">
                  <x-input-error :messages="$errors->get('invitePassword')" class="mt-1" />
                  <p class="text-xs text-ink/50 mt-1.5">You'll need to share this password with them securely.</p>
              </div>
              
              <div class="flex justify-end gap-3 pt-2">
                  <flux:modal.close>
                      <button type="button" class="text-sm font-semibold text-ink/70 px-4 py-2 rounded-lg hover:bg-ink/5 transition">Cancel</button>
                  </flux:modal.close>
                  <button type="submit" class="text-sm font-semibold text-paper bg-teal px-4 py-2 rounded-lg hover:bg-teal/90 transition">Invite admin</button>
              </div>
          </form>
      </div>
  </flux:modal>
</div>
