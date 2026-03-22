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
use App\Exports\StyledAttendanceExport;





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
public function clockIn(Request $request)
{
    $user = auth()->user();
    $today = now()->toDateString();

    $attendance = Attendance::firstOrCreate(
        ['user_id' => $user->id, 'date' => $today]
    );

    $attendance->clock_in = now();

    // Get start time from settings
    $startTime = ClockInSetting::first()->start_time ?? '08:00';

    if (now()->format('H:i') > $startTime) {
        $attendance->status = 'late';
    } else {
        $attendance->status = 'on_time';
    }

    $attendance->save();

    if ($attendance->status === 'late') {
        // Return response to show late reason modal
        return response()->json([
            'status' => 'late',
            'message' => 'You are late! Please provide a reason.'
        ]);
    }
    // Send email to admin
    Mail::to(config('mail.admin_email'))->send(new ClockInMail($user, $attendance, $attendance->status)); 

    return back()->with('success', 'Clocked in successfully.');
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
    $query = Attendance::with(['user.unit','user.department'])
                ->whereHas('user', fn($q) => $q->where('role', 'staff'));

    if ($request->start_date && $request->end_date) {
        $query->whereBetween('date', [$request->start_date, $request->end_date]);
    }

    if ($request->unit_id) {
        $query->whereHas('user', fn($q) => $q->where('unit_id', $request->unit_id));
    }

    if ($request->department_id) {
        $query->whereHas('user', fn($q) => $q->where('department_id', $request->department_id));
    }

    $attendances = $query->orderBy('date')->get();

    // Pass filters for top row display
    $filters = [
        'start_date' => $request->start_date,
        'end_date' => $request->end_date,
        'unit' => $request->unit_id ? \App\Models\Unit::find($request->unit_id)->name : '',
        'department' => $request->department_id ? \App\Models\Department::find($request->department_id)->name : '',
    ];

    return \Maatwebsite\Excel\Facades\Excel::download(new StyledAttendanceExport($attendances, $filters), 'attendance_report.xlsx');
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

public function submitLateReason(Request $request)
{
    $request->validate([
        'reason' => 'required|string|max:600',
    ]);

    $user = auth()->user();

    // Store late reason in database, e.g., in attendance table
    $attendance = $user->attendances()->latest()->first();
    if ($attendance) {
        $attendance->late_reason = $request->reason;
        $attendance->save();
    }

    return response()->json([
        'status' => 'success',
        'message' => 'Late reason submitted successfully!',
    ]);
}

public function saveLateReason(Request $request)
{
    $request->validate([
        'reason' => 'required|string|max:600',
    ]);

    $attendance = Attendance::where('user_id', auth()->id())
                            ->whereDate('date', now())
                            ->first();

    if($attendance && $attendance->status === 'late'){
        $attendance->late_reason = $request->reason;
        $attendance->save();
    }

    return back()->with('success', 'Late reason submitted successfully.');
}

}
