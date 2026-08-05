<?php

namespace Database\Seeders;

use App\Models\Option;
use App\Models\Paper;
use App\Models\Question;
use App\Models\Subject;
use Illuminate\Database\Seeder;

class DummyDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Subjects
        $science = Subject::firstOrCreate(
            ['slug' => 'science-ol-en'],
            ['name' => 'Science', 'level' => 'ol', 'medium' => 'english']
        );
        
        $maths = Subject::firstOrCreate(
            ['slug' => 'maths-al-en'],
            ['name' => 'Mathematics', 'level' => 'al', 'medium' => 'english']
        );

        // Create Papers
        $paper1 = Paper::firstOrCreate(
            ['title' => 'O/L Science 2023'],
            [
                'subject_id' => $science->id,
                'year' => 2023,
                'price' => 150.00,
                'duration_minutes' => 60,
                'is_published' => true,
            ]
        );
        
        $paper2 = Paper::firstOrCreate(
            ['title' => 'A/L Combined Maths 2023'],
            [
                'subject_id' => $maths->id,
                'year' => 2023,
                'price' => 200.00,
                'duration_minutes' => 120,
                'is_published' => true,
            ]
        );

        // Questions for Paper 1
        if ($paper1->questions()->count() == 0) {
            $this->createQuestions($paper1, [
                [
                    'text' => 'What is the powerhouse of the cell?',
                    'topic' => 'Biology',
                    'options' => [
                        ['text' => 'Nucleus', 'correct' => false],
                        ['text' => 'Mitochondria', 'correct' => true],
                        ['text' => 'Ribosome', 'correct' => false],
                        ['text' => 'Endoplasmic Reticulum', 'correct' => false],
                    ]
                ],
                [
                    'text' => 'What is the chemical symbol for Gold?',
                    'topic' => 'Chemistry',
                    'options' => [
                        ['text' => 'Ag', 'correct' => false],
                        ['text' => 'Au', 'correct' => true],
                        ['text' => 'Fe', 'correct' => false],
                        ['text' => 'Cu', 'correct' => false],
                    ]
                ],
                [
                    'text' => 'Which planet is known as the Red Planet?',
                    'topic' => 'Astronomy',
                    'options' => [
                        ['text' => 'Earth', 'correct' => false],
                        ['text' => 'Jupiter', 'correct' => false],
                        ['text' => 'Mars', 'correct' => true],
                        ['text' => 'Venus', 'correct' => false],
                    ]
                ],
                [
                    'text' => 'What is the speed of light in a vacuum?',
                    'topic' => 'Physics',
                    'options' => [
                        ['text' => '300,000 km/s', 'correct' => true],
                        ['text' => '150,000 km/s', 'correct' => false],
                        ['text' => '30,000 km/s', 'correct' => false],
                        ['text' => '3,000,000 km/s', 'correct' => false],
                    ]
                ],
                [
                    'text' => 'What is the most abundant gas in the Earth\'s atmosphere?',
                    'topic' => 'Earth Science',
                    'options' => [
                        ['text' => 'Oxygen', 'correct' => false],
                        ['text' => 'Carbon Dioxide', 'correct' => false],
                        ['text' => 'Nitrogen', 'correct' => true],
                        ['text' => 'Hydrogen', 'correct' => false],
                    ]
                ]
            ]);
        }
        
        // Questions for Paper 2
        if ($paper2->questions()->count() == 0) {
            $this->createQuestions($paper2, [
                [
                    'text' => 'What is the derivative of x^2?',
                    'topic' => 'Calculus',
                    'options' => [
                        ['text' => 'x', 'correct' => false],
                        ['text' => '2x', 'correct' => true],
                        ['text' => '2', 'correct' => false],
                        ['text' => 'x^3/3', 'correct' => false],
                    ]
                ],
                [
                    'text' => 'What is the value of Pi to 2 decimal places?',
                    'topic' => 'Geometry',
                    'options' => [
                        ['text' => '3.14', 'correct' => true],
                        ['text' => '3.15', 'correct' => false],
                        ['text' => '3.12', 'correct' => false],
                        ['text' => '3.16', 'correct' => false],
                    ]
                ]
            ]);
        }
    }
    
    private function createQuestions($paper, $questions)
    {
        $order = 1;
        foreach ($questions as $q) {
            $question = Question::create([
                'paper_id' => $paper->id,
                'question_text' => $q['text'],
                'topic_tag' => $q['topic'],
                'order_index' => $order++,
            ]);
            
            $optOrder = 1;
            foreach ($q['options'] as $o) {
                Option::create([
                    'question_id' => $question->id,
                    'option_text' => $o['text'],
                    'is_correct' => $o['correct'],
                    'order_index' => $optOrder++,
                ]);
            }
        }
    }
}
