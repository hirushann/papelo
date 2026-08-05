<?php

namespace App\Livewire;

use App\Models\Attempt;
use App\Models\AttemptAnswer;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class ProgressReport extends Component
{
    use WithPagination;

    public $user;
    public $subjectFilter = 'all';
    
    // Stats
    public $totalAttempts = 0;
    public $averageScore = 0;
    public $strongestTopic = 'N/A';
    public $weakestTopic = 'N/A';
    
    // Charts Data
    public $scoreTrend = [];
    public $topicBreakdown = [];
    public $availableSubjects = [];

    public function mount()
    {
        $this->user = Auth::user();
        
        if (!$this->user) {
            return redirect()->route('login');
        }

        $this->calculateStats();
        $this->loadScoreTrend();
        $this->loadTopicBreakdown();
        $this->loadAvailableSubjects();
    }
    
    public function setSubjectFilter($subject)
    {
        $this->subjectFilter = $subject;
        $this->resetPage();
    }

    public function logout(\App\Livewire\Actions\Logout $logout)
    {
        $logout();
        $this->redirect('/', navigate: true);
    }

    private function calculateStats()
    {
        $completedAttempts = Attempt::where('user_id', $this->user->id)
            ->whereNotNull('completed_at');
            
        $this->totalAttempts = $completedAttempts->count();
        $this->averageScore = $this->totalAttempts > 0 
            ? round($completedAttempts->avg('score')) 
            : 0;
    }

    private function loadScoreTrend()
    {
        $this->scoreTrend = Attempt::with('paper.subject')
            ->where('user_id', $this->user->id)
            ->whereNotNull('completed_at')
            ->orderBy('completed_at', 'desc')
            ->take(6)
            ->get()
            ->reverse()
            ->values();
    }

    private function loadTopicBreakdown()
    {
        // Get all answers for completed attempts by this user
        $answers = DB::table('attempt_answers')
            ->join('attempts', 'attempt_answers.attempt_id', '=', 'attempts.id')
            ->join('questions', 'attempt_answers.question_id', '=', 'questions.id')
            ->join('papers', 'questions.paper_id', '=', 'papers.id')
            ->join('subjects', 'papers.subject_id', '=', 'subjects.id')
            ->where('attempts.user_id', $this->user->id)
            ->whereNotNull('attempts.completed_at')
            ->select(
                'questions.topic_tag',
                'subjects.name as subject_name',
                DB::raw('COUNT(*) as total'),
                DB::raw('SUM(is_correct) as correct')
            )
            ->groupBy('questions.topic_tag', 'subjects.name')
            ->get();

        $topics = [];
        foreach ($answers as $ans) {
            $topic = $ans->topic_tag ?: 'General';
            $score = $ans->total > 0 ? round(($ans->correct / $ans->total) * 100) : 0;
            $topics[] = [
                'topic' => $topic,
                'subject' => $ans->subject_name,
                'score' => $score
            ];
        }

        // Sort by score ascending (weakest first)
        usort($topics, fn($a, $b) => $a['score'] <=> $b['score']);
        
        $this->topicBreakdown = $topics;

        if (count($topics) > 0) {
            $this->weakestTopic = $topics[0]['topic'];
            $this->strongestTopic = $topics[count($topics) - 1]['topic'];
        }
    }

    private function loadAvailableSubjects()
    {
        $this->availableSubjects = DB::table('attempts')
            ->join('papers', 'attempts.paper_id', '=', 'papers.id')
            ->join('subjects', 'papers.subject_id', '=', 'subjects.id')
            ->where('attempts.user_id', $this->user->id)
            ->select('subjects.name')
            ->distinct()
            ->pluck('name')
            ->toArray();
    }

    public function render()
    {
        $query = Attempt::with(['paper.subject'])
            ->where('user_id', $this->user->id)
            ->whereNotNull('completed_at');
            
        if ($this->subjectFilter !== 'all') {
            $query->whereHas('paper.subject', function ($q) {
                $q->where('name', $this->subjectFilter);
            });
        }
        
        $history = $query->orderBy('completed_at', 'desc')->paginate(6);

        return view('livewire.progress-report', [
            'history' => $history
        ])->layout('layouts.quiz')->title('Your Progress — Papelo');
    }
}
