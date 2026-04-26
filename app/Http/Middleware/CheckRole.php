<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        // Check if user is authenticated
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Please login to access this page.');
        }

        // Check if user has the required role
        if (Auth::user()->role !== $role) {
            // Return custom 403 view
            return response()->view('errors.403', [
                'exception' => (object)['message' => 'Unauthorized access. You do not have permission to view this page.']
            ], 403);
        }

        return $next($request);
    }
}
