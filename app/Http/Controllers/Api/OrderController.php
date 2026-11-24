<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Order\CheckoutRequest;
use App\Http\Resources\OrderResource;
use App\Models\Order;
use App\Services\CheckoutService;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    protected CheckoutService $checkoutService;

    public function __construct(CheckoutService $checkoutService)
    {
        $this->checkoutService = $checkoutService;
    }

    public function checkout(CheckoutRequest $request)
    {
        $order = $this->checkoutService->placeOrder(
            $request->user(),
            $request->validated('delivery_address')
        );

        return $this->created(new OrderResource($order), 'Order placed successfully.');
    }

    public function index(Request $request)
    {
        $orders = $request->user()
            ->orders()
            ->with('items.store', 'items.vendorOrder')
            ->latest('id')
            ->paginate(20);

        $items = OrderResource::collection($orders)->resolve();

        return $this->paginated($items, $orders, 'Orders retrieved successfully.');
    }

    public function show(Order $order)
    {
        $this->authorize('view', $order);

        $order->load('items.store', 'items.vendorOrder');

        return $this->success(new OrderResource($order));
    }
}
