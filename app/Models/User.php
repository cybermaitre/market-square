<?php

namespace App\Models;

use App\Enums\UserRole;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

#[Fillable(['name', 'email', 'password', 'role', 'phone_number'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasApiTokens, HasFactory, Notifiable;

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'role' => UserRole::class,
        ];
    }

    public function isAdmin(): bool
    {
        return $this->role === UserRole::Admin;
    }

    public function isVendor(): bool
    {
        return $this->role === UserRole::Vendor;
    }

    public function isBuyer(): bool
    {
        return $this->role === UserRole::Buyer;
    }

    // A vendor has one store
    public function store(): HasOne
    {
        return $this->hasOne(Store::class);
    }

    // A buyer has one cart
    public function cart(): HasOne
    {
        return $this->hasOne(Cart::class);
    }

    // Orders placed by this user as a buyer
    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    // A vendor's fulfilment records
    public function vendorOrders(): HasMany
    {
        return $this->hasMany(VendorOrder::class);
    }

    // A vendor's earnings row
    public function earnings(): HasOne
    {
        return $this->hasOne(Earnings::class);
    }
}