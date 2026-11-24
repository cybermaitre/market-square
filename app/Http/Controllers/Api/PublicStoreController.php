<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\StoreResource;
use App\Models\Store;

class PublicStoreController extends Controller
{
    public function index()
    {
        $stores = Store::orderBy('name')->paginate(20);

        $items = StoreResource::collection($stores)->resolve();

        return $this->paginated($items, $stores, 'Stores retrieved successfully.');
    }

    public function show(Store $store)
    {
        return $this->success(new StoreResource($store));
    }
}
