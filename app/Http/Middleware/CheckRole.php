<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
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
        if (!$request->user()) {
            return redirect()->route('login');
        }

        // Allow admin to access everything
        if ($request->user()->isAdmin()) {
            return $next($request);
        }

        // Split comma-separated roles
        $roleArray = array_map('trim', explode(',', $roles));

        foreach ($roleArray as $role) {
            $methodName = 'is' . ucfirst($role);
            if (method_exists($request->user(), $methodName) && $request->user()->$methodName()) {
                return $next($request);
            }
        }

        abort(403, 'Unauthorized action.');
    }
}
