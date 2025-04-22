<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
<<<<<<< HEAD
=======
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;
>>>>>>> 231977c8c8cc7dfc8f6b499ce1a4fff2b8175808

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
<<<<<<< HEAD
     * @param  string  $roles
     * @return mixed
     */
    public function handle(Request $request, Closure $next, string $roles)
    {
        if (!$request->user()) {
            return redirect('login');
        }

        $roles = explode('|', $roles);
        
        if ($request->user()->hasAnyRole($roles)) {
            return $next($request);
        }

=======
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
            // Split the roles string into an array
            $rolesArray = array_map('trim', explode(',', $roles));

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

>>>>>>> 231977c8c8cc7dfc8f6b499ce1a4fff2b8175808
        abort(403, 'Unauthorized action.');
    }
}
