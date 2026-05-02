<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\LoanRequest;
use Illuminate\Support\Facades\Auth;
use App\Models\Loan;

class LoanController extends Controller
{
    // View all my loan requests
    public function index()
    {
        $requests = LoanRequest::where('user_id', Auth::id())
            ->latest()
            ->get();

        return view('staff.loans.index', compact('requests'));
    }

    // Store loan request
    public function store(Request $request)
    {
        $request->validate([
            'amount_requested' => 'required|numeric|min:1',
            'reason' => 'required|string',
            'repayment_months' => 'required|integer|min:1',
        ]);

        LoanRequest::create([
    'user_id' => Auth::id(),
    'amount_requested' => $request->amount_requested,
    'reason' => $request->reason,
    'repayment_months' => $request->repayment_months ?? 12,
    'status' => 'pending',
]);

        return back()->with('success', 'Loan request submitted successfully.');
    }

    public function active()
{
    $loans = Loan::where('status', 'active')->get();

    return view('staff.loans.active', compact('loans'));
}


public function pending()
{
    $loans = Loan::where('status', 'pending')->get();

    return view('staff.loans.pending', compact('loans'));
}


public function total()
{
    $loans = Loan::all(); // all statuses

    $active = Loan::where('status', 'active')->count();
    $pending = Loan::where('status', 'pending')->count();
    $declined = Loan::where('status', 'declined')->count();

    return view('staff.loans.total', compact(
        'loans',
        'active',
        'pending',
        'declined'
    ));
}

}