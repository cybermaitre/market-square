<?php

namespace App\Policies;

use App\Models\Product;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ProductPolicy
{
    public function create(User $user): Response
    {
        if ($user->store === null) {
            return Response::deny('Create your store before adding products.');
        }

        return Response::allow();
    }

    public function view(User $user, Product $product): bool
    {
        return $this->ownsProduct($user, $product);
    }

    public function update(User $user, Product $product): bool
    {
        return $this->ownsProduct($user, $product);
    }

    public function delete(User $user, Product $product): bool
    {
        return $this->ownsProduct($user, $product);
    }

    private function ownsProduct(User $user, Product $product): bool
    {
        return $user->store !== null && $user->store->id === $product->store_id;
    }
}