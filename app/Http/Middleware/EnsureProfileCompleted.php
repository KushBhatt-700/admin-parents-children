<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class EnsureProfileCompleted
{
    public function handle($request, Closure $next)
    {
        $user = Auth::user();
        // Assumes users table has 'profile_completed' boolean
        if (!$user || !$user->profile_completed) {
            if ($request->is('profile/*') || $request->routeIs('profile.complete') || $request->is('logout')) {
                return $next($request);
            }
            return redirect()->route('profile.complete')->with('error','Please complete your profile before continuing.');
        }
        return $next($request);
    }
}
