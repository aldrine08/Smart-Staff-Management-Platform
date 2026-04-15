<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
   public function store(LoginRequest $request): RedirectResponse
{
    $request->authenticate();
    $request->session()->regenerate();

    $user = Auth::user();

    // 🚫 BLOCK DEACTIVATED USERS
    if ($user->is_active == 0) {
        Auth::logout();
        return redirect('/login')->with('error', 'Your account is deactivated. Contact system admin.');
    }

    // ✅ SUPER ADMIN REDIRECT
    if ($user->role === 'super_admin') {
        return redirect()->route('super_admin.dashboard');
    }

    // ✅ ADMIN REDIRECT
    if ($user->role === 'admin') {
        return redirect()->route('admin.dashboard');
    }

    // ✅ DEFAULT STAFF REDIRECT
    return redirect()->route('staff.dashboard');
}

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
