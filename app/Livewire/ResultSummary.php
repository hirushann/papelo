<?php

namespace App\Livewire;

use App\Models\Attempt;
use App\Models\AttemptAnswer;
use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('layouts.quiz')]
class ResultSummary extends Component
{
    public Attempt $attempt;

    public $correctCount = 0;
    public $incorrectCount = 0;
    public $unansweredCount = 0;
    public $totalQuestions = 0;
    public $percentage = 0;
    public $timeTakenMinutes = 0;
    public $timeTakenSeconds = 0;
    
    // Analytics by topic
    public $topicAnalytics = []; // [topic => ['correct' => int, 'total' => int, 'percentage' => float]]

    public function mount(Attempt $attempt)
    {
        $this->attempt = $attempt->load('paper.questions');
        $this->totalQuestions = $this->attempt->total_questions;
        $this->percentage = $this->attempt->score;

        if ($this->attempt->completed_at && $this->attempt->started_at) {
            $diffInSeconds = $this->attempt->started_at->diffInSeconds($this->attempt->completed_at);
            $this->timeTakenMinutes = floor($diffInSeconds / 60);
            $this->timeTakenSeconds = $diffInSeconds % 60;
        }

        // Calculate breakdown
        $answers = AttemptAnswer::where('attempt_id', $this->attempt->id)
            ->with('question')
            ->get();

        $this->unansweredCount = $this->totalQuestions - $answers->count();

        foreach ($answers as $ans) {
            if ($ans->is_correct) {
                $this->correctCount++;
            } else {
                $this->incorrectCount++;
            }

            // Topic Breakdown
            $topic = $ans->question->topic_tag ?? 'General';
            if (!isset($this->topicAnalytics[$topic])) {
                $this->topicAnalytics[$topic] = ['correct' => 0, 'total' => 0];
            }
            
            $this->topicAnalytics[$topic]['total']++;
            if ($ans->is_correct) {
                $this->topicAnalytics[$topic]['correct']++;
            }
        }

        // Also add unanswered to topic totals based on all paper questions
        $answeredQuestionIds = $answers->pluck('question_id')->toArray();
        foreach ($this->attempt->paper->questions as $question) {
            if (!in_array($question->id, $answeredQuestionIds)) {
                $topic = $question->topic_tag ?? 'General';
                if (!isset($this->topicAnalytics[$topic])) {
                    $this->topicAnalytics[$topic] = ['correct' => 0, 'total' => 0];
                }
                $this->topicAnalytics[$topic]['total']++;
            }
        }

        foreach ($this->topicAnalytics as $topic => $data) {
            $this->topicAnalytics[$topic]['percentage'] = $data['total'] > 0 
                ? round(($data['correct'] / $data['total']) * 100) 
                : 0;
        }

        // Sort by lowest percentage first (weakest topics)
        uasort($this->topicAnalytics, function($a, $b) {
            return $a['percentage'] <=> $b['percentage'];
        });
    }

    public function render()
    {
        return view('livewire.result-summary')->title('Results — ' . ($this->attempt->paper->title ?? 'Exam'));
    }
}
