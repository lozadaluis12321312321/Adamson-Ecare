<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'Please log in to access this page.');
        }

        if (auth()->user()->role !== 'admin') {
            abort(403, 'Unauthorized action. You do not have administrator permissions to access this area.');
        }

        return $next($request);
    }
}
