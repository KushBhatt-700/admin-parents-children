<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class CheckProfileComplete
{
    public function handle($request, Closure $next)
    {
        // Skip check for logout route
        if ($request->routeIs('logout')) {
            return $next($request);
        }

        $user = Auth::user();

        // If profile NOT completed → redirect to profile page
        if ($user && !$user->profile_completed) {
            return redirect()->route('profile.complete')
                ->with('error', 'Please complete your profile first.');
        }

        return $next($request);
    }
}
