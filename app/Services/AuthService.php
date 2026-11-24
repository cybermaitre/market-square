<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AuthService
{
    /**
     * Create the user (and a cart if they are a buyer), then issue a token.
     *
     * @return array{0: User, 1: string}
     */
    public function register(array $data): array
    {
        return DB::transaction(function () use ($data) {
            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'phone_number' => $data['phone_number'] ?? null,
                'password' => $data['password'],
                'role' => $data['role'],
            ]);

            // Every buyer gets exactly one cart
            if ($user->isBuyer()) {
                $user->cart()->create();
            }

            return [$user, $user->createToken('auth_token')->plainTextToken];
        });
    }

    /**
     * Check the credentials and issue a token.
     *
     * @return array{0: User, 1: string}|null  null when the credentials are wrong
     */
    public function login(array $credentials): ?array
    {
        $user = User::where('email', $credentials['email'])->first();

        if (! $user || ! Hash::check($credentials['password'], $user->password)) {
            return null;
        }

        return [$user, $user->createToken('auth_token')->plainTextToken];
    }

    // Revoke only the token used for this request
    public function logout(User $user): void
    {
        /** @var \Laravel\Sanctum\PersonalAccessToken $token */
        $token = $user->currentAccessToken();

        $token->delete();
    }
}