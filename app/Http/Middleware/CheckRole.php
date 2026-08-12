<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Restrict a route to one or more roles.
     *
     * Usage in routes/web.php:
     *   Route::middleware(['auth', 'role:Admin'])->group(...);
     *   Route::middleware(['auth', 'role:Admin,Supervisor'])->group(...);
     *
     * Role-agnostic to *how* the account was created (self-registration
     * vs. Staff Management "Add Staff" form) — both write to the same
     * `users` table and the same `role` column, so this single check
     * covers every account regardless of origin.
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = $request->user();

        if (! $user) {
            abort(401);
        }

        // Normalize case so 'admin', 'Admin', 'ADMIN' all match.
        $allowed = array_map('strtolower', $roles);

        if (! in_array(strtolower((string) $user->role), $allowed, true)) {
            abort(403, 'You do not have permission to access this page.');
        }

        return $next($request);
    }
}