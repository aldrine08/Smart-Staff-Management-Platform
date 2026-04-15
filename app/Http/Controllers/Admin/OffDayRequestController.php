<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\OffDayRequest;
use Illuminate\Support\Facades\Auth;
use App\Models\Unit;
use App\Models\Department;
use Illuminate\Http\Request;

class OffDayRequestController extends Controller
{

public function index(Request $request)
{
    $adminId = Auth::id();

    // Start query
    $query = OffDayRequest::with(['user.unit', 'user.department'])
        ->whereHas('user.unit', function ($q) use ($adminId) {
            $q->where('admin_id', $adminId);
        });

    // 🔍 FILTER: Staff Name
    if ($request->filled('staff_name')) {
        $query->whereHas('user', function ($q) use ($request) {
            $q->where('name', 'like', '%' . $request->staff_name . '%');
        });
    }

    // 🔍 FILTER: Unit
    if ($request->unit_id) {
        $query->whereHas('user', function ($q) use ($request) {
            $q->where('unit_id', $request->unit_id);
        });
    }

    // 🔍 FILTER: Department
    if ($request->department_id) {
        $query->whereHas('user', function ($q) use ($request) {
            $q->where('department_id', $request->department_id);
        });
    }

    $offDayRequests = $query->latest()->get();

    // ✅ ONLY ADMIN'S UNITS
    $units = Unit::where('admin_id', $adminId)->get();

    // ✅ ONLY DEPARTMENTS BELONGING TO ADMIN STAFF
    $departments = Department::whereHas('users.unit', function ($q) use ($adminId) {
        $q->where('admin_id', $adminId);
    })->get();

    return view('admin.offdays.index', compact(
        'offDayRequests',
        'units',
        'departments'
    ));
}

}