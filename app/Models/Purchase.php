<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['user_id', 'paper_id', 'amount_paid', 'payhere_order_id', 'status'])]
class Purchase extends Model
{
    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'amount_paid' => 'decimal:2',
        ];
    }

    /**
     * Get the user who made this purchase.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the paper that was purchased.
     */
    public function paper(): BelongsTo
    {
        return $this->belongsTo(Paper::class);
    }
}
