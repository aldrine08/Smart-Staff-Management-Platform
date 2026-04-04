<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Attendance;
use Carbon\Carbon;
use App\Models\Unit;
use App\Models\Department;
use App\Models\Staff;
use Illuminate\Support\Facades\Auth;
use App\Models\OffDayRequest;
use Illuminate\Support\Facades\Mail;
use App\Mail\OffDayStatusMail;
use App\Models\SickRequest;



class AdminDashboardController extends Controller
{
    
    public function index()
    {
        $units = Unit::with('chatRoom')->get();

        $requests = SickRequest::latest()->get();

        

          $departments = Department::all();
        
          $today = Carbon::today()->toDateString();

          $totalStaff = User::where('role', 'staff')->where('is_active', 1)->whereNull('deleted_at')->count();
         
          $clockedInUsers = Attendance::with('user')
                        ->whereDate('date', $today)
                        ->whereNotNull('clock_in')
                        ->whereHas('user')
                        ->get()
                        ->pluck('user');

          $clockedOutUsers = Attendance::with('user')
                        ->whereDate('date', $today)
                        ->whereNotNull('clock_out')
                        ->whereHas('user')  
                        ->get()
                        ->pluck('user');

            //  $clockedIn = $clockedInUsers->count();
            //  $clockedOut = $clockedOutUsers->count();  
            
             // Staff who haven't clocked in today
        $clockedInUserIds = $clockedInUsers->pluck('user_id')->toArray();

        $notClockedInUsers = User::where('role', 'staff')
            ->where('is_active', 1)
            ->whereNull('deleted_at')
            ->whereNotIn('id', $clockedInUserIds)
            ->get();
        
        $units = Unit::withCount('staff')->get(); // staff_count will be available

        $staffCount = User::where('role', 'staff')
        ->where('is_active', 1)
        ->whereNull('deleted_at')
        ->count();

         $pendingRequestsCount = OffDayRequest::where('status', 'pending')->count();

         // =====================
// DASHBOARD ANALYTICS (NEW)
// =====================

// Get all active staff (important: matches your system filters)
$staff = User::where('role', 'staff')
    ->where('is_active', 1)
    ->whereNull('deleted_at')
    ->get();

/* ACTIVE / INACTIVE */
$activeEmployees = $staff->count();
$inactiveEmployees = User::where('role', 'staff')
    ->where('is_active', 0)
    ->count();

/* GENDER */
$maleCount = $staff->where('gender', 'male')->count();
$femaleCount = $staff->where('gender', 'female')->count();

/* AGE GROUPS (using dob column from your User model) */
$ageGroups = [
    '20-29' => 0,
    '30-39' => 0,
    '40-49' => 0,
    '50+' => 0,
];

foreach ($staff as $user) {
    if ($user->dob) {
        $age = Carbon::parse($user->dob)->age;

        if ($age >= 20 && $age <= 29) $ageGroups['20-29']++;
        elseif ($age <= 39) $ageGroups['30-39']++;
        elseif ($age <= 49) $ageGroups['40-49']++;
        else $ageGroups['50+']++;
    }
}

/* YEARS OF SERVICE (using start_date if exists) */
$serviceGroups = [
    '0-4' => 0,
    '5-9' => 0,
    '10-14' => 0,
    '15+' => 0,
];

foreach ($staff as $user) {
    if ($user->start_date) {
        $years = Carbon::parse($user->start_date)->diffInYears(now());

        if ($years <= 4) $serviceGroups['0-4']++;
        elseif ($years <= 9) $serviceGroups['5-9']++;
        elseif ($years <= 14) $serviceGroups['10-14']++;
        else $serviceGroups['15+']++;
    }
}

// =====================
// TODAY ATTENDANCE SUMMARY (NEW)
// =====================

$presentCount = $clockedInUsers->count();
$absentCount = $notClockedInUsers->count();

// Late (if you have late logic later, this is ready)
$lateCount = Attendance::whereDate('date', $today)
    ->whereNotNull('clock_in')
    ->whereTime('clock_in', '>', '08:10:00') // matches your setting
    ->count();

// Missed clock-out
$missedClockOut = Attendance::whereDate('date', $today)
    ->whereNotNull('clock_in')
    ->whereNull('clock_out')
    ->count();
        
        

        return view('admin.dashboard', compact(
    'units',
    'totalStaff',
    'clockedInUsers',
    'clockedOutUsers',
    'notClockedInUsers',
    'staffCount',
    'pendingRequestsCount',
    'requests',

    // NEW
    'activeEmployees',
    'inactiveEmployees',
    'maleCount',
    'femaleCount',
    'ageGroups',
    'serviceGroups',
    'presentCount',
    'absentCount',
    'lateCount',
    'missedClockOut'
));
    }

    public function manualClockIn(User $user)
{
    $today = Carbon::today()->toDateString();

    $attendance = Attendance::firstOrCreate(
        ['user_id' => $user->id, 'date' => $today]
    );

    $attendance->clock_in = now();
    $attendance->save();

    return back()->with('success', $user->name.' has been clocked in manually.');
}

public function manualClockOut(User $user)
{
    $today = Carbon::today()->toDateString();

    $attendance = Attendance::where('user_id', $user->id)
                            ->whereDate('date', $today)
                            ->first();

    if ($attendance && !$attendance->clock_out) {
        $attendance->clock_out = now();
        $attendance->save();

        return back()->with('success', $user->name.' has been clocked out manually.');
    }

    return back()->with('error', 'Cannot clock out '.$user->name.' (already clocked out or no clock-in record).');
}

public function unitStaff(Unit $unit)
{

$staff = $unit->staff()->where('role', 'staff')->get();

    $staff = User::where('unit_id', $unit->id)
             ->where('role', 'staff')
             ->whereNull('deleted_at') // only exclude deleted users
             ->get();

    return view('admin.units.show', compact('unit', 'staff'));

    
}

public function indexOffDays()
{
    $offDayRequests = OffDayRequest::with('user')->latest()->get();
    $pendingRequestsCount = OffDayRequest::where('status','pending')->count();

    return view('admin.offdays.index', compact('offDayRequests', 'pendingRequestsCount'));
}

public function approveOffDay(OffDayRequest $offDay)
{
    $offDay->update(['status' => 'approved']);

    // Send email to staff
    Mail::to($offDay->user->email)->send(new OffDayStatusMail($offDay));

    return back()->with('success', 'Request approved successfully.');
}

public function declineOffDay(Request $request, OffDayRequest $offDay)
{
    $offDay->update([
        'status' => 'declined',
        'admin_comment' => $request->admin_comment
    ]);

    // Send email to staff
    Mail::to($offDay->user->email)->send(new OffDayStatusMail($offDay));

    return back()->with('success', 'Request declined.');
}



}
