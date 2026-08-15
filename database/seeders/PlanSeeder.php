<?php

namespace Database\Seeders;

use App\Models\Plan;
use Illuminate\Database\Seeder;

class PlanSeeder extends Seeder
{
    /**
     * Seed the three subscription tiers.
     *
     * After running this seeder, log in to your Lemon Squeezy dashboard,
     * create the matching product + variants, and update the ls_variant_id
     * values via the admin settings or directly in the database.
     */
    public function run(): void
    {
        $plans = [
            [
                'name' => 'Practice',
                'slug' => 'practice',
                'price' => 490.00,
                'paper_limit' => 15,
                'sort_order' => 1,
                'features' => [
                    'Up to 15 new papers a month',
                    'Instant MCQ & short-answer marking',
                    'Full attempt history',
                    'Per-attempt topic breakdown',
                ],
            ],
            [
                'name' => 'Progress',
                'slug' => 'progress',
                'price' => 990.00,
                'paper_limit' => null,
                'sort_order' => 2,
                'features' => [
                    'Everything in Practice, plus:',
                    'Unlimited papers',
                    'Full topic breakdown & trends over time',
                    'Suggested next paper',
                    'Priority access to new papers',
                ],
            ],
            [
                'name' => 'Pass',
                'slug' => 'pass',
                'price' => 1490.00,
                'paper_limit' => null,
                'sort_order' => 3,
                'features' => [
                    'Everything in Progress, plus:',
                    'Structured & self-marked papers (proofs, constructions)',
                    'Downloadable progress report',
                ],
            ],
        ];

        foreach ($plans as $plan) {
            Plan::updateOrCreate(
                ['slug' => $plan['slug']],
                $plan
            );
        }
    }
}
