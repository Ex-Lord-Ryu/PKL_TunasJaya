<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
class superadmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();
        Log::info('Auth Check: ' . (Auth::check() ? 'true' : 'false'));
        Log::info('User ID: ' . ($user ? $user->id : 'none'));
        Log::info('User Role: ' . ($user ? $user->role : 'none'));
        Log::info('Route: ' . $request->route()->getName());

        if (Auth::check() && $user->role === 'superadmin') {
            return $next($request);
        }

        abort(403, 'This action is unauthorized. Role: ' . ($user ? $user->role : 'none'));
    }
}
