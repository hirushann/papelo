<?php

namespace App\Livewire\Admin;

use App\Models\Paper;
use App\Models\Question;
use App\Models\Option;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\Attributes\Url;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;

#[Layout('layouts.admin')]
class QuestionManager extends Component
{
    use WithFileUploads;

    #[Url]
    public $paper_id = '';

    public string $questionText = '';
    public $questionImage = null;
    public string $topicTag = '';
    public array $options = [
        ['text' => '', 'is_correct' => false],
        ['text' => '', 'is_correct' => false],
        ['text' => '', 'is_correct' => false],
        ['text' => '', 'is_correct' => false],
    ];
    public ?string $correctOption = null;

    public ?int $editingQuestionId = null;
    public bool $removeImage = false;
    public string $successMessage = '';

    public function mount()
    {
        if (!$this->paper_id) {
            return;
        }

        if (!$this->paper) {
            return redirect()->route('admin.papers');
        }
        
        // Select first question automatically if exists
        $firstQuestion = $this->questions->first();
        if ($firstQuestion) {
            $this->editQuestion($firstQuestion->id);
        }
    }

    #[Computed]
    public function paper()
    {
        return Paper::with('subject')->find($this->paper_id);
    }

    #[Computed]
    public function allPapers()
    {
        return Paper::latest()->get();
    }
    
    public function updatedPaperId()
    {
        if ($this->paper_id) {
            $firstQuestion = $this->questions->first();
            if ($firstQuestion) {
                $this->editQuestion($firstQuestion->id);
            } else {
                $this->showAddForm();
            }
        }
    }

    #[Computed]
    public function questions()
    {
        return Question::where('paper_id', $this->paper_id)
            ->with('options')
            ->orderBy('order_index')
            ->get();
    }

    public function saveQuestion(bool $addNext = false): void
    {
        $this->validate([
            'questionText' => 'required|string',
            'questionImage' => 'nullable|image|max:2048',
            'topicTag' => 'nullable|string|max:100',
            'options.0.text' => 'required|string|max:1000',
            'options.1.text' => 'required|string|max:1000',
            'options.2.text' => 'required|string|max:1000',
            'options.3.text' => 'required|string|max:1000',
            'correctOption' => 'required|in:0,1,2,3',
        ], [
            'questionText.required' => 'The question text is required.',
            'options.0.text.required' => 'Option 1 is required.',
            'options.1.text.required' => 'Option 2 is required.',
            'options.2.text.required' => 'Option 3 is required.',
            'options.3.text.required' => 'Option 4 is required.',
            'correctOption.required' => 'You must select which option is the correct answer.',
        ]);

        // Handle image upload
        $imagePath = null;
        if ($this->questionImage) {
            $imagePath = $this->questionImage->store('question-images', 'public');
        }

        $nextOrder = $this->editingQuestionId
            ? Question::find($this->editingQuestionId)->order_index
            : ($this->questions->max('order_index') ?? 0) + 1;

        if ($this->editingQuestionId) {
            // Update existing question
            $question = Question::find($this->editingQuestionId);
            
            // Handle image removal or replacement
            $finalImagePath = $question->image_path;
            if ($this->removeImage) {
                if ($finalImagePath) {
                    \Illuminate\Support\Facades\Storage::disk('public')->delete($finalImagePath);
                }
                $finalImagePath = null;
            } elseif ($imagePath) {
                if ($finalImagePath) {
                    \Illuminate\Support\Facades\Storage::disk('public')->delete($finalImagePath);
                }
                $finalImagePath = $imagePath;
            }

            $question->update([
                'question_text' => $this->questionText,
                'image_path' => $finalImagePath,
                'topic_tag' => $this->topicTag ?: null,
                'order_index' => $nextOrder,
            ]);

            // Delete existing options and recreate
            $question->options()->delete();
        } else {
            // Create new question
            $question = Question::create([
                'paper_id' => $this->paper_id,
                'question_text' => $this->questionText,
                'image_path' => $imagePath,
                'topic_tag' => $this->topicTag ?: null,
                'order_index' => $nextOrder,
            ]);
        }

        // Create options
        foreach ($this->options as $index => $option) {
            Option::create([
                'question_id' => $question->id,
                'option_text' => $option['text'],
                'is_correct' => (int) $this->correctOption === $index,
                'order_index' => $index + 1,
            ]);
        }

        $this->successMessage = $this->editingQuestionId
            ? 'Question updated successfully!'
            : 'Question added successfully!';

        unset($this->questions);
        
        if ($addNext) {
            $this->showAddForm();
        } else {
            // Stay on the same edited question, just refresh UI
            $this->editQuestion($question->id);
            // Hide success message after a bit could be done in alpine, for now just let it show
        }
    }

    public function editQuestion(int $questionId): void
    {
        $question = Question::with('options')->find($questionId);
        if (!$question || $question->paper_id != $this->paper_id) return;

        $this->editingQuestionId = $question->id;
        $this->questionText = $question->question_text;
        $this->topicTag = $question->topic_tag ?? '';
        $this->questionImage = null;
        $this->removeImage = false;
        $this->successMessage = '';

        $this->options = [];
        $this->correctOption = null;

        foreach ($question->options->sortBy('order_index')->values() as $index => $option) {
            $this->options[$index] = [
                'text' => $option->option_text,
                'is_correct' => $option->is_correct,
            ];
            if ($option->is_correct) {
                $this->correctOption = (string) $index;
            }
        }

        // Ensure we always have 4 options
        while (count($this->options) < 4) {
            $this->options[] = ['text' => '', 'is_correct' => false];
        }
    }

    public function deleteQuestion(): void
    {
        if (!$this->editingQuestionId) return;
        
        Question::find($this->editingQuestionId)?->delete();

        // Reorder remaining questions
        $remainingQuestions = Question::where('paper_id', $this->paper_id)
            ->orderBy('order_index')
            ->get();

        foreach ($remainingQuestions as $index => $q) {
            $q->update(['order_index' => $index + 1]);
        }

        unset($this->questions);
        $this->successMessage = 'Question deleted.';
        
        $firstQuestion = $this->questions->first();
        if ($firstQuestion) {
            $this->editQuestion($firstQuestion->id);
        } else {
            $this->showAddForm();
        }
    }

    public function showAddForm(): void
    {
        $this->editingQuestionId = null;
        $this->questionText = '';
        $this->questionImage = null;
        $this->removeImage = false;
        $this->topicTag = '';
        $this->correctOption = null;
        $this->options = [
            ['text' => '', 'is_correct' => false],
            ['text' => '', 'is_correct' => false],
            ['text' => '', 'is_correct' => false],
            ['text' => '', 'is_correct' => false],
        ];
        $this->successMessage = '';
        $this->resetValidation();
    }


    public function render()
    {
        return view('livewire.admin.question-manager');
    }
}
