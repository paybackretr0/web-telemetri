<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserHasRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  $role
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        if (!$request->user() || $request->user()->role !== $role) {
            // Redirect based on user's actual role if they're logged in
            if ($request->user()) {
                if ($request->user()->role === 'admin') {
                    return redirect('/admin/dashboard');
                }
                return redirect('/pengurus/dashboard');
            }
            
            // Not logged in, redirect to login
            return redirect('/login');
        }

        return $next($request);
    }
}