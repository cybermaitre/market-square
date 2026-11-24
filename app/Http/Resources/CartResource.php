<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CartResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
     {
        $items = $this->items;

        $total = $items->sum(fn ($item) => $item->product->price * $item->quantity);

        return [
            'id' => $this->id,
            'items' => CartItemResource::collection($items),
            'items_count' => $items->count(),
            'total_quantity' => $items->sum('quantity'),
            'total' => number_format($total, 2, '.', ''),
        ];
    }
}
