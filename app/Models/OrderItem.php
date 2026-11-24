<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

#[Fillable([
    'order_id',
    'product_id',
    'store_id',
    'product_name',
    'quantity',
    'price',
    'line_total',
    'commission_rate',
    'commission_amount',
    'vendor_earning',
])]
class OrderItem extends Model
{
    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'line_total' => 'decimal:2',
            'commission_rate' => 'decimal:2',
            'commission_amount' => 'decimal:2',
            'vendor_earning' => 'decimal:2',
        ];
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function store(): BelongsTo
    {
        return $this->belongsTo(Store::class);
    }

    public function vendorOrder(): HasOne
    {
        return $this->hasOne(VendorOrder::class);
    }
}
