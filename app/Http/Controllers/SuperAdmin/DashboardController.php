<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
{
    $admins = User::where('role', 'admin')
        ->whereNull('deleted_at')
        ->with(['units.staff'])
        ->get();

    return view('super_admin.dashboard', compact('admins'));
}

}
