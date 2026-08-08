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

        // PRIOR PERIOD
        $priorDateThreshold = match($this->timeRange) {
            '7' => now()->subDays(14),
            '30' => now()->subDays(60),
            '90' => now()->subDays(180),
            default => now()->subDays(60),
        };

        // KPI: Revenue
        $revenue = Purchase::where('status', 'completed')
            ->where('created_at', '>=', $dateThreshold)
            ->sum('amount_paid');
            
        $priorRevenue = Purchase::where('status', 'completed')
            ->whereBetween('created_at', [$priorDateThreshold, $dateThreshold])
            ->sum('amount_paid');
            
        $revenueGrowth = $priorRevenue > 0 ? round((($revenue - $priorRevenue) / $priorRevenue) * 100) : 0;

        // KPI: New Students
        $newStudents = User::where('is_admin', false)
            ->where('created_at', '>=', $dateThreshold)
            ->count();
            
        $priorNewStudents = User::where('is_admin', false)
            ->whereBetween('created_at', [$priorDateThreshold, $dateThreshold])
            ->count();
            
        $studentsGrowth = $priorNewStudents > 0 ? round((($newStudents - $priorNewStudents) / $priorNewStudents) * 100) : 0;

        // KPI: Papers Attempted
        $papersAttempted = Attempt::where('created_at', '>=', $dateThreshold)
            ->count();
            
        $priorPapersAttempted = Attempt::whereBetween('created_at', [$priorDateThreshold, $dateThreshold])
            ->count();
            
        $attemptsGrowth = $priorPapersAttempted > 0 ? round((($papersAttempted - $priorPapersAttempted) / $priorPapersAttempted) * 100) : 0;
        
        // Attempts by level
        $totalAttempts = $papersAttempted > 0 ? $papersAttempted : 1;
        $olAttempts = DB::table('attempts')
            ->join('papers', 'attempts.paper_id', '=', 'papers.id')
            ->join('subjects', 'papers.subject_id', '=', 'subjects.id')
            ->where('attempts.created_at', '>=', $dateThreshold)
            ->where('subjects.level', 'ol')
            ->count();
            
        $alAttempts = DB::table('attempts')
            ->join('papers', 'attempts.paper_id', '=', 'papers.id')
            ->join('subjects', 'papers.subject_id', '=', 'subjects.id')
            ->where('attempts.created_at', '>=', $dateThreshold)
            ->where('subjects.level', 'al')
            ->count();
            
        $scholarshipAttempts = DB::table('attempts')
            ->join('papers', 'attempts.paper_id', '=', 'papers.id')
            ->join('subjects', 'papers.subject_id', '=', 'subjects.id')
            ->where('attempts.created_at', '>=', $dateThreshold)
            ->where('subjects.level', 'scholarship')
            ->count();
            
        $attemptsByLevel = [
            'ol' => round(($olAttempts / $totalAttempts) * 100),
            'al' => round(($alAttempts / $totalAttempts) * 100),
            'scholarship' => round(($scholarshipAttempts / $totalAttempts) * 100),
        ];

        // Revenue Trend (12 buckets)
        $days = (int) $this->timeRange;
        $bucketSize = max(1, floor($days / 12));
        $revenueTrend = [];
        $maxBucketRevenue = 0;
        
        for ($i = 11; $i >= 0; $i--) {
            $start = now()->subDays(($i + 1) * $bucketSize);
            $end = now()->subDays($i * $bucketSize);
            if ($i == 0) $end = now(); // Ensure last bucket goes up to now
            
            $bucketRevenue = Purchase::where('status', 'completed')
                ->whereBetween('created_at', [$start, $end])
                ->sum('amount_paid');
                
            $revenueTrend[] = $bucketRevenue;
            if ($bucketRevenue > $maxBucketRevenue) {
                $maxBucketRevenue = $bucketRevenue;
            }
        }
        
        // Convert trend to percentages for the chart height
        $revenueTrendHeights = array_map(function($val) use ($maxBucketRevenue) {
            return $maxBucketRevenue > 0 ? round(($val / $maxBucketRevenue) * 100) : 0;
        }, $revenueTrend);

        // Top Papers by Attempts
        $topPapers = Paper::withCount(['attempts' => function($q) use ($dateThreshold) {
                $q->where('created_at', '>=', $dateThreshold);
            }])
            ->orderByDesc('attempts_count')
            ->take(5)
            ->get();

        // Weakest Topics
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
            
        // Purchased count (distinct users who made a purchase)
        $purchasedCount = Purchase::where('status', 'completed')
            ->where('created_at', '>=', $dateThreshold)
            ->distinct('user_id')
            ->count();

        return view('livewire.admin.analytics', [
            'revenue' => $revenue,
            'revenueGrowth' => $revenueGrowth,
            'newStudents' => $newStudents,
            'studentsGrowth' => $studentsGrowth,
            'papersAttempted' => $papersAttempted,
            'attemptsGrowth' => $attemptsGrowth,
            'attemptsByLevel' => $attemptsByLevel,
            'revenueTrendHeights' => $revenueTrendHeights,
            'purchasedCount' => $purchasedCount,
            'topPapers' => $topPapers,
            'weakestTopics' => $weakestTopics,
        ]);
    }
}
