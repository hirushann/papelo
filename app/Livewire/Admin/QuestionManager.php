<?php

namespace App\Livewire\Admin;

use App\Models\Subject;
use App\Models\Paper;
use App\Models\Question;
use App\Models\Option;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\Attributes\Validate;
use Livewire\Attributes\Computed;

class QuestionManager extends Component
{
    use WithFileUploads;

    public int $step = 1;
    public $selectedSubjectId = '';
    public string $subjectName = '';
    public string $subjectLevel = '';
    public string $subjectMedium = '';
    public bool $creatingSubject = false;

    public $selectedPaperId = '';
    public string $paperTitle = '';
    public string $paperYear = '';
    public string $paperPrice = '';
    public string $paperDuration = '';
    public bool $creatingPaper = false;

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
    public bool $showQuestionForm = false;
    public bool $removeImage = false;
    public string $successMessage = '';

    #[Computed]
    public function subjects()
    {
        return Subject::orderBy('name')->get();
    }

    #[Computed]
    public function selectedSubject()
    {
        return $this->selectedSubjectId
            ? Subject::find($this->selectedSubjectId)
            : null;
    }

    #[Computed]
    public function papers()
    {
        return $this->selectedSubjectId
            ? Paper::where('subject_id', $this->selectedSubjectId)->orderByDesc('year')->get()
            : collect();
    }

    #[Computed]
    public function selectedPaper()
    {
        return $this->selectedPaperId
            ? Paper::find($this->selectedPaperId)
            : null;
    }

    #[Computed]
    public function questions()
    {
        return $this->selectedPaperId
            ? Question::where('paper_id', $this->selectedPaperId)
                ->with('options')
                ->orderBy('order_index')
                ->get()
            : collect();
    }

    public function selectSubject(): void
    {
        $this->validate([
            'selectedSubjectId' => 'required',
        ], [
            'selectedSubjectId.required' => 'Please choose a subject to continue.',
        ]);

        $this->step = 2;
        $this->resetPaperFields();
    }

    public function toggleCreateSubject(): void
    {
        $this->creatingSubject = !$this->creatingSubject;
        $this->resetValidation();
    }

    public function createSubject(): void
    {
        $this->validate([
            'subjectName' => 'required|string|max:255',
            'subjectLevel' => 'required|in:scholarship,ol,al',
            'subjectMedium' => 'required|in:english,sinhala,tamil',
        ]);

        $subject = Subject::create([
            'name' => $this->subjectName,
            'level' => $this->subjectLevel,
            'medium' => $this->subjectMedium,
            'slug' => Str::slug($this->subjectName . '-' . $this->subjectLevel . '-' . $this->subjectMedium),
        ]);

        $this->selectedSubjectId = $subject->id;
        $this->creatingSubject = false;
        $this->subjectName = '';
        $this->subjectLevel = '';
        $this->subjectMedium = '';
        $this->step = 2;
        $this->successMessage = 'Subject created successfully!';
        unset($this->subjects);
    }

    public function selectPaper(): void
    {
        $this->validate([
            'selectedPaperId' => 'required',
        ], [
            'selectedPaperId.required' => 'Please choose a paper to continue.',
        ]);

        $this->step = 3;
        $this->resetQuestionFields();
    }

    public function toggleCreatePaper(): void
    {
        $this->creatingPaper = !$this->creatingPaper;
        $this->resetValidation();
    }

    public function createPaper(): void
    {
        $this->validate([
            'paperTitle' => 'required|string|max:255',
            'paperYear' => 'required|integer|min:1990|max:' . (date('Y') + 1),
            'paperPrice' => 'required|numeric|min:0',
            'paperDuration' => 'required|integer|min:1',
        ]);

        $paper = Paper::create([
            'subject_id' => $this->selectedSubjectId,
            'title' => $this->paperTitle,
            'year' => (int) $this->paperYear,
            'price' => (float) $this->paperPrice,
            'duration_minutes' => (int) $this->paperDuration,
            'is_published' => false,
        ]);

        $this->selectedPaperId = $paper->id;
        $this->creatingPaper = false;
        $this->resetPaperFormFields();
        $this->step = 3;
        $this->successMessage = 'Paper created successfully!';
        unset($this->papers);
    }

    public function togglePublishPaper(): void
    {
        if (!$this->selectedPaperId) return;

        $paper = \App\Models\Paper::find($this->selectedPaperId);
        if ($paper) {
            $paper->is_published = !$paper->is_published;
            $paper->save();
            
            \Illuminate\Support\Facades\Cache::forget('catalog_published_papers');
            
            $this->successMessage = $paper->is_published ? 'Paper published successfully!' : 'Paper unpublished.';
        }
    }

    public function saveQuestion(): void
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
            'options.0.text.required' => 'Option A is required.',
            'options.1.text.required' => 'Option B is required.',
            'options.2.text.required' => 'Option C is required.',
            'options.3.text.required' => 'Option D is required.',
            'correctOption.required' => 'You must select which option is the correct answer.',
        ]);

        // Handle image upload
        $imagePath = null;
        if ($this->questionImage) {
            $imagePath = $this->questionImage->store('question-images', 'public');
        }

        $nextOrder = $this->editingQuestionId
            ? Question::find($this->editingQuestionId)->order_index
            : (Question::where('paper_id', $this->selectedPaperId)->max('order_index') ?? 0) + 1;

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
                'paper_id' => $this->selectedPaperId,
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

        $this->resetQuestionFields();
        $this->showQuestionForm = false;
        unset($this->questions);
    }

    public function editQuestion(int $questionId): void
    {
        $question = Question::with('options')->find($questionId);
        if (!$question) return;

        $this->editingQuestionId = $question->id;
        $this->questionText = $question->question_text;
        $this->topicTag = $question->topic_tag ?? '';
        $this->questionImage = null;
        $this->removeImage = false;

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

        $this->showQuestionForm = true;
    }

    public function deleteQuestion(int $questionId): void
    {
        Question::find($questionId)?->delete();

        // Reorder remaining questions
        $questions = Question::where('paper_id', $this->selectedPaperId)
            ->orderBy('order_index')
            ->get();

        foreach ($questions as $index => $question) {
            $question->update(['order_index' => $index + 1]);
        }

        $this->successMessage = 'Question deleted.';
        unset($this->questions);
    }

    public function showAddForm(): void
    {
        $this->resetQuestionFields();
        $this->showQuestionForm = true;
    }

    public function cancelEdit(): void
    {
        $this->resetQuestionFields();
        $this->showQuestionForm = false;
    }

    public function goToStep(int $step): void
    {
        if ($step === 1) {
            $this->step = 1;
        } elseif ($step === 2 && $this->selectedSubjectId) {
            $this->step = 2;
        } elseif ($step === 3 && $this->selectedPaperId) {
            $this->step = 3;
        }
    }

    private function resetPaperFields(): void
    {
        $this->selectedPaperId = '';
        $this->creatingPaper = false;
        $this->resetPaperFormFields();
    }

    private function resetPaperFormFields(): void
    {
        $this->paperTitle = '';
        $this->paperYear = '';
        $this->paperPrice = '';
        $this->paperDuration = '';
    }

    private function resetQuestionFields(): void
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
        $this->resetValidation();
    }

    public function render()
    {
        return view('livewire.admin.question-manager');
    }
}
