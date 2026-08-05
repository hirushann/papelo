<x-admin-layout>
  <x-slot name="headerActions">
    <a href="#" class="inline-flex items-center gap-1.5 rounded-lg bg-teal text-paper text-sm font-semibold px-4 py-2 hover:bg-teal/90 transition">
      <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M12 5v14M5 12h14"/></svg>
      Add Paper
    </a>
  </x-slot>

  <!-- STAT CARDS -->
  <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-6">
    <div class="bg-white rounded-2xl border border-ink/10 p-5">
      <p class="text-xs font-medium text-ink/50 mb-2">Revenue this month</p>
      <p class="font-display text-2xl text-ink mb-1">Rs. 142,500</p>
      <p class="text-xs text-teal font-medium">↑ 12% vs last month</p>
    </div>
    <div class="bg-white rounded-2xl border border-ink/10 p-5">
      <p class="text-xs font-medium text-ink/50 mb-2">Active students</p>
      <p class="font-display text-2xl text-ink mb-1">1,284</p>
      <p class="text-xs text-teal font-medium">↑ 8% vs last month</p>
    </div>
    <div class="bg-white rounded-2xl border border-ink/10 p-5">
      <p class="text-xs font-medium text-ink/50 mb-2">Papers attempted</p>
      <p class="font-display text-2xl text-ink mb-1">3,940</p>
      <p class="text-xs text-teal font-medium">↑ 21% vs last month</p>
    </div>
    <div class="bg-white rounded-2xl border border-ink/10 p-5">
      <p class="text-xs font-medium text-ink/50 mb-2">Preview → purchase</p>
      <p class="font-display text-2xl text-ink mb-1">24%</p>
      <p class="text-xs text-margin font-medium">↓ 3% vs last month</p>
    </div>
  </div>

  <!-- REVENUE CHART + TOP PAPERS -->
  <div class="grid lg:grid-cols-[1fr_320px] gap-6 mb-6">
    <div class="bg-white rounded-2xl border border-ink/10 p-6">
      <div class="flex items-center justify-between mb-6">
        <h2 class="font-display text-lg text-ink">Revenue — last 7 days</h2>
        <select class="text-xs border border-ink/15 rounded-lg px-2.5 py-1.5 text-ink/60">
          <option>7 days</option><option>30 days</option><option>90 days</option>
        </select>
      </div>
      <div class="flex items-end justify-between gap-3 h-40">
        <div class="flex-1 flex flex-col items-center gap-2">
          <div class="w-full bg-teal/20 rounded-t-md" style="height:55%"></div>
          <span class="text-[11px] text-ink/40">Mon</span>
        </div>
        <div class="flex-1 flex flex-col items-center gap-2">
          <div class="w-full bg-teal/20 rounded-t-md" style="height:70%"></div>
          <span class="text-[11px] text-ink/40">Tue</span>
        </div>
        <div class="flex-1 flex flex-col items-center gap-2">
          <div class="w-full bg-teal/20 rounded-t-md" style="height:40%"></div>
          <span class="text-[11px] text-ink/40">Wed</span>
        </div>
        <div class="flex-1 flex flex-col items-center gap-2">
          <div class="w-full bg-teal rounded-t-md" style="height:95%"></div>
          <span class="text-[11px] text-ink/40 font-semibold">Thu</span>
        </div>
        <div class="flex-1 flex flex-col items-center gap-2">
          <div class="w-full bg-teal/20 rounded-t-md" style="height:60%"></div>
          <span class="text-[11px] text-ink/40">Fri</span>
        </div>
        <div class="flex-1 flex flex-col items-center gap-2">
          <div class="w-full bg-teal/20 rounded-t-md" style="height:30%"></div>
          <span class="text-[11px] text-ink/40">Sat</span>
        </div>
        <div class="flex-1 flex flex-col items-center gap-2">
          <div class="w-full bg-teal/20 rounded-t-md" style="height:25%"></div>
          <span class="text-[11px] text-ink/40">Sun</span>
        </div>
      </div>
    </div>

    <div class="bg-white rounded-2xl border border-ink/10 p-6">
      <h2 class="font-display text-lg text-ink mb-5">Top papers this month</h2>
      <div class="space-y-4">
        <div>
          <div class="flex justify-between text-sm mb-1"><span class="text-ink/80 truncate">O/L Science 2024</span><span class="font-semibold text-ink flex-shrink-0 ml-2">412</span></div>
          <div class="h-1.5 rounded-full bg-ink/10"><div class="h-1.5 rounded-full bg-teal" style="width:100%"></div></div>
        </div>
        <div>
          <div class="flex justify-between text-sm mb-1"><span class="text-ink/80 truncate">Grade 5 Scholarship</span><span class="font-semibold text-ink flex-shrink-0 ml-2">378</span></div>
          <div class="h-1.5 rounded-full bg-ink/10"><div class="h-1.5 rounded-full bg-teal" style="width:88%"></div></div>
        </div>
        <div>
          <div class="flex justify-between text-sm mb-1"><span class="text-ink/80 truncate">O/L Maths 2024</span><span class="font-semibold text-ink flex-shrink-0 ml-2">301</span></div>
          <div class="h-1.5 rounded-full bg-ink/10"><div class="h-1.5 rounded-full bg-teal" style="width:70%"></div></div>
        </div>
        <div>
          <div class="flex justify-between text-sm mb-1"><span class="text-ink/80 truncate">A/L Physical Science</span><span class="font-semibold text-ink flex-shrink-0 ml-2">254</span></div>
          <div class="h-1.5 rounded-full bg-ink/10"><div class="h-1.5 rounded-full bg-teal" style="width:58%"></div></div>
        </div>
      </div>
    </div>
  </div>

  <!-- RECENT ACTIVITY -->
  <div class="bg-white rounded-2xl border border-ink/10 overflow-hidden">
    <div class="flex items-center justify-between px-6 py-4 border-b border-ink/10">
      <h2 class="font-display text-lg text-ink">Recent attempts</h2>
      <a href="#" class="text-xs font-semibold text-teal hover:underline">View all →</a>
    </div>
    <table class="w-full text-sm">
      <thead>
        <tr class="text-left text-xs text-ink/40 border-b border-ink/10">
          <th class="font-medium px-6 py-3">Student</th>
          <th class="font-medium px-6 py-3">Paper</th>
          <th class="font-medium px-6 py-3">Score</th>
          <th class="font-medium px-6 py-3">Date</th>
        </tr>
      </thead>
      <tbody class="divide-y divide-ink/5">
        <tr>
          <td class="px-6 py-3.5 text-ink/80">Sanduni W.</td>
          <td class="px-6 py-3.5 text-ink/60">O/L Science 2024</td>
          <td class="px-6 py-3.5"><span class="text-teal font-semibold">92%</span></td>
          <td class="px-6 py-3.5 text-ink/40">Today, 2:14 PM</td>
        </tr>
        <tr>
          <td class="px-6 py-3.5 text-ink/80">Kavindu R.</td>
          <td class="px-6 py-3.5 text-ink/60">Grade 5 Scholarship</td>
          <td class="px-6 py-3.5"><span class="text-margin font-semibold">54%</span></td>
          <td class="px-6 py-3.5 text-ink/40">Today, 1:52 PM</td>
        </tr>
        <tr>
          <td class="px-6 py-3.5 text-ink/80">Dilhani P.</td>
          <td class="px-6 py-3.5 text-ink/60">A/L Physical Science</td>
          <td class="px-6 py-3.5"><span class="text-teal font-semibold">81%</span></td>
          <td class="px-6 py-3.5 text-ink/40">Today, 12:30 PM</td>
        </tr>
        <tr>
          <td class="px-6 py-3.5 text-ink/80">Tharindu S.</td>
          <td class="px-6 py-3.5 text-ink/60">O/L Maths 2024</td>
          <td class="px-6 py-3.5"><span class="text-teal font-semibold">76%</span></td>
          <td class="px-6 py-3.5 text-ink/40">Today, 11:47 AM</td>
        </tr>
      </tbody>
    </table>
  </div>
</x-admin-layout>
