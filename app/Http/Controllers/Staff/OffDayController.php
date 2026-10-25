<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\OffDayRequest;
use Illuminate\Support\Facades\Mail;
use App\Mail\OffDayRequestMail;
use Illuminate\Support\Facades\Auth;
use App\Models\OffDay;
use Carbon\CarbonImmutable;
use Illuminate\Validation\ValidationException;
use App\Models\User;
use Illuminate\Support\Facades\DB;


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
        
$validated = $request->validate([
    'start_date' => [
        'required',
        'date_format:Y-m-d',
    ],
    'end_date' => [
        'required',
        'date_format:Y-m-d',
        'after:start_date',
    ],
    'reason' => [
        'required',
        'string',
        'max:600',
    ],
]);

$start = CarbonImmutable::parse($validated['start_date']);
$end = CarbonImmutable::parse($validated['end_date']);

$days = (int) $start->diffInDays($end);

if ($days > config('offdays.maximum_request_days', 366)) {
    throw ValidationException::withMessages([
        'end_date' => 'The requested duration is too long.',
    ]);
}

$user = $request->user();

if (!$user->department_id ||
    !$user->department?->offDaySetting?->is_enabled) {
    throw ValidationException::withMessages([
        'start_date' => 'Off-day accrual is not enabled for your department.',
    ]);
}

if (!$user->start_date ||
    $start->lessThan(CarbonImmutable::parse($user->start_date)->startOfDay())) {
    throw ValidationException::withMessages([
        'start_date' => 'Off-day dates cannot precede your employment start date.',
    ]);
}

$hasOverlap = OffDayRequest::query()
    ->where('user_id', $user->id)
    ->whereIn('status', ['pending', 'approved'])
    ->whereDate('start_date', '<', $end->toDateString())
    ->whereDate('end_date', '>', $start->toDateString())
    ->exists();

if ($hasOverlap) {
    throw ValidationException::withMessages([
        'start_date' => 'You already have a pending or approved request overlapping these dates.',
    ]);
}

$offday = OffDayRequest::create([
    'user_id' => $user->id,
    'start_date' => $validated['start_date'],
    'end_date' => $validated['end_date'],
    'reason' => $validated['reason'],
    'status' => 'pending',
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

    public function edit($id)
{
    $offDay = \App\Models\OffDayRequest::findOrFail($id);

    if ($offDay->user_id !== auth()->id()) {
        abort(403);
    }

    if ($offDay->status !== 'pending') {
        return redirect()->route('offdays.index')
            ->with('error', 'Only pending requests can be edited.');
    }

    return view('staff.offdays.edit', compact('offDay'));
}

public function update(Request $request, $id)
{
    $offDay = \App\Models\OffDayRequest::findOrFail($id);

    if ($offDay->user_id !== auth()->id()) {
        abort(403);
    }

    if ($offDay->status !== 'pending') {
        return redirect()->route('offdays.index')
            ->with('error', 'Cannot update processed request.');
    }

    
$validated = $request->validate([
    'start_date' => [
        'required',
        'date_format:Y-m-d',
    ],
    'end_date' => [
        'required',
        'date_format:Y-m-d',
        'after:start_date',
    ],
    'reason' => [
        'required',
        'string',
        'max:600',
    ],
]);

$start = CarbonImmutable::parse($validated['start_date']);
$end = CarbonImmutable::parse($validated['end_date']);

$days = (int) $start->diffInDays($end);

if ($days > config('offdays.maximum_request_days', 366)) {
    throw ValidationException::withMessages([
        'end_date' => 'The requested duration is too long.',
    ]);
}

$user = $request->user();

if (!$user->start_date ||
    $start->lessThan(CarbonImmutable::parse($user->start_date)->startOfDay())) {
    throw ValidationException::withMessages([
        'start_date' => 'Off-day dates cannot precede your employment start date.',
    ]);
}

$hasOverlap = OffDayRequest::query()
    ->where('user_id', $user->id)
    ->where('id', '!=', $offDay->id)
    ->whereIn('status', ['pending', 'approved'])
    ->whereDate('start_date', '<', $end->toDateString())
    ->whereDate('end_date', '>', $start->toDateString())
    ->exists();

if ($hasOverlap) {
    throw ValidationException::withMessages([
        'start_date' => 'Another pending or approved request overlaps these dates.',
    ]);
}

$offDay->update($validated);


    $offDay->update([
        'start_date' => $request->start_date,
        'end_date' => $request->end_date,
        'reason' => $request->reason,
    ]);

    return redirect()->route('offdays.index')
        ->with('success', 'Off day request updated successfully.');
}


}
