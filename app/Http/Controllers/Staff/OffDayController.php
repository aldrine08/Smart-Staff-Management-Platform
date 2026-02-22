<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\OffDayRequest;
use Illuminate\Support\Facades\Mail;
use App\Mail\OffDayRequestMail;
use Illuminate\Support\Facades\Auth;
use App\Models\OffDay;


class OffDayController extends Controller
{
    public function index(Request $request)
{
    $userId = Auth::id();

    $query = OffDayRequest::where('user_id', $userId);

    if ($request->has('filter')) {
        if ($request->filter === 'pending') {
            $query->where('status', 'pending');
        } elseif ($request->filter === 'approved') {
            $query->where('status', 'approved');
        }
    }

    $offDays = $query->orderBy('created_at', 'desc')->get();

    return view('staff.offdays.index', compact('offDays'));
}

    public function store(Request $request)
    {
        $request->validate([
            'start_date' => 'required|date',
            'end_date'   => 'required|date|after_or_equal:start_date',
            'reason'     => 'required|string|max:100',
        ]);

        $offday = OffDayRequest::create([
            'user_id'    => Auth()->id(),
            'start_date' => $request->start_date,
            'end_date'   => $request->end_date,
            'reason'     => $request->reason,
            'status'     => 'pending',
        ]);

        // Send email to admin
        //  Mail::to(config('mail.admin_email'))->send(new OffDayRequestMail($offday));

        return back()->with('success', 'Your off-day request has been submitted.');
    }
}
