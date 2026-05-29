<?php

namespace App\Http\Middleware;

use App\Enums\UserRole;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        if (! $request->user()) {
            return redirect()->route('login');
        }

        $userRole = $request->user()->role;

        foreach ($roles as $role) {
            // Handle both string and enum comparison
            if ($userRole instanceof UserRole) {
                // If user role is enum, compare with enum value
                if ($userRole->value === $role) {
                    return $next($request);
                }
            } else {
                // If user role is string, compare directly
                if ($userRole === $role) {
                    return $next($request);
                }
            }
        }

        abort(403, 'Anda tidak memiliki akses ke halaman ini.');
    }
}
