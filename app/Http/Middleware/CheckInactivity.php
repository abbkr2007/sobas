<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class CheckInactivity
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Exclude application submission route from inactivity check
        if ($request->is('application-submit')) {
            return $next($request);
        }

        // Only check inactivity for authenticated users
        if (Auth::check()) {
            $maxInactivityTime = config('session.inactivity_timeout', 60); // Default 60 minutes
            $lastActivityTime = Session::get('last_activity');
            $currentTime = time();

            // If there's a recorded last activity time
            if ($lastActivityTime) {
                $inactivityDuration = $currentTime - $lastActivityTime;

                // Check if inactivity timeout has been exceeded
                if ($inactivityDuration > ($maxInactivityTime * 60)) {
                    // Logout the user
                    Auth::logout();
                    Session::flush();
                    
                    return redirect()->route('login')->with('message', 'Your session has expired due to inactivity. Please login again.');
                }
            }

            // Update the last activity timestamp
            Session::put('last_activity', $currentTime);
        }

        return $next($request);
    }
}
