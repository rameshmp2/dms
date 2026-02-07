<?php
// app/Http/Middleware/SetUserTimezone.php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class SetUserTimezone
{
    /**
     * Handle an incoming request.
     */
    public function handle($request, Closure $next)
    {
        if (Auth::check()) {
            $userTimezone = Auth::user()->timezone ?? config('app.timezone');
            
            // Set timezone for Carbon
            Carbon::setToStringFormat('Y-m-d H:i:s');
            date_default_timezone_set($userTimezone);
            
            // Make timezone available in views
            view()->share('userTimezone', $userTimezone);
        }

        return $next($request);
    }
}