<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['user_id', 'session_id', 'paper_id', 'started_at', 'completed_at', 'score', 'total_questions'])]
class Attempt extends Model
{
    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'started_at' => 'datetime',
            'completed_at' => 'datetime',
        ];
    }

    /**
     * Get the user who made this attempt.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the paper being attempted.
     */
    public function paper(): BelongsTo
    {
        return $this->belongsTo(Paper::class);
    }

    /**
     * Get the answers for this attempt.
     */
    public function answers(): HasMany
    {
        return $this->hasMany(AttemptAnswer::class);
    }

    /**
     * Calculate the score percentage.
     */
    public function scorePercentage(): ?float
    {
        if ($this->total_questions === 0 || $this->score === null) {
            return null;
        }

        return round(($this->score / $this->total_questions) * 100, 1);
    }
}
