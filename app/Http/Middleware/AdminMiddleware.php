<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;


class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
{
    if (!auth()->check() || auth()->user()->role !== 'admin') {
        abort(403, 'Unauthorized');
    }

    // 🚫 Block inactive admins
    if (auth()->user()->is_active == 0) {
        auth()->logout();
        return redirect('/login')
            ->with('error', 'Your admin account has been deactivated.');
    }

    return $next($request);
}

}
