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

    public $importFile;

    public function downloadTemplate()
    {
        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=questions_template.csv",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $columns = ['Question_Text', 'Topic_Tag', 'Option_1', 'Option_2', 'Option_3', 'Option_4', 'Correct_Option_Number_1_To_4'];

        $callback = function() use($columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);
            
            // Example row
            fputcsv($file, ['What is the powerhouse of the cell?', 'Biology', 'Nucleus', 'Mitochondria', 'Ribosome', 'Endoplasmic Reticulum', '2']);
            
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function exportCsv()
    {
        $questions = Question::where('paper_id', $this->paper_id)->with('options')->orderBy('order_index')->get();

        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=questions_export_" . date('Y-m-d') . ".csv",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $columns = ['Question_Text', 'Topic_Tag', 'Option_1', 'Option_2', 'Option_3', 'Option_4', 'Correct_Option_Number_1_To_4'];

        $callback = function() use($questions, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($questions as $question) {
                $opts = $question->options->sortBy('order_index')->values();
                $correctIndex = 1;
                foreach ($opts as $idx => $opt) {
                    if ($opt->is_correct) {
                        $correctIndex = $idx + 1;
                        break;
                    }
                }
                
                fputcsv($file, [
                    $question->question_text,
                    $question->topic_tag ?? '',
                    $opts[0]->option_text ?? '',
                    $opts[1]->option_text ?? '',
                    $opts[2]->option_text ?? '',
                    $opts[3]->option_text ?? '',
                    $correctIndex
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
        $nextOrder = ($this->questions->max('order_index') ?? 0) + 1;

        while ($row = fgetcsv($file)) {
            if (count($row) < 7) continue;

            $questionText = trim($row[0]);
            $topicTag = trim($row[1]);
            $option1 = trim($row[2]);
            $option2 = trim($row[3]);
            $option3 = trim($row[4]);
            $option4 = trim($row[5]);
            $correctNum = (int) trim($row[6]);

            if (!$questionText || !$option1 || !$option2 || !$option3 || !$option4 || !in_array($correctNum, [1, 2, 3, 4])) {
                continue; // Skip invalid row
            }

            $question = Question::create([
                'paper_id' => $this->paper_id,
                'question_text' => $questionText,
                'topic_tag' => $topicTag ?: null,
                'order_index' => $nextOrder++,
            ]);

            $options = [$option1, $option2, $option3, $option4];
            foreach ($options as $index => $optText) {
                Option::create([
                    'question_id' => $question->id,
                    'option_text' => $optText,
                    'is_correct' => ($correctNum === $index + 1),
                    'order_index' => $index + 1,
                ]);
            }

            $importedCount++;
        }

        fclose($file);
        $this->reset('importFile');
        $this->successMessage = "$importedCount questions imported successfully!";
        unset($this->questions);
        
        $firstQuestion = $this->questions->first();
        if ($firstQuestion) {
            $this->editQuestion($firstQuestion->id);
        }
    }

    public function render()
    {
        return view('livewire.admin.question-manager');
    }
}
