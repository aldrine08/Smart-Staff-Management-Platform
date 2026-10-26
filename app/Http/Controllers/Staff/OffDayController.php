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

    $start = CarbonImmutable::parse(
        $validated['start_date']
    )->startOfDay();

    $end = CarbonImmutable::parse(
        $validated['end_date']
    )->startOfDay();

    // End date represents the reporting date.
    // It is excluded from off-day usage.
    $days = (int) $start->diffInDays($end);

    if ($days > config('offdays.maximum_request_days', 366)) {
        throw ValidationException::withMessages([
            'end_date' => 'The requested duration is too long.',
        ]);
    }

    $user = $request->user();

    if ($user->role !== 'staff') {
        abort(403);
    }

    // Preserve the existing department-policy requirement.
    $department = $user->department;

    if (
        !$department ||
        !$user->unit ||
        (int) $department->admin_id !== (int) $user->unit->admin_id ||
        !$department->offDaySetting?->is_enabled
    ) {
        throw ValidationException::withMessages([
            'start_date' =>
                'Off-day requests are not enabled for your department.',
        ]);
    }

    if (
        !$user->start_date ||
        $start->lessThan(
            CarbonImmutable::parse($user->start_date)->startOfDay()
        )
    ) {
        throw ValidationException::withMessages([
            'start_date' =>
                'Off-day dates cannot precede your employment start date.',
        ]);
    }

    // Lock the employee so repeated or simultaneous submissions
    // use the same overlap check before inserting a request.
    DB::transaction(function () use ($user, $validated, $start, $end) {

        User::query()
            ->whereKey($user->id)
            ->lockForUpdate()
            ->firstOrFail();

        $overlap = OffDayRequest::query()
            ->where('user_id', $user->id)
            ->whereIn('status', ['pending', 'approved'])
            ->whereDate('start_date', '<', $end->toDateString())
            ->whereDate('end_date', '>', $start->toDateString())
            ->exists();

        if ($overlap) {
            throw ValidationException::withMessages([
                'start_date' =>
                    'You already have a pending or approved request overlapping these dates.',
            ]);
        }

        OffDayRequest::create([
            'user_id' => $user->id,
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'],
            'reason' => $validated['reason'],
            'status' => 'pending',
        ]);

    }, 3);

    return redirect()
        ->route('offdays.index')
        ->with(
            'success',
            'Your off-day request has been submitted successfully.'
        );
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


    return redirect()->route('offdays.index')
        ->with('success', 'Off day request updated successfully.');
}


}
