<?php

namespace App\Livewire\Admin;

use App\Models\Paper;
use App\Models\Subject;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Livewire\Attributes\Layout;
use Illuminate\Support\Str;

#[Layout('layouts.admin', ['header' => 'Manage Papers'])]
class PaperManager extends Component
{
    use WithPagination, WithFileUploads;

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

    public $importFile;

    public function downloadTemplate()
    {
        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=papers_template.csv",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $columns = ['Subject', 'Level (scholarship, ol, al)', 'Medium (english, sinhala, tamil)', 'Year', 'Title', 'Duration_Minutes', 'Price', 'Status (Draft, Published)'];

        $callback = function() use($columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);
            
            // Example row
            fputcsv($file, ['Science', 'ol', 'english', '2023', 'O/L Science 2023', '60', '500', 'Published']);
            
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function exportCsv()
    {
        $query = Paper::query()->with('subject');
        
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

        $papers = $query->get();

        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=papers_export_" . date('Y-m-d') . ".csv",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $columns = ['Subject', 'Level', 'Medium', 'Year', 'Title', 'Duration_Minutes', 'Price', 'Status'];

        $callback = function() use($papers, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($papers as $paper) {
                fputcsv($file, [
                    $paper->subject->name ?? '',
                    $paper->subject->level ?? '',
                    $paper->subject->medium ?? '',
                    $paper->year,
                    $paper->title,
                    $paper->duration_minutes,
                    $paper->price,
                    $paper->is_published ? 'Published' : 'Draft'
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function importCsv()
    {
        $this->validate([
            'importFile' => 'required|file|max:5120',
        ]);

        $filePath = $this->importFile->getRealPath();
        $file = fopen($filePath, 'r');
        $header = fgetcsv($file);

        $importedCount = 0;
        while ($row = fgetcsv($file)) {
            if (count($row) < 8) continue;

            $subjectName = trim($row[0]);
            $level = trim($row[1]);
            $medium = trim($row[2]);
            $year = trim($row[3]);
            $title = trim($row[4]);
            $duration = trim($row[5]);
            $price = trim($row[6]);
            $status = trim($row[7]);

            if (!$subjectName || !in_array($level, ['scholarship', 'ol', 'al']) || !in_array($medium, ['english', 'sinhala', 'tamil'])) {
                continue; // Skip invalid row
            }

            $subject = Subject::firstOrCreate(
                [
                    'name' => $subjectName,
                    'level' => $level,
                    'medium' => $medium
                ],
                [
                    'slug' => Str::slug($subjectName . '-' . $level . '-' . $medium)
                ]
            );

            Paper::create([
                'subject_id' => $subject->id,
                'year' => (int) $year,
                'title' => $title ?: ($subject->name . ' ' . $year),
                'duration_minutes' => (int) $duration,
                'price' => (float) $price,
                'is_published' => strtolower($status) === 'published',
            ]);

            $importedCount++;
        }

        fclose($file);
        $this->reset('importFile');
        session()->flash('success', "$importedCount papers imported successfully!");
        $this->resetPage();
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
