<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Product\BrowseProductsRequest;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use App\Models\Store;

class PublicProductController extends Controller
{
    public function index(BrowseProductsRequest $request)
    {
        $search = $request->validated('search');
        $category = $request->validated('category');

        $query = Product::with('store');

        if (filled($search)) {
            $query->where('name', 'like', '%' . $search . '%');
        }

        if (filled($category)) {
            $query->whereHas('store', function ($storeQuery) use ($category) {
                $storeQuery->where('category', $category);
            });
        }

        $products = $query->orderBy('name')->orderBy('id')->paginate(20)->withQueryString();

        $items = ProductResource::collection($products)->resolve();

        return $this->paginated($items, $products, 'Products retrieved successfully.');
    }

    public function show(Product $product)
    {
        $product->load('store');

        return $this->success(new ProductResource($product));
    }

    public function byStore(Store $store)
    {
        $products = $store->products()->orderBy('name')->orderBy('id')->paginate(20);

        $items = ProductResource::collection($products)->resolve();

        return $this->paginated($items, $products, 'Store products retrieved successfully.');
    }
}
