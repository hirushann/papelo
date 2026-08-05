<?php

namespace App\Livewire;

use App\Models\Attempt;
use App\Models\AttemptAnswer;
use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('layouts.quiz')]
class DetailedReview extends Component
{
    public Attempt $attempt;
    public $filter = 'all'; // all, correct, incorrect, unanswered
    public $questionsData = []; // Pre-processed data for the view
    public $stats = [
        'correct' => 0,
        'incorrect' => 0,
        'unanswered' => 0,
        'total' => 0,
    ];

    public function mount(Attempt $attempt)
    {
        $this->attempt = $attempt->load(['paper.questions.options']);
        $this->stats['total'] = $this->attempt->total_questions;

        // Load all answers mapped by question_id
        $answers = AttemptAnswer::where('attempt_id', $this->attempt->id)
            ->get()
            ->keyBy('question_id');

        foreach ($this->attempt->paper->questions as $index => $question) {
            $answer = $answers->get($question->id);
            $status = 'unanswered';
            
            if ($answer) {
                $status = $answer->is_correct ? 'correct' : 'incorrect';
                if ($answer->is_correct) {
                    $this->stats['correct']++;
                } else {
                    $this->stats['incorrect']++;
                }
            } else {
                $this->stats['unanswered']++;
            }

            $correctOptionId = $question->options->where('is_correct', true)->first()->id ?? null;

            $this->questionsData[] = [
                'id' => $question->id,
                'index' => $index + 1,
                'text' => $question->question_text,
                'topic' => $question->topic_tag ?? 'General',
                'status' => $status,
                'options' => $question->options,
                'selected_option_id' => $answer->selected_option_id ?? null,
                'correct_option_id' => $correctOptionId,
            ];
        }
    }

    public function setFilter($filter)
    {
        $this->filter = $filter;
    }

    public function getFilteredQuestionsProperty()
    {
        if ($this->filter === 'all') {
            return collect($this->questionsData);
        }

        return collect($this->questionsData)->filter(function ($q) {
            return $q['status'] === $this->filter;
        });
    }

    public function render()
    {
        return view('livewire.detailed-review')->title('Review — ' . ($this->attempt->paper->title ?? 'Exam'));
    }
}
