<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class VendorOrderResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $item = $this->orderItem;
        $order = $item->order;

        return [
            'id' => $this->id,
            'status' => $this->status->value,
            'item' => [
                'product_name' => $item->product_name,
                'quantity' => $item->quantity,
                'price' => $item->price,
                'line_total' => $item->line_total,
                'commission_rate' => $item->commission_rate,
                'commission_amount' => $item->commission_amount,
                'your_earning' => $item->vendor_earning,
            ],
            'order' => [
                'id' => $order->id,
                'placed_at' => $order->created_at,
                'delivery_address' => $order->delivery_address,
            ],
            'buyer' => [
                'name' => $order->buyer->name,
                'phone_number' => $order->buyer->phone_number,
            ],
            'shipped_at' => $this->shipped_at,
            'delivered_at' => $this->delivered_at,
            'created_at' => $this->created_at,
        ];
    }
}
