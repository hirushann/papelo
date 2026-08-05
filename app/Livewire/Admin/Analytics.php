<?php

namespace App\Livewire\Admin;

use App\Models\User;
use App\Models\Paper;
use App\Models\Attempt;
use App\Models\Purchase;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('layouts.admin', ['header' => 'Analytics'])]
class Analytics extends Component
{
    public $timeRange = '30';

    public function render()
    {
        $dateThreshold = match($this->timeRange) {
            '7' => now()->subDays(7),
            '30' => now()->subDays(30),
            '90' => now()->subDays(90),
            default => now()->subDays(30),
        };

        // KPI: Revenue
        $revenue = Purchase::where('status', 'completed')
            ->where('created_at', '>=', $dateThreshold)
            ->sum('amount_paid');

        // KPI: New Students
        $newStudents = User::where('is_admin', false)
            ->where('created_at', '>=', $dateThreshold)
            ->count();

        // KPI: Papers Attempted
        $papersAttempted = Attempt::where('created_at', '>=', $dateThreshold)
            ->count();

        // Top Papers by Attempts
        $topPapers = Paper::withCount(['attempts' => function($q) use ($dateThreshold) {
                $q->where('created_at', '>=', $dateThreshold);
            }])
            ->orderByDesc('attempts_count')
            ->take(5)
            ->get();

        // Weakest Topics
        // We'll calculate this by joining attempt_answers with questions to get the topic tag
        // and taking the average of is_correct.
        $weakestTopics = DB::table('attempt_answers')
            ->join('questions', 'attempt_answers.question_id', '=', 'questions.id')
            ->select('questions.topic_tag', DB::raw('avg(attempt_answers.is_correct) * 100 as score'))
            ->whereNotNull('questions.topic_tag')
            ->where('questions.topic_tag', '!=', '')
            ->where('attempt_answers.created_at', '>=', $dateThreshold)
            ->groupBy('questions.topic_tag')
            ->orderBy('score')
            ->take(6)
            ->get();

        return view('livewire.admin.analytics', [
            'revenue' => collect([$revenue])->sum(), // Cast properly
            'newStudents' => $newStudents,
            'papersAttempted' => $papersAttempted,
            'topPapers' => $topPapers,
            'weakestTopics' => $weakestTopics,
        ]);
    }
}
