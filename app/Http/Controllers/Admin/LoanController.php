<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\LoanRequest;
use App\Models\Loan;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;


class LoanController extends Controller
{
    // View all loan requests
    public function index(Request $request)
{
    $query = LoanRequest::with([
        'user.unit',
        'user.department'
    ]);

    if ($request->filled('name')) {
        $query->whereHas('user', function ($q) use ($request) {
            $q->where('name', 'like', '%' . $request->name . '%');
        });
    }

    if ($request->filled('email')) {
        $query->whereHas('user', function ($q) use ($request) {
            $q->where('email', 'like', '%' . $request->email . '%');
        });
    }

    if ($request->filled('unit')) {
        $query->whereHas('user.unit', function ($q) use ($request) {
            $q->where('name', 'like', '%' . $request->unit . '%');
        });
    }

    if ($request->filled('department')) {
        $query->whereHas('user.department', function ($q) use ($request) {
            $q->where('name', 'like', '%' . $request->department . '%');
        });
    }

    $requests = $query
        ->latest()
        ->get();

    return view('admin.loans.index', compact('requests'));
}

    // Approve loan
    // Approve loan
public function approve(Request $request, $id)
{
    $request->validate([
        'approved_amount' => 'required|numeric|min:1',
        'repayment_months' => 'required|integer|min:1',
    ]);

    $loanRequest = LoanRequest::findOrFail($id);

    // Prevent multiple active loans
    $hasActiveLoan = Loan::where('user_id', $loanRequest->user_id)
        ->where('status', 'active')
        ->exists();

    if ($hasActiveLoan) {
        return redirect()
            ->route('admin.loans.index')
            ->with('error', 'User already has an active loan.');
    }

    $monthly = $request->approved_amount / $request->repayment_months;

    // Create active loan
    Loan::create([
        'user_id' => $loanRequest->user_id,
        'approved_amount' => $request->approved_amount,
        'repayment_months' => $request->repayment_months,
        'monthly_installment' => $monthly,
        'remaining_balance' => $request->approved_amount,
        'status' => 'active',
        'approved_by' => Auth::id(),
        'start_date' => now(),
    ]);

    // Mark request as approved
    $loanRequest->update([
        'status' => 'approved',
        'reviewed_by' => Auth::id(),
    ]);

    return redirect()
        ->route('admin.loans.index')
        ->with('success', 'Loan approved successfully.');
}

    // Reject loan
    public function reject(Request $request, $id)
    {
        $request->validate([
            'admin_reason' => 'required|string',
        ]);

        $loanRequest = LoanRequest::findOrFail($id);

        $loanRequest->update([
            'status' => 'rejected',
            'admin_reason' => $request->admin_reason,
            'reviewed_by' => Auth::id(),
        ]);

        return back()->with('success', 'Loan request rejected.');
    }

    public function repay(Request $request, $id)
{
    $loan = \App\Models\Loan::findOrFail($id);

    $request->validate([
        'amount_paid' => 'required|numeric|min:1',
    ]);

    $loan->remaining_balance -= $request->amount_paid;

    if ($loan->remaining_balance <= 0) {
        $loan->remaining_balance = 0;
        $loan->status = 'completed';
    }

    $loan->save();

    \App\Models\LoanRepayment::create([
        'loan_id' => $loan->id,
        'amount_paid' => $request->amount_paid,
        'payment_date' => now(),
        'remaining_balance' => $loan->remaining_balance,
    ]);

    return back()->with('success', 'Repayment recorded successfully.');
}

public function show($id)
{
    $loanRequest = LoanRequest::with([
        'user.unit',
        'user.department'
    ])->findOrFail($id);

    $activeLoan = Loan::where('user_id', $loanRequest->user_id)
    ->where('status', 'active')
    ->latest()
    ->first();  

    return view('admin.loans.show', compact('loanRequest', 'activeLoan' ));
}

public function complete($id)
{
    $loan = Loan::findOrFail($id);

    if ($loan->status !== 'active') {
        return back()->with('error', 'Only active loans can be completed.');
    }

    $loan->update([
        'status' => 'completed',
        'remaining_balance' => 0,
    ]);

    return back()->with('success', 'Loan marked as completed successfully.');
}

}