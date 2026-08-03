<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['name', 'level', 'medium', 'slug'])]
class Subject extends Model
{
    /**
     * Get the papers for this subject.
     */
    public function papers(): HasMany
    {
        return $this->hasMany(Paper::class);
    }
}
