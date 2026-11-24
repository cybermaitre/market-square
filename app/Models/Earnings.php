<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['user_id', 'amount_earned', 'amount_withdrawn'])]
class Earnings extends Model
{
    protected function casts(): array
    {
        return [
            'amount_earned' => 'decimal:2',
            'amount_withdrawn' => 'decimal:2',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // What the vendor can still withdraw
    public function balance(): float
    {
        return round((float) $this->amount_earned - (float) $this->amount_withdrawn, 2);
    }
}
