<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Product\CreateProductRequest;
use App\Http\Requests\Product\UpdateProductRequest;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $store = $request->user()->store;

        if ($store === null) {
            return $this->notFound('You have not created a store yet.');
        }

        $products = $store->products()->orderBy('name')->paginate(20);

        $items = ProductResource::collection($products)->resolve();

        return $this->paginated($items, $products, 'Products retrieved successfully.');
    }

    public function store(CreateProductRequest $request)
    {
        $this->authorize('create', Product::class);

        $product = $request->user()->store->products()->create($request->validated());

        return $this->created(new ProductResource($product), 'Product created successfully.');
    }

    public function show(Product $product)
    {
        $this->authorize('view', $product);

        return $this->success(new ProductResource($product));
    }

    public function update(UpdateProductRequest $request, Product $product)
    {
        $this->authorize('update', $product);

        $product->update($request->validated());

        return $this->success(new ProductResource($product), 'Product updated successfully.');
    }

    public function destroy(Product $product)
    {
        $this->authorize('delete', $product);

        $product->delete();

        return $this->success(null, 'Product deleted successfully.');
    }
}
