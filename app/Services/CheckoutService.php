<?php

namespace App\Services;

use App\Models\Cart;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use App\Models\VendorOrder;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class CheckoutService
{
    public function placeOrder(User $user, string $deliveryAddress): Order
    {
        return DB::transaction(function () use ($user, $deliveryAddress) {
            $cart = Cart::where('user_id', $user->id)->lockForUpdate()->first();

            if ($cart === null) {
                throw ValidationException::withMessages([
                    'cart' => 'Your cart is empty.',
                ]);
            }

            $cartItems = $cart->items()->get();

            if ($cartItems->isEmpty()) {
                throw ValidationException::withMessages([
                    'cart' => 'Your cart is empty.',
                ]);
            }

            $products = Product::with('store')
                ->whereIn('id', $cartItems->pluck('product_id'))
                ->orderBy('id')
                ->lockForUpdate()
                ->get()
                ->keyBy('id');

            $problems = [];

            foreach ($cartItems as $cartItem) {
                $product = $products->get($cartItem->product_id);

                if ($product === null) {
                    $problems[] = 'A product in your cart is no longer available.';
                    continue;
                }

                if ($product->unit_available < 1) {
                    $problems[] = "\"{$product->name}\" is out of stock.";
                } elseif ($product->unit_available < $cartItem->quantity) {
                    $problems[] = "Only {$product->unit_available} unit(s) of \"{$product->name}\" are available, but you have {$cartItem->quantity} in your cart.";
                }
            }

            if (! empty($problems)) {
                throw ValidationException::withMessages([
                    'cart' => $problems,
                ]);
            }

            $rate = (float) config('marketplace.commission_rate');
            $lines = [];
            $totalKobo = 0;

            foreach ($cartItems as $cartItem) {
                $product = $products->get($cartItem->product_id);

                $priceKobo = (int) round($product->price * 100);
                $lineKobo = $priceKobo * $cartItem->quantity;
                $commissionKobo = (int) round($lineKobo * $rate / 100);

                $lines[] = [
                    'product' => $product,
                    'quantity' => $cartItem->quantity,
                    'price_kobo' => $priceKobo,
                    'line_kobo' => $lineKobo,
                    'commission_kobo' => $commissionKobo,
                ];

                $totalKobo += $lineKobo;
            }

            $order = Order::create([
                'user_id' => $user->id,
                'total_price' => $this->toNaira($totalKobo),
                'delivery_address' => $deliveryAddress,
                'payment_status' => 'paid',
            ]);

                       foreach ($lines as $line) {
                $product = $line['product'];
                $earningKobo = $line['line_kobo'] - $line['commission_kobo'];

                $orderItem = $order->items()->create([
                    'product_id' => $product->id,
                    'store_id' => $product->store_id,
                    'product_name' => $product->name,
                    'quantity' => $line['quantity'],
                    'price' => $this->toNaira($line['price_kobo']),
                    'line_total' => $this->toNaira($line['line_kobo']),
                    'commission_rate' => $rate,
                    'commission_amount' => $this->toNaira($line['commission_kobo']),
                    'vendor_earning' => $this->toNaira($earningKobo),
                ]);

                VendorOrder::create([
                    'user_id' => $product->store->user_id,
                    'order_item_id' => $orderItem->id,
                ]);

                Product::where('id', $product->id)->decrement('unit_available', $line['quantity']);
            }

            $cart->items()->delete();

            return $order->load('items.store', 'items.vendorOrder');
        });
    }

    private function toNaira(int $kobo): string
    {
        return number_format($kobo / 100, 2, '.', '');
    }
}