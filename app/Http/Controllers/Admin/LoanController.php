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
    public function index()
    {
        $requests = LoanRequest::with('user')
            ->latest()
            ->get();

        return view('admin.loans.index', compact('requests'));
    }

    // Approve loan
    public function approve(Request $request, $id)
    {
        $loanRequest = LoanRequest::findOrFail($id);

        $request->validate([
            'approved_amount' => 'required|numeric|min:1',
            'repayment_months' => 'required|integer|min:1',
        ]);

        // prevent multiple active loans
        $hasActiveLoan = Loan::where('user_id', $loanRequest->user_id)
            ->where('status', 'active')
            ->exists();

        if ($hasActiveLoan) {
            return back()->with('error', 'User already has an active loan.');
        }

        $monthly = $request->approved_amount / $request->repayment_months;

        // create loan
        Loan::create([
            'user_id' => $loanRequest->user_id,
            'approved_amount' => $request->approved_amount,
            'repayment_months' => $request->repayment_months,
            'monthly_installment' => $monthly,
            'remaining_balance' => $request->approved_amount,
            'status' => 'active',
            'approved_by' => Auth::id(),
            'start_date' => Carbon::now(),
        ]);

        // update request
        $loanRequest->update([
            'status' => 'approved',
            'reviewed_by' => Auth::id(),
        ]);

        return back()->with('success', 'Loan approved successfully.');
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

}