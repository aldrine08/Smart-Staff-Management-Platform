<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Payroll;
use App\Models\Bonus;
use App\Models\Deduction;   

class StaffPayrollController extends Controller
{
    public function index()
    {
        $payrolls = Payroll::where('user_id', Auth::id())
            ->orderByDesc('year')
            ->orderByDesc('month')
            ->get();

        $latest = $payrolls->first();

         $payrolls = Payroll::where('user_id', Auth::id())
        ->orderBy('year')
        ->orderBy('month')
        ->get();

    $latest = $payrolls->last();

    // chart data
    $chartLabels = $payrolls->map(fn($p) => $p->month . '/' . $p->year);
    $chartData = $payrolls->map(fn($p) => $p->net_salary);

    return view('staff.payroll.index', compact(
        'payrolls',
        'latest',
        'chartLabels',
        'chartData'
    ));

        return view('staff.payroll.index', compact('payrolls','latest'));
    }

   public function show($id)
{
    $payroll = Payroll::where('id', $id)
        ->where('user_id', Auth::id())
        ->firstOrFail();

    $user = $payroll->user;

    // Get bonuses and deductions for this payroll month
    $bonuses = Bonus::where('unit_id', $user->unit_id)
        ->where('department_id', $user->department_id)
        ->whereYear('created_at', $payroll->year)
        ->whereMonth('created_at', $payroll->month)
        ->get();

    $deductions = Deduction::where('unit_id', $user->unit_id)
        ->where('department_id', $user->department_id)
        ->whereYear('created_at', $payroll->year)
        ->whereMonth('created_at', $payroll->month)
        ->get();

    return view('staff.payroll.show', compact('payroll', 'bonuses', 'deductions'));
}
}
