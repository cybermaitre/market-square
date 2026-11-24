<?php

namespace App\Policies;

use App\Models\User;
use App\Models\VendorOrder;

class VendorOrderPolicy
{
    public function view(User $user, VendorOrder $vendorOrder): bool
    {
        return $user->id === $vendorOrder->user_id;
    }

    public function updateStatus(User $user, VendorOrder $vendorOrder): bool
    {
        return $user->id === $vendorOrder->user_id;
    }
}
