<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Cart\AddToCartRequest;
use App\Http\Requests\Cart\UpdateCartItemRequest;
use App\Http\Resources\CartResource;
use App\Models\CartItem;
use App\Services\CartService;
use Illuminate\Http\Request;

class CartController extends Controller
{
    protected CartService $cartService;

    public function __construct(CartService $cartService)
    {
        $this->cartService = $cartService;
    }

    public function show(Request $request)
    {
        $cart = $this->cartService->getCart($request->user());

        return $this->success(new CartResource($cart), 'Cart retrieved successfully.');
    }

    public function addItem(AddToCartRequest $request)
    {
        $cart = $this->cartService->addItem(
            $request->user(),
            $request->validated('product_id'),
            $request->validated('quantity')
        );

        return $this->success(new CartResource($cart), 'Item added to cart.');
    }

    public function updateItem(UpdateCartItemRequest $request, CartItem $cartItem)
    {
        $this->authorize('update', $cartItem);

        $cart = $this->cartService->updateQuantity(
            $request->user(),
            $cartItem,
            $request->validated('quantity')
        );

        return $this->success(new CartResource($cart), 'Cart updated.');
    }

    public function removeItem(Request $request, CartItem $cartItem)
    {
        $this->authorize('delete', $cartItem);

        $cart = $this->cartService->removeItem($request->user(), $cartItem);

        return $this->success(new CartResource($cart), 'Item removed from cart.');
    }

    public function clear(Request $request)
    {
        $cart = $this->cartService->clear($request->user());

        return $this->success(new CartResource($cart), 'Cart cleared.');
    }
}