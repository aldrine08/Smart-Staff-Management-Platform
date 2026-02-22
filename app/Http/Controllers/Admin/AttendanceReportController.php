<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Exports\AttendanceExport;
use Maatwebsite\Excel\Facades\Excel;
use illuminate\Support\Facades\attendance;
use Maatwebsite\Excel\Concerns\FromQuery;

class AttendanceReportController extends Controller
{
    public function index(Request $request)
{
    $query = Attendance::with('user');

    if ($request->filled('from')) {
        $query->whereBetween('date', [$request->from, $request->to]);
    }

    return view('admin.reports', [
        'records' => $query->get()
    ]);
}

public function export(Request $request)
{
    return Excel::download(new AttendanceExport, 'attendance.xlsx');
}

}
