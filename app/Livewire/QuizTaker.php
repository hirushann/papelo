<?php

namespace App\Livewire;

use App\Models\Paper;
use App\Models\Attempt;
use App\Models\AttemptAnswer;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('layouts.quiz')]
class QuizTaker extends Component
{
    public Paper $paper;
    public ?Attempt $attempt = null;
    
    // Loaded once
    public $questions;
    public $allQuestionsCount = 0;

    // Preview mode
    public bool $isPreview = false;
    public int $previewLimit = 3;
    public bool $showPreviewResults = false;
    public int $previewCorrect = 0;
    public int $previewIncorrect = 0;
    public int $previewUnanswered = 0;
    public int $previewScore = 0;
    public array $previewTopics = [];

    // State
    public int $currentQuestionIndex = 0;
    public array $answers = []; // question_id => option_id
    public array $flagged = []; // question_id => true

    public function mount(Paper $paper)
    {
        $this->paper = $paper->load('subject');
        $this->isPreview = request()->query('preview') === '1';
        
        // Load all questions to get the total count
        $allQuestions = $paper->questions()->with(['options' => function($q) {
            $q->orderBy('order_index');
        }])->orderBy('order_index')->get();

        $this->allQuestionsCount = $allQuestions->count();

        if ($allQuestions->isEmpty()) {
            session()->flash('error', 'This paper has no questions yet.');
            $this->redirect(route('papers'), navigate: true);
            return;
        }

        if ($this->isPreview) {
            // Preview mode: only show first N questions, no attempt tracking
            $this->questions = $allQuestions->take($this->previewLimit)->values();
            return;
        }

        // Full mode: all questions
        $this->questions = $allQuestions;

        // Initialize or resume attempt
        $existingAttempt = Attempt::where('user_id', Auth::id())
            ->where('paper_id', $this->paper->id)
            ->whereNull('completed_at')
            ->first();

        if ($existingAttempt) {
            $this->attempt = $existingAttempt;
            // Load existing answers
            $existingAnswers = AttemptAnswer::where('attempt_id', $this->attempt->id)->get();
            foreach ($existingAnswers as $ans) {
                $this->answers[$ans->question_id] = $ans->selected_option_id;
            }
        } else {
            $this->attempt = Attempt::create([
                'user_id' => Auth::id(),
                'paper_id' => $this->paper->id,
                'started_at' => now(),
                'total_questions' => $this->questions->count(),
            ]);
        }
    }

    public function selectOption($questionId, $optionId)
    {
        $this->answers[$questionId] = $optionId;
    }

    public function toggleFlag($questionId)
    {
        if (isset($this->flagged[$questionId])) {
            unset($this->flagged[$questionId]);
        } else {
            $this->flagged[$questionId] = true;
        }
    }

    public function goToQuestion($index)
    {
        if ($index >= 0 && $index < $this->questions->count()) {
            $this->currentQuestionIndex = $index;
        }
    }

    public function nextQuestion()
    {
        if ($this->currentQuestionIndex < $this->questions->count() - 1) {
            $this->currentQuestionIndex++;
        } elseif ($this->isPreview) {
            $this->submit();
        }
    }

    public function prevQuestion()
    {
        if ($this->currentQuestionIndex > 0) {
            $this->currentQuestionIndex--;
        }
    }

    public function saveAndExit()
    {
        if (!$this->isPreview && $this->attempt) {
            $this->persistAnswers();
        }
        return redirect()->route($this->isPreview ? 'papers' : 'dashboard');
    }

    public function submit()
    {
        // Calculate score
        $correctCount = 0;
        foreach ($this->questions as $question) {
            $selectedOptionId = $this->answers[$question->id] ?? null;
            if ($selectedOptionId) {
                $correctOption = $question->options->where('is_correct', true)->first();
                if ($correctOption && $correctOption->id == $selectedOptionId) {
                    $correctCount++;
                }
            }
        }

        $score = $this->questions->count() > 0 
            ? round(($correctCount / $this->questions->count()) * 100) 
            : 0;

        if ($this->isPreview) {
            $this->previewCorrect = $correctCount;
            $this->previewIncorrect = count($this->answers) - $correctCount;
            $this->previewUnanswered = $this->questions->count() - count($this->answers);
            $this->previewScore = $score;
            
            // Topic Analytics for preview
            foreach ($this->questions as $question) {
                $topic = $question->topic_tag ?? 'General';
                if (!isset($this->previewTopics[$topic])) {
                    $this->previewTopics[$topic] = ['correct' => 0, 'total' => 0];
                }
                $this->previewTopics[$topic]['total']++;
                
                $selectedOptionId = $this->answers[$question->id] ?? null;
                if ($selectedOptionId) {
                    $correctOption = $question->options->where('is_correct', true)->first();
                    if ($correctOption && $correctOption->id == $selectedOptionId) {
                        $this->previewTopics[$topic]['correct']++;
                    }
                }
            }
            
            foreach ($this->previewTopics as $topic => $data) {
                $this->previewTopics[$topic]['percentage'] = $data['total'] > 0 
                    ? round(($data['correct'] / $data['total']) * 100) : 0;
            }

            $this->showPreviewResults = true;
            return;
        }

        // Complete attempt
        $this->attempt->update([
            'completed_at' => now(),
            'score' => $score
        ]);

        $this->persistAnswers();

        return redirect()->route('result.summary', $this->attempt->id);
    }

    private function persistAnswers()
    {
        if (!$this->attempt) return;

        foreach ($this->answers as $questionId => $optionId) {
            $question = $this->questions->firstWhere('id', $questionId);
            $correctOptionId = $question ? $question->options->where('is_correct', true)->first()->id : null;
            $isCorrect = $optionId == $correctOptionId;

            AttemptAnswer::updateOrCreate(
                [
                    'attempt_id' => $this->attempt->id,
                    'question_id' => $questionId
                ],
                [
                    'selected_option_id' => $optionId,
                    'is_correct' => $isCorrect
                ]
            );
        }
    }

    public function render()
    {
        return view('livewire.quiz-taker')->title($this->paper->title . ' — Papelo');
    }
}
