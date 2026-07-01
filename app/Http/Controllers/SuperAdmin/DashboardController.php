<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\LoginLog;

class DashboardController extends Controller
{
    public function index()
    {
        
    $search = request('search');
$from = request('from');
$to = request('to');



$todayLogins = LoginLog::whereDate(
    'login_at',
    today()
)->count();

$weekLogins = LoginLog::where(
    'login_at',
    '>=',
    now()->startOfWeek()
)->count();

$monthLogins = LoginLog::where(
    'login_at',
    '>=',
    now()->startOfMonth()
)->count();

$totalUsers = User::count();  


$dailyLogins = LoginLog::selectRaw("
        DATE(login_at) as day,
        COUNT(*) as total
    ")
    ->where(
        'login_at',
        '>=',
        now()->subDays(7)
    )
    ->groupBy('day')
    ->orderBy('day')
    ->get();


    $topUsers = LoginLog::selectRaw(
        'user_id, COUNT(*) as total'
    )
    ->with('user')
    ->groupBy('user_id')
    ->orderByDesc('total')
    ->take(5)
    ->get();
    
    

$admins = User::where('role', 'admin')
            ->whereNull('deleted_at')
            ->with(['units.staff'])
            ->get();

            $roleStats = User::selectRaw('role, COUNT(*) as total')
    ->groupBy('role')
    ->get();

        $activeUsers = DB::table('sessions')
            ->join('users', 'sessions.user_id', '=', 'users.id')
            ->select(
                'users.id',
                'users.name',
                'users.email',
                'users.phone',
                'users.role',
                'users.is_active',
                'sessions.ip_address',
                'sessions.user_agent',
                'sessions.last_activity',
                'sessions.id as session_id'
            )
            ->orderByDesc('sessions.last_activity')
            ->get();

            $loginLogs = \App\Models\LoginLog::with('user')

    ->when($search, function ($query) use ($search) {
        $query->whereHas('user', function ($q) use ($search) {
            $q->where('name', 'like', "%{$search}%")
              ->orWhere('email', 'like', "%{$search}%")
              ->orWhere('phone', 'like', "%{$search}%");
        });
    })

    ->when($from, function ($query) use ($from) {
        $query->whereDate('login_at', '>=', $from);
    })

    ->when($to, function ($query) use ($to) {
        $query->whereDate('login_at', '<=', $to);
    })

    ->latest('login_at')
    ->paginate(10)
    ->withQueryString();

        return view('super_admin.dashboard', [
            'admins' => $admins,
            'activeUsers' => $activeUsers,
            'loginLogs' => $loginLogs,
            'todayLogins' => $todayLogins,
            'weekLogins' => $weekLogins,
            'monthLogins' => $monthLogins,
            'totalUsers' => $totalUsers,
            'dailyLogins' => $dailyLogins,
            'roleStats' => $roleStats,
            'topUsers' => $topUsers,
        ]);
    }



    public function sessionDetails($sessionId)
{
    $session = DB::table('sessions')
        ->join('users', 'sessions.user_id', '=', 'users.id')
        ->where('sessions.id', $sessionId)
        ->select(
            'sessions.*',
            'users.name',
            'users.email',
            'users.phone',
            'users.role'
        )
        ->first();

    return response()->json($session);
}



public function terminateSession($sessionId)
{
    DB::table('sessions')
        ->where('id', $sessionId)
        ->delete();

    return back()->with(
        'success',
        'Session terminated successfully.'
    );
}



}