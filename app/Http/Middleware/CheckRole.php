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
     * @param  string  $roles
     * @return mixed
     */
    public function handle(Request $request, Closure $next, string $roles): Response
    {
        $user = $request->user();
        
        if (!$user) {
            return redirect()->route('login');
        }

        // Split the roles string into an array and check each role
        $rolesArray = array_map('trim', explode(',', $roles));
        foreach ($rolesArray as $role) {
            if ($user->hasRole($role)) {
                return $next($request);
            }
        }

        // If we get here, the user doesn't have any of the required roles
        if ($request->expectsJson()) {
            return response()->json(['message' => 'Unauthorized.'], 403);
        }

        abort(403, 'Unauthorized action.');
    }
}
