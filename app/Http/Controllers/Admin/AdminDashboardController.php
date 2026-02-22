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



class AdminDashboardController extends Controller
{
    
    public function index()
    {
        $units = Unit::with('chatRoom')->get();

        

          $departments = Department::all();
        
          $today = Carbon::today()->toDateString();

          $totalStaff = User::where('role', 'staff')->where('is_active', 1)->count();

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
            ->whereNotIn('id', $clockedInUserIds)
            ->get();
        
        $units = Unit::withCount('staff')->get(); // staff_count will be available

        $staffCount = User::where('role', 'staff')
        ->where('is_active', 1)
        ->count();

         $pendingRequestsCount = OffDayRequest::where('status', 'pending')->count();
        
        

        return view('admin.dashboard', compact('units','totalStaff','clockedInUsers', 'clockedOutUsers', 'notClockedInUsers', 'staffCount', 'pendingRequestsCount'));
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
                 ->where('is_active', 1)
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
