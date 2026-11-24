<?php

use App\Http\Controllers\Api\AuthController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\StoreController;
use App\Http\Controllers\Api\PublicStoreController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\PublicProductController;
use App\Http\Controllers\Api\CartController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\VendorOrderController;
use App\Http\Controllers\Api\EarningsController;

Route::post('/auth/register', [AuthController::class, 'register']);
Route::post('/auth/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/auth/logout', [AuthController::class, 'logout']);
    Route::get('/auth/me', [AuthController::class, 'me']);
});

//for public only
Route::get('/stores', [PublicStoreController::class, 'index']);
Route::get('/stores/{store:name}', [PublicStoreController::class, 'show']);
Route::get('/stores/{store:name}/products', [PublicProductController::class, 'byStore']);

Route::get('/products', [PublicProductController::class, 'index']);
Route::get('/products/{product}', [PublicProductController::class, 'show']);


//for vendors only
Route::middleware(['auth:sanctum', 'role:vendor'])->prefix('vendor')->group(function () {
    Route::get('/store', [StoreController::class, 'show']);
    Route::post('/store', [StoreController::class, 'store']);
    Route::match(['put', 'patch'], '/store', [StoreController::class, 'update']);
    Route::delete('/store', [StoreController::class, 'destroy']);


    Route::apiResource('products', ProductController::class);

    Route::get('/orders', [VendorOrderController::class, 'index']);
    Route::get('/orders/{vendorOrder}', [VendorOrderController::class, 'show']);
    Route::patch('/orders/{vendorOrder}/status', [VendorOrderController::class, 'updateStatus']);


    Route::get('/earnings', [EarningsController::class, 'show']);
});

//for carts
Route::middleware(['auth:sanctum', 'role:buyer'])->prefix('cart')->group(function () {
    Route::get('/', [CartController::class, 'show']);
    Route::delete('/', [CartController::class, 'clear']);
    Route::post('/items', [CartController::class, 'addItem']);
    Route::put('/items/{cartItem}', [CartController::class, 'updateItem']);
    Route::delete('/items/{cartItem}', [CartController::class, 'removeItem']);


    // for orders
    Route::post('/checkout', [OrderController::class, 'checkout']);
    Route::get('/orders', [OrderController::class, 'index']);
    Route::get('/orders/{order}', [OrderController::class, 'show']);
});
