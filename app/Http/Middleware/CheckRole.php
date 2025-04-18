<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $roles
     * @return mixed
     */
    public function handle(Request $request, Closure $next, ?string $roles = null): Response
    {
        if (!$request->user()) {
            return redirect()->route('login');
        }

        // If no roles are specified, just check if user is authenticated
        if (!$roles) {
            return $next($request);
        }

        try {
            // Split the roles string into an array, handling both pipe and comma separators
            $rolesArray = array_map('trim', preg_split('/[,|]/', $roles));

            // Check if user has any of the required roles
            if ($request->user()->hasAnyRole($rolesArray)) {
                return $next($request);
            }
        } catch (\Exception $e) {
            Log::error("Error checking roles: " . $e->getMessage());
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Error checking authorization.'], 500);
            }
            abort(500, 'Error checking authorization.');
        }

        // If we get here, the user doesn't have any of the required roles
        if ($request->expectsJson()) {
            return response()->json(['message' => 'Unauthorized.'], 403);
        }

        abort(403, 'Unauthorized action.');
    }
}
