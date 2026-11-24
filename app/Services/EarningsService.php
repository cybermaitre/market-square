<?php

namespace App\Services;

use App\Enums\OrderStatus;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class EarningsService
{
    public function summary(User $vendor): array
    {
        $earning = DB::table('earnings')
            ->where('user_id', $vendor->id)
            ->selectRaw('amount_earned, amount_withdrawn, (amount_earned - amount_withdrawn) as balance')
            ->first();

        $delivered = DB::table('vendor_orders')
            ->join('order_items', 'order_items.id', '=', 'vendor_orders.order_item_id')
            ->where('vendor_orders.user_id', $vendor->id)
            ->where('vendor_orders.status', OrderStatus::Delivered->value)
            ->selectRaw('COALESCE(SUM(order_items.line_total), 0) as sales')
            ->selectRaw('COALESCE(SUM(order_items.commission_amount), 0) as commission')
            ->selectRaw('COALESCE(SUM(order_items.vendor_earning), 0) as earnings')
            ->first();

        $pending = DB::table('vendor_orders')
            ->join('order_items', 'order_items.id', '=', 'vendor_orders.order_item_id')
            ->where('vendor_orders.user_id', $vendor->id)
            ->where('vendor_orders.status', '!=', OrderStatus::Delivered->value)
            ->selectRaw('COALESCE(SUM(order_items.vendor_earning), 0) as earnings')
            ->first();

        $counts = DB::table('vendor_orders')
            ->where('user_id', $vendor->id)
            ->selectRaw('status, COUNT(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        $ordersByStatus = [];

        foreach (OrderStatus::cases() as $status) {
            $ordersByStatus[$status->value] = (int) $counts->get($status->value, 0);
        }

        return [
            'balance' => $this->money($earning?->balance),
            'total_earned' => $this->money($earning?->amount_earned),
            'total_withdrawn' => $this->money($earning?->amount_withdrawn),
            'pending_earnings' => $this->money($pending->earnings),
            'sales' => [
                'gross_sales' => $this->money($delivered->sales),
                'commission_deducted' => $this->money($delivered->commission),
                'net_earnings' => $this->money($delivered->earnings),
            ],
            'orders_by_status' => $ordersByStatus,
        ];
    }

    private function money(string|int|float|null $value): string
    {
        return number_format((float) $value, 2, '.', '');
    }
}