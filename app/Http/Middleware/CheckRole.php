<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

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
    public function handle(Request $request, Closure $next, string $roles)
    {
        if (!$request->user()) {
            return redirect('login');
        }

        $roles = explode('|', $roles);
        
        if ($request->user()->hasAnyRole($roles)) {
            return $next($request);
        }

        abort(403, 'Unauthorized action.');
    }
}
