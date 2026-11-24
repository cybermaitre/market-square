<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['user_id', 'total_price', 'delivery_address', 'payment_status'])]
class Order extends Model
{
    //
    protected function casts(): array
    {
        return [
            'total_price' => 'decimal:2',
        ];
    }
    public function buyer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function Items():HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

}
