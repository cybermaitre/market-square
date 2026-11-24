<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Store\CreateStoreRequest;
use App\Http\Requests\Store\UpdateStoreRequest;
use App\Http\Resources\StoreResource;
use App\Models\Store;
use Illuminate\Http\Request;

class StoreController extends Controller
{
    public function show(Request $request)
    {
        $store = $request->user()->store;

        if ($store === null) {
            return $this->notFound('You have not created a store yet.');
        }

        return $this->success(new StoreResource($store));
    }

    public function store(CreateStoreRequest $request)
    {
        $this->authorize('create', Store::class);

        $store = $request->user()->store()->create($request->validated());

        return $this->created(new StoreResource($store), 'Store created successfully.');
    }

    public function update(UpdateStoreRequest $request)
    {
        $store = $request->user()->store;

        if ($store === null) {
            return $this->notFound('You have not created a store yet.');
        }

        $this->authorize('update', $store);

        $store->update($request->validated());

        return $this->success(new StoreResource($store), 'Store updated successfully.');
    }

    public function destroy(Request $request)
    {
        $store = $request->user()->store;

        if ($store === null) {
            return $this->notFound('You have not created a store yet.');
        }

        $this->authorize('delete', $store);

        if ($store->orderItems()->exists()) {
            return $this->error('This store has sales and cannot be deleted.', 409);
        }

        $store->delete();

        return $this->success(null, 'Store deleted successfully.');
    }
}
