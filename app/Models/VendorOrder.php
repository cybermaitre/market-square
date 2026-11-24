<?php

namespace App\Models;

use App\Enums\OrderStatus;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['user_id', 'order_item_id', 'status', 'shipped_at', 'delivered_at'])]
class VendorOrder extends Model
{
    protected function casts(): array
    {
        return [
            'status' => OrderStatus::class,
            'shipped_at' => 'datetime',
            'delivered_at' => 'datetime',
        ];
    }

    public function orderItem(): BelongsTo
    {
        return $this->belongsTo(OrderItem::class);
    }

    // The vendor who fulfils this item
    public function vendor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}