<?php

namespace App\Policies;

use App\Models\Store;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class StorePolicy
{
    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): Response
    {
        if (! $user->isVendor()) {
            return Response::deny('Only vendors can create a store.');
        }

        if ($user->store !== null) {
            return Response::deny('You already have a store.');
        }

        return Response::allow();
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Store $store): bool
    {
        return $user->id === $store->user_id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Store $store): bool
    {
        return $user->id === $store->user_id;
    }

    
}
