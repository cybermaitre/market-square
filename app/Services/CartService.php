<?php

namespace App\Services;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use App\Models\User;
use Illuminate\Validation\ValidationException;

class CartService
{
    public function getCart(User $user): Cart
    {
        $cart = $user->cart()->firstOrCreate();

        return $cart->load('items.product.store');
    }

    public function addItem(User $user, int $productId, int $quantity): Cart
    {
        $cart = $user->cart()->firstOrCreate();

        $product = Product::findOrFail($productId);

        $item = $cart->items()->where('product_id', $product->id)->first();

        $newQuantity = $quantity + ($item ? $item->quantity : 0);

        $this->checkStock($product, $newQuantity);

        if ($item) {
            $item->update(['quantity' => $newQuantity]);
        } else {
            $cart->items()->create([
                'product_id' => $product->id,
                'quantity' => $newQuantity,
            ]);
        }

        return $this->getCart($user);
    }

    public function updateQuantity(User $user, CartItem $item, int $quantity): Cart
    {
        $this->checkStock($item->product, $quantity);

        $item->update(['quantity' => $quantity]);

        return $this->getCart($user);
    }

    public function removeItem(User $user, CartItem $item): Cart
    {
        $item->delete();

        return $this->getCart($user);
    }

    public function clear(User $user): Cart
    {
        $user->cart()->firstOrCreate()->items()->delete();

        return $this->getCart($user);
    }

    private function checkStock(Product $product, int $quantity): void
    {
        if ($product->unit_available < 1) {
            throw ValidationException::withMessages([
                'quantity' => "\"{$product->name}\" is out of stock.",
            ]);
        }

        if ($quantity > $product->unit_available) {
            throw ValidationException::withMessages([
                'quantity' => "Only {$product->unit_available} unit(s) of \"{$product->name}\" are available.",
            ]);
        }
    }
}