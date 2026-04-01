<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StaffMiddleware
{
   public function handle(Request $request, Closure $next)
{
    if (!Auth::check() || Auth::user()->role !== 'staff') {
        abort(403, 'Unauthorized');
    }

    // 🚫 Block inactive users
    if (Auth::user()->is_active == 0) {
        Auth::logout();
        return redirect('/login')->with('error', 'Your account has been deactivated. Contact admin.');
    }

    return $next($request);
}
}

