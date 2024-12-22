<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        // Check if the user is authenticated
        if (!$request->user()) {
            return response()->json(['error' => 'Unauthorized: User not authenticated'], 401);
        }

        // Check if the authenticated user has one of the allowed roles
        if (!in_array($request->user()->role, $roles)) {
            return response()->json([
                'error' => 'Unauthorized',
                'message' => 'You do not have the required role. Allowed roles: ' . implode(', ', $roles)
            ], 403);
        }
        return $next($request);
    }
}
