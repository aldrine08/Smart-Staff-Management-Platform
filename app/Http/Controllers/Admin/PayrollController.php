<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Attendance;
use App\Models\SalarySetting;
use App\Models\Deduction;
use App\Models\Bonus;
use App\Models\Payroll;
use App\Models\Unit;
use App\Models\ProcessedPayroll;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Mail;
use App\Mail\ProcessedPayrollReport;
use App\Models\PayrollAudit;


class PayrollController extends Controller
{
    // Show payroll summary
    public function index()
    {
        // Get all staff
        $staffs = User::where('role', 'staff')->get();

        $payrollData = [];

        foreach ($staffs as $staff) {
            // Get unit & department
            $unitId = $staff->unit_id;
            $deptId = $staff->department_id;

            // Count days worked (clocked in)
            $daysWorked = Attendance::where('user_id', $staff->id)
                                    ->whereNotNull('clock_in')
                                    ->count();

            // Get salary setting for their unit & department
            $salarySetting = SalarySetting::where('unit_id', $unitId)
                                         ->where('department_id', $deptId)
                                         ->first();

            $dailySalary = $salarySetting ? $salarySetting->daily_salary : 0;

            $totalSalary = $dailySalary * $daysWorked;

           if ($daysWorked > 0) {
    $totalDeductions = Deduction::where('unit_id', $unitId)
        ->where('department_id', $deptId)
        ->sum('amount');

    $totalBonuses = Bonus::where('unit_id', $unitId)
        ->where('department_id', $deptId)
        ->sum('amount');
} else {
    $totalDeductions = 0;
    $totalBonuses = 0;
}

$netPay = $totalSalary + $totalBonuses - $totalDeductions;


            $payrollData[] = [
                'staff' => $staff,
                'daysWorked' => $daysWorked,
                'dailySalary' => $dailySalary,
                'totalSalary' => $totalSalary,
                'totalDeductions' => $totalDeductions,
                'totalBonuses' => $totalBonuses,
                'netPay' => $netPay,
            ];
        }

        return view('admin.payroll.index', compact('payrollData'));
    }

    public function generate(Request $request)
{
    $year = $request->year;
    $month = $request->month;

    $users = User::where('role','staff')->get();

    foreach ($users as $user) {

        // ✅ Count days present (clocked in)
        $daysPresent = Attendance::where('user_id', $user->id)
            ->whereYear('date', $year)
            ->whereMonth('date', $month)
            ->whereNotNull('clock_in')
            ->count();

        // ✅ Get daily salary based on unit + department
        $salarySetting = SalarySetting::where('unit_id', $user->unit_id)
            ->where('department_id', $user->department_id)
            ->first();

        $dailyRate = $salarySetting?->daily_salary ?? 0;

        $baseSalary = $daysPresent * $dailyRate;

        // ✅ Bonuses
        $totalBonus = Bonus::where('user_id', $user->id)
            ->whereYear('date', $year)
            ->whereMonth('date', $month)
            ->sum('amount');

        // ✅ Deductions
        $totalDeductions = Deduction::where('user_id', $user->id)
            ->whereYear('date', $year)
            ->whereMonth('date', $month)
            ->sum('amount');

        $netSalary = $baseSalary + $totalBonus - $totalDeductions;

        // ✅ Save / Update payroll
        Payroll::updateOrCreate(
            [
                'user_id' => $user->id,
                'year' => $year,
                'month' => $month,
            ],
            [
                'days_present' => $daysPresent,
                'base_salary' => $baseSalary,
                'total_bonus' => $totalBonus,
                'total_deductions' => $totalDeductions,
                'net_salary' => $netSalary,
            ]
        );
    }

    return back()->with('success','Payroll generated successfully');
}

public function unitPayroll(Request $request, $unitId)
{
    $unit = Unit::findOrFail($unitId);

    // Get date filters
    $startDate = $request->get('start_date');
    $endDate   = $request->get('end_date');

    // Prepare payroll data
    $staffs = User::where('unit_id', $unitId)->get();
    $payrollData = [];

    $totals = [
        'deductions' => 0,
        'bonuses'   => 0,
        'netSalary' => 0
    ];

    foreach ($staffs as $staff) {
        $daysWorked = Attendance::where('user_id', $staff->id)
            ->when($startDate, fn($q) => $q->whereDate('clock_in', '>=', $startDate))
            ->when($endDate, fn($q) => $q->whereDate('clock_in', '<=', $endDate))
            ->count();

        $salarySetting = SalarySetting::where('unit_id', $unitId)
            ->where('department_id', $staff->department_id)
            ->first();

        $dailySalary = $salarySetting->amount ?? 0;
        $totalSalary = $dailySalary * $daysWorked;

       if ($daysWorked > 0) {
    $totalDeductions = Deduction::where('unit_id', $unitId)
        ->where('department_id', $staff->department_id)
        ->sum('amount');

    $totalBonuses = Bonus::where('unit_id', $unitId)
        ->where('department_id', $staff->department_id)
        ->sum('amount');
} else {
    $totalDeductions = 0;
    $totalBonuses = 0;
}

$netPay = $totalSalary + $totalBonuses - $totalDeductions;


        $payrollData[] = [
            'staff'           => $staff,
            'daysWorked'      => $daysWorked,
            'dailySalary'     => $dailySalary,
            'totalSalary'     => $totalSalary,
            'totalDeductions' => $totalDeductions,
            'totalBonuses'    => $totalBonuses,
            'netPay'          => $netPay
        ];
if ($daysWorked > 0) {
    $totals['deductions'] += $totalDeductions;
    $totals['bonuses']   += $totalBonuses;
    $totals['netSalary'] += $netPay;
}

    }

    // Return the same index view but pass the payroll data
    return view('admin.payroll.index', [
        'payrollData' => $payrollData,
        'unitId'      => $unitId,
        'totals'      => $totals
    ]);
}


public function paySalaries(Request $request, $unitId)
{
    $startDate = $request->start_date;
    $endDate = $request->end_date;

    // Prevent duplicate processing
    $alreadyProcessed = ProcessedPayroll::where('unit_id', $unitId)
        ->where('start_date', $startDate)
        ->where('end_date', $endDate)
        ->exists();

    if ($alreadyProcessed) {
        return back()->with('error', 'Payroll already processed for this date range.');
    }

    $staffs = User::where('role', 'staff')->where('unit_id', $unitId)->get();

    foreach ($staffs as $staff) {
        $daysWorked = Attendance::where('user_id', $staff->id)
                        ->whereBetween('date', [$startDate, $endDate])
                        ->whereNotNull('clock_in')
                        ->count();

        $salarySetting = SalarySetting::where('unit_id', $staff->unit_id)
                                      ->where('department_id', $staff->department_id)
                                      ->first();

        $dailySalary = $salarySetting?->daily_salary ?? 0;
        $baseSalary = $dailySalary * $daysWorked;

        if ($daysWorked > 0) {
    $totalDeductions = Deduction::where('unit_id', $staff->unit_id)
        ->where('department_id', $staff->department_id)
        ->sum('amount');

    $totalBonuses = Bonus::where('unit_id', $staff->unit_id)
        ->where('department_id', $staff->department_id)
        ->sum('amount');
} else {
    $totalDeductions = 0;
    $totalBonuses = 0;
}


        $netSalary = $baseSalary + $totalBonuses - $totalDeductions;

        // Save payroll
        Payroll::updateOrCreate(
            [
                'user_id' => $staff->id,
                'year' => now()->year,
                'month' => now()->month,
            ],
            [
                'days_present' => $daysWorked,
                'base_salary' => $baseSalary,
                'total_bonus' => $totalBonuses,
                'total_deductions' => $totalDeductions,
                'net_salary' => $netSalary,
            ]
        );

        // Save to processed payroll table
        ProcessedPayroll::create([
            'user_id' => $staff->id,
            'unit_id' => $staff->unit_id,
            'department_id' => $staff->department_id,
            'start_date' => $startDate,
            'end_date' => $endDate,
            'net_salary' => $netSalary,
        ]);

        PayrollAudit::create([
    'admin_id' => auth()->id(),
    'unit_id' => $unitId,
    'start_date' => $startDate,
    'end_date' => $endDate,
    'total_paid' => $netSalary,
]);

    }

    // 🔒 LOCK bonuses & deductions after payroll processing
Bonus::where('unit_id', $unitId)
    ->update(['locked' => true]);

Deduction::where('unit_id', $unitId)
    ->update(['locked' => true]);


    return back()->with('success', 'Salaries paid and processed payroll recorded successfully!');
}

public function processedPayrolls(Request $request)
{
    $query = ProcessedPayroll::with('user', 'unit', 'department')->latest();

    // Filters
    if ($request->unit_id) {
        $query->where('unit_id', $request->unit_id);
    }

    if ($request->user_id) {
        $query->where('user_id', $request->user_id);
    }

    if ($request->start_date && $request->end_date) {
        $query->whereBetween('start_date', [$request->start_date, $request->end_date]);
    }

    $processedPayrolls = $query->paginate(20);

    $units = Unit::all();
    $staffs = User::where('role','staff')->get();

    return view('admin.payroll.processed', compact('processedPayrolls','units','staffs'));
}

// Download payroll report as PDF
public function downloadPDF(Request $request)
{
    $query = ProcessedPayroll::with('user','unit','department')->latest();

    if ($request->unit_id) $query->where('unit_id', $request->unit_id);
    if ($request->user_id) $query->where('user_id', $request->user_id);
    if ($request->start_date && $request->end_date) $query->whereBetween('start_date', [$request->start_date, $request->end_date]);

    $payrolls = $query->get();

    $pdf = Pdf::loadView('admin.payroll.processed_pdf', ['payrolls' => $payrolls]);
    return $pdf->download('processed_payrolls.pdf');
}

// Send payroll report by email
public function emailReport(Request $request)
{
    $query = ProcessedPayroll::with('user','unit','department')->latest();

    if ($request->unit_id) $query->where('unit_id', $request->unit_id);
    if ($request->user_id) $query->where('user_id', $request->user_id);
    if ($request->start_date && $request->end_date) $query->whereBetween('start_date', [$request->start_date, $request->end_date]);

    $payrolls = $query->get();

    Mail::to(auth()->user()->email)->send(new ProcessedPayrollReport($payrolls));

    return back()->with('success', 'Processed payroll report emailed successfully!');
}

private function calculateAdjustments($staff, $unitId, $startDate, $endDate, $daysWorked)
{
    if ($daysWorked === 0) {
        return ['bonus' => 0, 'deduction' => 0];
    }

    $bonuses = Bonus::where('unit_id', $unitId)
        ->where('department_id', $staff->department_id)
        ->whereBetween('created_at', [$startDate, $endDate])
        ->get();

    $deductions = Deduction::where('unit_id', $unitId)
        ->where('department_id', $staff->department_id)
        ->whereBetween('created_at', [$startDate, $endDate])
        ->get();

    $totalBonus = 0;
    foreach ($bonuses as $bonus) {
        $totalBonus += $bonus->per_day
            ? $bonus->amount * $daysWorked
            : $bonus->amount;
    }

    $totalDeduction = 0;
    foreach ($deductions as $deduction) {
        $totalDeduction += $deduction->per_day
            ? $deduction->amount * $daysWorked
            : $deduction->amount;
    }

    return [
        'bonus' => $totalBonus,
        'deduction' => $totalDeduction,
    ];
}


}
