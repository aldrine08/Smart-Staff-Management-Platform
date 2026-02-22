<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Attendance;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\AttendanceNotification;
use App\Mail\ClockOutMail;
use App\Mail\ClockInMail;
use App\Models\ClockInSetting;
use App\Models\User;
use App\Http\Controllers\Controller;
use App\Models\Unit;
use App\Models\Department;
use App\Exports\AttendanceExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Mail\AttendanceExportMail;
use Illuminate\Support\Facades\Storage;




class AttendanceController extends Controller
{

public function index(Request $request)
    {
        // Get filters from request
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $unitId = $request->input('unit_id');
        $departmentId = $request->input('department_id');

        // Base query: staff with attendances
        $query = Attendance::with(['user.unit', 'user.department'])
                           ->whereHas('user', fn($q) => $q->where('role', 'staff'));

        // Apply date range filter
        if ($startDate && $endDate) {
            $query->whereBetween('date', [$startDate, $endDate]);
        }

        // Apply unit filter
        if ($unitId) {
            $query->whereHas('user', fn($q) => $q->where('unit_id', $unitId));
        }

        // Apply department filter
        if ($departmentId) {
            $query->whereHas('user', fn($q) => $q->where('department_id', $departmentId));
        }

        $attendances = $query->orderBy('date', 'desc')->get();

        // Pass units and departments for filter dropdowns
        $units = Unit::all();
        $departments = Department::all();

        return view('admin.attendance.index', compact('attendances', 'units', 'departments', 'startDate', 'endDate', 'unitId', 'departmentId'));
    }
public function clockIn()
{
    $user = Auth::user();
    $today = Carbon::now();
    $weekday = $today->format('D'); // Mon, Tue, ...

    $setting = ClockInSetting::first();
    $standardClockIn = $setting ? Carbon::parse($setting->start_time) : Carbon::createFromTime(8,10,0);
    $workingDays = $setting->working_days ?? ['Mon','Tue','Wed','Thu','Fri'];

    // Check if today is a working day
    if (!in_array($weekday, $workingDays)) {
        return back()->with('error', 'Today is not a working day.');
    }

    $attendance = Attendance::firstOrCreate(
        ['user_id' => $user->id, 'date' => $today->toDateString()],
        ['clock_in' => now()]
    );

    if ($attendance->clock_in) {
        return back()->with('error', 'You have already clocked in today.');
    }

    $clockInTime = now();
    $status = $clockInTime->gt($standardClockIn) ? 'Late' : 'On-Time';

    $attendance->update([
        'clock_in' => $clockInTime,
        'status' => $status,
    ]);

    // Send email to admin
    Mail::to(config('mail.admin_email'))->send(new ClockInMail($user, $attendance, $status));

    return back()->with('success', 'Clocked in successfully!');
}






public function clockOut()
{
    $attendance = Attendance::where('user_id', Auth::id())
        ->whereDate('date', today())
        ->first();

    if (!$attendance || !$attendance->clock_in) {
        return back()->with('error', 'You need to clock in first!');
    }

    if ($attendance->clock_out) {
        return back()->with('error', 'You have already clocked out today.');
    }

    $attendance->update(['clock_out' => now()]);

    // Optional: send email
   Mail::to(config('mail.admin_email'))->send(new ClockOutMail(Auth::user(), $attendance));



    return back()->with('success', 'Clocked out successfully!');
}


public function export(Request $request)
{
    // Apply same filters as index
    $startDate = $request->input('start_date');
    $endDate = $request->input('end_date');
    $unitId = $request->input('unit_id');
    $departmentId = $request->input('department_id');

    $query = Attendance::with(['user.unit','user.department'])
                ->whereHas('user', fn($q) => $q->where('role', 'staff'));

    if ($startDate && $endDate) {
        $query->whereBetween('date', [$startDate, $endDate]);
    }

    if ($unitId) {
        $query->whereHas('user', fn($q) => $q->where('unit_id', $unitId));
    }

    if ($departmentId) {
        $query->whereHas('user', fn($q) => $q->where('department_id', $departmentId));
    }

    $attendances = $query->orderBy('date', 'desc')->get();

    // Download filtered attendance as Excel
    return Excel::download(new AttendanceExport($attendances), 'attendance.xlsx');
}

public function exportToEmail(Request $request)
{
    // 1️⃣ Apply filters
    $startDate = $request->input('start_date');
    $endDate = $request->input('end_date');
    $unitId = $request->input('unit_id');
    $departmentId = $request->input('department_id');

    $query = Attendance::with(['user.unit', 'user.department'])
        ->whereHas('user', fn($q) => $q->where('role', 'staff'));

    if ($startDate && $endDate) {
        $query->whereBetween('date', [$startDate, $endDate]);
    }

    if ($unitId) {
        $query->whereHas('user', fn($q) => $q->where('unit_id', $unitId));
    }

    if ($departmentId) {
        $query->whereHas('user', fn($q) => $q->where('department_id', $departmentId));
    }

    $attendances = $query->orderBy('date', 'desc')->get();

    // 2️⃣ Store Excel in storage/app
    $fileName = 'attendance_' . now()->format('Ymd_His') . '.xlsx';
    Excel::store(new AttendanceExport($attendances), $fileName, 'local');

    // 3️⃣ Get absolute path
    $filePath = Storage::path($fileName); // ✅ ensures Mail can find it

    // 4️⃣ Send email
    Mail::to(auth()->user()->email)->send(new AttendanceExportMail($filePath));

    // 5️⃣ Optionally delete after sending
    Storage::delete($fileName);

    return back()->with('success', 'Attendance report has been sent to your email!');
}

}
