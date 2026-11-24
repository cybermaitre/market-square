<?php

namespace App\Http\Controllers\Api;

use App\Enums\OrderStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\VendorOrder\ListVendorOrdersRequest;
use App\Http\Requests\VendorOrder\UpdateVendorOrderStatusRequest;
use App\Http\Resources\VendorOrderResource;
use App\Models\VendorOrder;
use App\Services\VendorOrderService;

class VendorOrderController extends Controller
{
    protected VendorOrderService $vendorOrderService;

    public function __construct(VendorOrderService $vendorOrderService)
    {
        $this->vendorOrderService = $vendorOrderService;
    }

    public function index(ListVendorOrdersRequest $request)
    {
        $query = VendorOrder::with('orderItem.order.buyer')
            ->where('user_id', $request->user()->id);

        $status = $request->validated('status');

        if (filled($status)) {
            $query->where('status', $status);
        }

        $vendorOrders = $query->latest('id')->paginate(20)->withQueryString();

        $items = VendorOrderResource::collection($vendorOrders)->resolve();

        return $this->paginated($items, $vendorOrders, 'Orders retrieved successfully.');
    }

    public function show(VendorOrder $vendorOrder)
    {
        $this->authorize('view', $vendorOrder);

        $vendorOrder->load('orderItem.order.buyer');

        return $this->success(new VendorOrderResource($vendorOrder));
    }

    public function updateStatus(UpdateVendorOrderStatusRequest $request, VendorOrder $vendorOrder)
    {
        $this->authorize('updateStatus', $vendorOrder);

        $newStatus = OrderStatus::from($request->validated('status'));

        $updated = $this->vendorOrderService->updateStatus($vendorOrder, $newStatus);

        return $this->success(new VendorOrderResource($updated), 'Order status updated.');
    }
}
