<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderItemResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'product_id' => $this->product_id,
            'product_name' => $this->product_name,
            'store' => $this->whenLoaded('store', fn () => $this->store->name),
            'quantity' => $this->quantity,
            'price' => $this->price,
            'line_total' => $this->line_total,
            'status' => $this->vendorOrder?->status->value,
        ];
    }
}
