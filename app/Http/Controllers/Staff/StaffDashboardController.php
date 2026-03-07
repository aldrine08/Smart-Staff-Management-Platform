<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\OffDayRequest;
use Illuminate\Support\Facades\Mail;
use App\Mail\OffDayRequestMail;
use Illuminate\Support\Facades\Auth;
use App\Models\Attendance;
use Carbon\Carbon;
use App\Models\Item;
use App\Models\Staff;
use Illuminate\Support\Facades\Storage;


class StaffDashboardController extends Controller
{

public function store(Request $request)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email',
        'document' => 'nullable|mimes:pdf|max:2048'
    ]);

    $documentPath = null;

    if ($request->hasFile('document')) {
        $documentPath = $request->file('document')->store('staff_documents', 'public');
    }

    Staff::create([
        'name' => $request->name,
        'email' => $request->email,
        'document' => $documentPath
    ]);

    return redirect()->back()->with('success', 'Staff added successfully');
}


    public function storeOffDayRequest(Request $request)
{
    $request->validate([
        'start_date' => 'required|date|after_or_equal:today',
        'end_date' => 'required|date|after_or_equal:start_date',
        'reason' => 'required|max:600', // approx 100 words
    ]);

    $offDay = OffDayRequest::create([
        'user_id' => auth()->id(),
        'start_date' => $request->start_date,
        'end_date' => $request->end_date,
        'reason' => $request->reason,
    ]);

    // Email to admin
    Mail::to('aldrine@gmail.com')->send(new OffDayRequestMail($offDay));

    // // Optional: store notification for admin (for sidebar)
    // \App\Models\User::where('role','admin')->first()->notify(new \App\Notifications\NewOffDayRequest($offDay));

    return back()->with('success', 'Your off-day request has been submitted!');
}


public function dashboard()
{
    $user = Auth::user();
    $userId = $user->id;

    $attendance = Attendance::where('user_id', $userId)
                        ->whereDate('date', Carbon::today())
                        ->first();

    $clockedIn = $attendance?->clock_in;
    $clockedOut = $attendance?->clock_out;

    $pendingRequests = OffDayRequest::where('user_id', $userId)
                        ->where('status', 'pending')
                        ->count();

    $approvedOffDays = OffDayRequest::where('user_id', $userId)
                        ->where('status', 'approved')
                        ->count();

    $totalOffDays = OffDayRequest::where('user_id', $userId)->count();

    // Chart data for last 7 days
    $chartLabels = [];
    $chartData = [];
    for ($i = 6; $i >= 0; $i--) {
        $date = Carbon::today()->subDays($i);
        $chartLabels[] = $date->format('D'); // e.g., Mon, Tue
        $chartData[] = Attendance::where('user_id', $userId)
                                 ->whereDate('date', $date)
                                 ->count();
    }

    // ✅ Fetch assigned items for this staff
    $assignedItems = $user->items()->orderByPivot('assigned_at', 'desc')->get();

    return view('staff.dashboard', compact(
        'clockedIn',
        'clockedOut',
        'pendingRequests',
        'approvedOffDays',
        'totalOffDays',
        'chartLabels',
        'chartData',
        'assignedItems' // pass to Blade
    ));
}

}
