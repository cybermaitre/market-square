<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Traits\ApiResponse;

class EnsureUserHasRole
{
    use ApiResponse;

    public function handle(Request $request, Closure $next, string $role)
    {
        $user = $request->user();

        if ($user === null || $user->role->value !== $role) {
            return $this->forbidden('You do not have permission to access this resource.');
        }

        return $next($request);
    }
}
