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
use Illuminate\Support\Facades\Storage;

#[Layout('layouts.admin')]
class QuestionManager extends Component
{
    use WithFileUploads;

    #[Url]
    public $paper_id = '';

    public ?int $editingQuestionId = null;
    public string $successMessage = '';

    // Core properties
    public string $type = 'mcq';
    public string $questionText = '';
    public $questionImage = null;
    public bool $removeImage = false;
    public string $topicTag = '';
    public string $instruction = ''; // shared diagram/passage
    public string $modelSolution = ''; // For short, essay, structured
    public bool $allowPhoto = false; // For structured

    // MCQ & MCQImg
    public array $options = [];
    public ?string $correctOption = null;

    // Matching
    public array $matchPrompts = [];
    public array $matchChoices = [];

    // Cloze
    public string $clozeText = '';
    public array $clozeWords = [];
    public array $clozeAnswers = []; // blank_index => word

    // Short Answer
    public array $shortAnswers = [];

    // Essay
    public string $essayMinWords = '';
    public string $essayMaxWords = '';

    // Structured
    public array $structuredCriteria = [];

    public function mount()
    {
        if (!$this->paper_id) {
            return;
        }

        if (!$this->paper) {
            return redirect()->route('admin.papers');
        }
        
        $firstQuestion = $this->questions->first();
        if ($firstQuestion) {
            $this->editQuestion($firstQuestion->id);
        } else {
            $this->showAddForm();
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

    // Dynamic UI helpers
    public function addOption()
    {
        $this->options[] = ['text' => '', 'is_correct' => false];
    }
    public function removeOption($index)
    {
        unset($this->options[$index]);
        $this->options = array_values($this->options);
    }
    public function addMatchPrompt()
    {
        $this->matchPrompts[] = ['text' => ''];
    }
    public function removeMatchPrompt($index)
    {
        unset($this->matchPrompts[$index]);
        $this->matchPrompts = array_values($this->matchPrompts);
    }
    public function addMatchChoice()
    {
        $this->matchChoices[] = ['text' => '', 'match_index' => null];
    }
    public function removeMatchChoice($index)
    {
        unset($this->matchChoices[$index]);
        $this->matchChoices = array_values($this->matchChoices);
    }
    public function addClozeWord()
    {
        $this->clozeWords[] = '';
    }
    public function removeClozeWord($index)
    {
        unset($this->clozeWords[$index]);
        $this->clozeWords = array_values($this->clozeWords);
    }
    public function addShortAnswer()
    {
        $this->shortAnswers[] = '';
    }
    public function removeShortAnswer($index)
    {
        unset($this->shortAnswers[$index]);
        $this->shortAnswers = array_values($this->shortAnswers);
    }
    public function addStructuredCriterion()
    {
        $this->structuredCriteria[] = ['text' => '', 'marks' => 1];
    }
    public function removeStructuredCriterion($index)
    {
        unset($this->structuredCriteria[$index]);
        $this->structuredCriteria = array_values($this->structuredCriteria);
    }
    public function insertClozeBlank()
    {
        $count = count($this->clozeAnswers) + 1;
        $this->clozeText .= " [blank_{$count}] ";
        $this->clozeAnswers[$count] = '';
    }


    public function saveQuestion(bool $addNext = false): void
    {
        // Basic validation
        $this->validate([
            'type' => 'required|in:mcq,mcqimg,match,cloze,short,essay,structured',
            'questionText' => 'required_unless:type,match|string',
            'questionImage' => 'nullable|image|max:2048',
            'topicTag' => 'nullable|string|max:100',
        ], [
            'questionText.required_unless' => 'The question text is required.',
        ]);

        if (in_array($this->type, ['mcq', 'mcqimg'])) {
            $this->validate(['correctOption' => 'required'], ['correctOption.required' => 'You must select a correct option.']);
        }

        // Handle image upload
        $imagePath = null;
        if ($this->questionImage) {
            $imagePath = $this->questionImage->store('question-images', 'public');
        }

        $nextOrder = $this->editingQuestionId
            ? Question::find($this->editingQuestionId)->order_index
            : ($this->questions->max('order_index') ?? 0) + 1;

        $data = [];
        if ($this->type === 'match') {
            $data['prompts'] = $this->matchPrompts;
            $data['choices'] = $this->matchChoices;
        } elseif ($this->type === 'cloze') {
            $data['text'] = $this->clozeText;
            $data['words'] = array_filter($this->clozeWords);
            $data['answers'] = $this->clozeAnswers;
        } elseif ($this->type === 'short') {
            $data['answers'] = array_filter($this->shortAnswers);
        } elseif ($this->type === 'essay') {
            $data['min_words'] = $this->essayMinWords;
            $data['max_words'] = $this->essayMaxWords;
        } elseif ($this->type === 'structured') {
            $data['criteria'] = $this->structuredCriteria;
        }

        if ($this->editingQuestionId) {
            $question = Question::find($this->editingQuestionId);
            $finalImagePath = $question->image_path;
            if ($this->removeImage) {
                if ($finalImagePath) Storage::disk('public')->delete($finalImagePath);
                $finalImagePath = null;
            } elseif ($imagePath) {
                if ($finalImagePath) Storage::disk('public')->delete($finalImagePath);
                $finalImagePath = $imagePath;
            }

            $question->update([
                'type' => $this->type,
                'question_text' => $this->questionText,
                'image_path' => $finalImagePath,
                'topic_tag' => $this->topicTag ?: null,
                'order_index' => $nextOrder,
                'instruction' => $this->instruction ?: null,
                'model_solution' => $this->modelSolution ?: null,
                'allow_photo' => $this->allowPhoto,
                'data' => $data,
            ]);
            $question->options()->delete();
        } else {
            $question = Question::create([
                'paper_id' => $this->paper_id,
                'type' => $this->type,
                'question_text' => $this->questionText,
                'image_path' => $imagePath,
                'topic_tag' => $this->topicTag ?: null,
                'order_index' => $nextOrder,
                'instruction' => $this->instruction ?: null,
                'model_solution' => $this->modelSolution ?: null,
                'allow_photo' => $this->allowPhoto,
                'data' => $data,
            ]);
        }

        if (in_array($this->type, ['mcq', 'mcqimg'])) {
            foreach ($this->options as $index => $option) {
                Option::create([
                    'question_id' => $question->id,
                    'option_text' => $option['text'],
                    'is_correct' => (string) $this->correctOption === (string) $index,
                    'order_index' => $index + 1,
                ]);
            }
        }

        $this->successMessage = $this->editingQuestionId
            ? 'Question updated successfully!'
            : 'Question added successfully!';

        unset($this->questions);
        
        if ($addNext) {
            $this->showAddForm();
        } else {
            $this->editQuestion($question->id);
        }
    }

    public function editQuestion(int $questionId): void
    {
        $question = Question::with('options')->find($questionId);
        if (!$question || $question->paper_id != $this->paper_id) return;

        $this->showAddForm(); // reset all
        
        $this->editingQuestionId = $question->id;
        $this->type = $question->type ?? 'mcq';
        $this->questionText = $question->question_text;
        $this->topicTag = $question->topic_tag ?? '';
        $this->instruction = $question->instruction ?? '';
        $this->modelSolution = $question->model_solution ?? '';
        $this->allowPhoto = $question->allow_photo;
        $data = $question->data ?? [];

        if (in_array($this->type, ['mcq', 'mcqimg'])) {
            $this->options = [];
            foreach ($question->options->sortBy('order_index')->values() as $index => $option) {
                $this->options[$index] = [
                    'text' => $option->option_text,
                    'is_correct' => $option->is_correct,
                ];
                if ($option->is_correct) {
                    $this->correctOption = (string) $index;
                }
            }
            while (count($this->options) < 4) {
                $this->options[] = ['text' => '', 'is_correct' => false];
            }
        } elseif ($this->type === 'match') {
            $this->matchPrompts = $data['prompts'] ?? [['text' => '']];
            $this->matchChoices = $data['choices'] ?? [['text' => '', 'match_index' => null]];
        } elseif ($this->type === 'cloze') {
            $this->clozeText = $data['text'] ?? '';
            $this->clozeWords = $data['words'] ?? [];
            $this->clozeAnswers = $data['answers'] ?? [];
        } elseif ($this->type === 'short') {
            $this->shortAnswers = $data['answers'] ?? [''];
        } elseif ($this->type === 'essay') {
            $this->essayMinWords = $data['min_words'] ?? '';
            $this->essayMaxWords = $data['max_words'] ?? '';
        } elseif ($this->type === 'structured') {
            $this->structuredCriteria = $data['criteria'] ?? [['text' => '', 'marks' => 1]];
        }
    }

    public function deleteQuestion(): void
    {
        if (!$this->editingQuestionId) return;
        Question::find($this->editingQuestionId)?->delete();

        $remainingQuestions = Question::where('paper_id', $this->paper_id)->orderBy('order_index')->get();
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
        $this->type = 'mcq';
        $this->questionText = '';
        $this->questionImage = null;
        $this->removeImage = false;
        $this->topicTag = '';
        $this->instruction = '';
        $this->modelSolution = '';
        $this->allowPhoto = false;
        $this->correctOption = null;
        
        $this->options = [
            ['text' => '', 'is_correct' => false],
            ['text' => '', 'is_correct' => false],
            ['text' => '', 'is_correct' => false],
            ['text' => '', 'is_correct' => false],
        ];
        $this->matchPrompts = [['text' => '']];
        $this->matchChoices = [['text' => '', 'match_index' => null]];
        
        $this->clozeText = '';
        $this->clozeWords = [];
        $this->clozeAnswers = [];
        
        $this->shortAnswers = [''];
        $this->essayMinWords = '';
        $this->essayMaxWords = '';
        $this->structuredCriteria = [['text' => '', 'marks' => 1]];

        $this->successMessage = '';
        $this->resetValidation();
    }

    public function render()
    {
        return view('livewire.admin.question-manager');
    }
}
