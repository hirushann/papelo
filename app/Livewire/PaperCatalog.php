<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Paper;
use App\Models\Purchase;
use Livewire\Attributes\Computed;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class PaperCatalog extends Component
{
    // ── Filters ────────────────────────────────────────
    public string $filterLevel = '';
    public string $filterMedium = '';

    #[Computed]
    public function purchasedPaperIds()
    {
        if (! Auth::check()) {
            return collect();
        }

        return Purchase::where('user_id', Auth::id())
            ->where('status', 'completed')
            ->pluck('paper_id');
    }

    public function isPurchased(Paper $paper): bool
    {
        if ((float) $paper->price === 0.00) {
            return true;
        }
        return $this->purchasedPaperIds()->contains($paper->id);
    }

    public function resetFilters()
    {
        $this->reset(['filterLevel', 'filterMedium']);
    }

    #[Computed]
    public function groupedPapers()
    {
        // Fetch all published papers (eager load subjects & count questions)
        $allPapers = Paper::where('is_published', true)
            ->with(['subject'])
            ->withCount('questions')
            ->orderBy('year', 'desc')
            ->get();

        // 2. Apply current Livewire filters
        $filtered = $allPapers->filter(function ($paper) {
            $matchesLevel = $this->filterLevel === '' || $paper->subject->level === $this->filterLevel;
            $matchesMedium = $this->filterMedium === '' || $paper->subject->medium === $this->filterMedium;
            return $matchesLevel && $matchesMedium;
        });

        // 3. Group by level -> subject name
        $grouped = [];
        foreach ($filtered as $paper) {
            $level = $paper->subject->level;
            $subjectName = $paper->subject->name;
            $grouped[$level][$subjectName][] = $paper;
        }

        // 4. Sort the levels in a standard order
        $order = ['scholarship' => 1, 'ol' => 2, 'al' => 3];
        uksort($grouped, fn($a, $b) => ($order[$a] ?? 99) <=> ($order[$b] ?? 99));

        return $grouped;
    }

    // ── UI Helpers ─────────────────────────────────────
    public function levelLabel(string $level): string
    {
        return match ($level) {
            'scholarship' => 'Grade 5 Scholarship',
            'ol' => 'Ordinary Level (O/L)',
            'al' => 'Advanced Level (A/L)',
            default => ucfirst($level),
        };
    }

    public function levelBadgeColor(string $level): string
    {
        return match ($level) {
            'scholarship' => 'emerald',
            'ol' => 'blue',
            'al' => 'indigo',
            default => '',
        };
    }
}
