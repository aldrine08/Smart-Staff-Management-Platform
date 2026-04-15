<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;


class SuperAdminMiddleware
{
  public function handle(Request $request, Closure $next)
{
    if (!auth()->check() || auth()->user()->role !== 'super_admin') {
        abort(403, 'Unauthorized');
    }

    // 🚨 OPTIONAL SAFETY: DO NOT HARD BLOCK SUPER ADMIN
    // Instead only warn or log (safer approach)

    if (auth()->user()->is_active == 0) {
        return redirect('/login')
            ->with('error', 'Super Admin account is disabled. Contact system database administrator.');
    }

    return $next($request);
}

}