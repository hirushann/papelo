<?php

namespace App\Livewire\Admin;

use App\Models\Paper;
use App\Models\Subject;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use Illuminate\Support\Str;

#[Layout('layouts.admin', ['header' => 'Manage Papers'])]
class PaperManager extends Component
{
    use WithPagination;

    public $search = '';
    public $levelFilter = '';
    public $statusFilter = '';

    // Create Paper Form
    public $newLevel = 'ol';
    public $newMedium = 'english';
    public $newYear;
    public $newSubject = '';
    public $newDuration = 60;
    public $newPrice = 100;
    public $newTitle = '';
    public $newStatus = 'Draft';

    public function mount()
    {
        $this->newYear = date('Y');
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function setLevelFilter($level)
    {
        $this->levelFilter = $level;
        $this->resetPage();
    }

    public function savePaper()
    {
        $this->validate([
            'newLevel' => 'required|in:scholarship,ol,al',
            'newMedium' => 'required|in:english,sinhala,tamil',
            'newYear' => 'required|integer|min:2000|max:2100',
            'newSubject' => 'required|string|max:255',
            'newDuration' => 'required|integer|min:1',
            'newPrice' => 'required|numeric|min:0',
            'newTitle' => 'nullable|string|max:255',
            'newStatus' => 'required|in:Draft,Published',
        ]);

        // Find or create subject
        $subject = Subject::firstOrCreate(
            [
                'name' => $this->newSubject,
                'level' => $this->newLevel,
                'medium' => $this->newMedium
            ],
            [
                'slug' => Str::slug($this->newSubject . '-' . $this->newLevel . '-' . $this->newMedium)
            ]
        );

        $paper = Paper::create([
            'subject_id' => $subject->id,
            'year' => $this->newYear,
            'title' => $this->newTitle ?: ($subject->name . ' ' . $this->newYear),
            'price' => $this->newPrice,
            'duration_minutes' => $this->newDuration,
            'is_published' => $this->newStatus === 'Published',
        ]);

        $this->reset(['newLevel', 'newMedium', 'newSubject', 'newDuration', 'newPrice', 'newTitle']);
        $this->newYear = date('Y');
        $this->newStatus = 'Draft';

        // Redirect to question manager for this paper
        return redirect()->route('admin.questions', ['paper_id' => $paper->id]);
    }

    public function render()
    {
        $query = Paper::query()
            ->with(['subject', 'attempts'])
            ->withCount('questions');

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('title', 'like', '%' . $this->search . '%')
                  ->orWhereHas('subject', function ($q2) {
                      $q2->where('name', 'like', '%' . $this->search . '%');
                  });
            });
        }

        if ($this->levelFilter) {
            $query->whereHas('subject', function ($q) {
                $q->where('level', $this->levelFilter);
            });
        }

        if ($this->statusFilter) {
            $isPublished = $this->statusFilter === 'Published';
            $query->where('is_published', $isPublished);
        }

        $papers = $query->latest()->paginate(10);

        return view('livewire.admin.paper-manager', [
            'papers' => $papers
        ]);
    }
}
