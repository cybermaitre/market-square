<?php

namespace App\Services;

use App\Enums\OrderStatus;
use App\Models\Earnings;
use App\Models\VendorOrder;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class VendorOrderService
{
    public function updateStatus(VendorOrder $vendorOrder, OrderStatus $newStatus): VendorOrder
    {
        return DB::transaction(function () use ($vendorOrder, $newStatus) {
            $locked = VendorOrder::with('orderItem')
                ->whereKey($vendorOrder->id)
                ->lockForUpdate()
                ->first();

            $expected = $locked->status->next();

            if ($expected === null) {
                throw ValidationException::withMessages([
                    'status' => 'This order has already been delivered and cannot be changed.',
                ]);
            }

            if ($newStatus !== $expected) {
                throw ValidationException::withMessages([
                    'status' => "This order is {$locked->status->value}. The next status must be {$expected->value}.",
                ]);
            }

            $locked->status = $newStatus;

            if ($newStatus === OrderStatus::Shipped) {
                $locked->shipped_at = now();
            }

            if ($newStatus === OrderStatus::Delivered) {
                $locked->delivered_at = now();

                Earnings::firstOrCreate(['user_id' => $locked->user_id]);

                Earnings::where('user_id', $locked->user_id)
                    ->increment('amount_earned', (float) $locked->orderItem->vendor_earning);
            }

            $locked->save();

            return $locked->load('orderItem.order.buyer');
        });
    }
}