<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Paper;
use Livewire\Attributes\Computed;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class PaperCatalog extends Component
{
    // ── Filters ────────────────────────────────────────
    public string $filterLevel = '';
    public string $filterMedium = '';
    public string $filterYear = '';
    public string $filterSubject = '';

    /**
     * Check if the current user has access to a paper.
     * Free papers are always accessible.
     * Paid papers require an active subscription.
     */
    public function hasAccess(Paper $paper): bool
    {
        if ((float) $paper->price === 0.00) {
            return true;
        }

        if (!Auth::check()) {
            return false;
        }

        return Auth::user()->hasAccess();
    }

    public function resetFilters()
    {
        $this->reset(['filterLevel', 'filterMedium', 'filterYear', 'filterSubject']);
    }

    #[Computed]
    public function availableYears()
    {
        return Paper::where('is_published', true)->select('year')->distinct()->orderBy('year', 'desc')->pluck('year');
    }

    #[Computed]
    public function availableSubjects()
    {
        return \App\Models\Subject::whereHas('papers', function($q) {
            $q->where('is_published', true);
        })->orderBy('name')->get();
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
            $matchesYear = $this->filterYear === '' || (string) $paper->year === $this->filterYear;
            $matchesSubject = $this->filterSubject === '' || (string) $paper->subject->id === $this->filterSubject;
            return $matchesLevel && $matchesMedium && $matchesYear && $matchesSubject;
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

    public function logout(\App\Livewire\Actions\Logout $logout)
    {
        $logout();
        $this->redirect('/', navigate: true);
    }

    public function render()
    {
        return view('livewire.paper-catalog')
            ->layout('layouts.quiz')
            ->title('Past Papers — Papelooo');
    }
}
