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
    'amount_requested' => 'required|numeric|min:1000',
    'repayment_months' => 'required|integer|min:1|max:60',
    'reason' => 'required|string|max:1000',

    'recovery_start_date' => 'nullable|date',
    'special_notes' => 'nullable|string',

    'guarantor1_name' => 'nullable|string|max:255',
    'guarantor1_phone' => 'nullable|string|max:255',
    'guarantor1_id' => 'nullable|string|max:255',

    'guarantor2_name' => 'nullable|string|max:255',
    'guarantor2_phone' => 'nullable|string|max:255',
    'guarantor2_id' => 'nullable|string|max:255',

    'guarantor3_name' => 'nullable|string|max:255',
    'guarantor3_phone' => 'nullable|string|max:255',
    'guarantor3_id' => 'nullable|string|max:255',
]);

        LoanRequest::create([
    'user_id' => Auth::id(),

    'amount_requested' => $request->amount_requested,
    'reason' => $request->reason,
    'repayment_months' => $request->repayment_months,

    'recovery_start_date' => $request->recovery_start_date,
    'special_notes' => $request->special_notes,

    'guarantor1_name' => $request->guarantor1_name,
    'guarantor1_phone' => $request->guarantor1_phone,
    'guarantor1_id' => $request->guarantor1_id,
    'guarantor1_address' => $request->guarantor1_address,

    'guarantor2_name' => $request->guarantor2_name,
    'guarantor2_phone' => $request->guarantor2_phone,
    'guarantor2_id' => $request->guarantor2_id,
    'guarantor2_address' => $request->guarantor2_address,
    
    'guarantor3_name' => $request->guarantor3_name,
    'guarantor3_phone' => $request->guarantor3_phone,
    'guarantor3_id' => $request->guarantor3_id,
    'guarantor3_address' => $request->guarantor3_address,

    

    'status' => 'pending',
]);

        return back()->with('success', 'Loan request submitted successfully.');
    }

    public function active()
{
    $loans = Loan::where('user_id', Auth::id())
        ->where('status', 'active')
        ->latest()
        ->get();

    return view('staff.loans.active', compact('loans'));
}

public function pending()
{
    $requests = LoanRequest::where('user_id', Auth::id())
        ->where('status', 'pending')
        ->latest()
        ->get();

    return view('staff.loans.pending', compact('requests'));
}


public function total()
{
    $loans = Loan::where('user_id', Auth::id())
        ->latest()
        ->get();

    $requests = LoanRequest::where('user_id', Auth::id())
        ->latest()
        ->get();

    $active = Loan::where('user_id', Auth::id())
        ->where('status', 'active')
        ->count();

    $pending = LoanRequest::where('user_id', Auth::id())
        ->where('status', 'pending')
        ->count();

    $declined = LoanRequest::where('user_id', Auth::id())
        ->where('status', 'rejected')
        ->count();

    return view('staff.loans.total', compact(
        'loans',
        'requests',
        'active',
        'pending',
        'declined'
    ));
}

}