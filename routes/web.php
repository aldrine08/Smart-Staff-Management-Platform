<?php

use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\AttendanceReportController;
use App\Http\Controllers\Admin\AttendanceReportController as AdminAttendanceReportController;
use App\Http\Middleware\AdminMiddleware;
use App\Http\Middleware\StaffMiddleware;
use App\Http\Controllers\Admin\StaffController;
use App\Http\Controllers\Admin\StaffReportController;
use App\Http\Controllers\Admin\UnitController;
use App\Http\Controllers\Admin\ClockInSettingController;
use App\Http\Controllers\Admin\DepartmentController;
use App\Http\Controllers\Admin\DepartmentReportController;
use App\Http\Controllers\Staff\OffDayController;
use App\Http\Controllers\Admin\OffDayRequestController;
use App\Http\Controllers\Admin\OffDayResponseController;
use Carbon\Carbon;
use App\Http\Controllers\Admin\SalarySettingController;
use App\Http\Controllers\Admin\DeductionController;
use App\Http\Controllers\Admin\BonusController;
use App\Http\Controllers\Admin\PayrollController;
use App\Http\Controllers\Staff\StaffPayrollController;
use App\Http\Controllers\Staff\StaffDashboardController;
use App\Http\Controllers\Admin\ItemController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\Staff\AttendanceReportController as StaffAttendanceReportController;
use App\Http\Controllers\Staff\AttendanceController as StaffAttendanceController;
use App\Http\Controllers\SickRequestController;
use App\Http\Controllers\CalendarController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\SuperAdmin\DashboardController;
use App\Http\Middleware\SuperAdminMiddleware;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// -------------------------------
// Guest / Welcome Page
// -------------------------------
Route::get('/', function () {
    return view('welcome');
});

// -------------------------------
// Dashboard Redirect Based on Role
// -------------------------------
Route::get('/dashboard', function () {
    $user = auth()->user();
     if ($user->role === 'super_admin') {
        return redirect()->route('super_admin.dashboard');
    }

    if ($user->role === 'admin') {
        return redirect()->route('admin.dashboard');
    }

    return redirect()->route('staff.dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// -------------------------------
// Authenticated Routes (all users)
// -------------------------------
Route::middleware('auth')->group(function () {

    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Chat Routes
    Route::get('/chat/{room}', [ChatController::class,'index'])->name('chat.index');
    Route::post('/chat/{room}', [ChatController::class,'store'])->name('chat.store');


    Route::get('/calendar', [App\Http\Controllers\CalendarController::class, 'index'])
    ->name('calendar.index');

    Route::post('/events/store', [App\Http\Controllers\EventController::class, 'store']);

    Route::prefix('admin')->middleware(['auth', 'admin'])->group(function () {

    Route::get('/sick-requests', [SickRequestController::class, 'index'])
        ->name('admin.sick-requests.index');

    Route::post('/sick-requests/{id}/approve', [SickRequestController::class, 'approve'])
        ->name('sick-requests.approve');

    Route::post('/sick-requests/{id}/decline', [SickRequestController::class, 'decline'])
        ->name('sick-requests.decline');
});

    Route::get('/events', function () { return \App\Models\Event::all()->map(function ($event) {
        return [
            'title' => $event->title . ' (' . $event->location . ')',
            'start' => $event->start_time,
            'end' => $event->end_time,
        ];
    });
});


Route::post('/notifications/{id}/read', function ($id) {

    $notification = auth()->user()
        ->notifications()
        ->where('id', $id)
        ->first();

    if ($notification) {
        $notification->markAsRead();
        return response()->json(['success' => true]);
    }

    return response()->json(['error' => 'Not found'], 404);

})->name('notifications.read');



    

    // -------------------------------
    // Staff Routes
    // -------------------------------
    Route::middleware(StaffMiddleware::class)->group(function () {

        Route::get('/staff/dashboard', function () {
    $user = auth()->user();
    $today = \Carbon\Carbon::today();

    $attendance = \App\Models\Attendance::where('user_id', $user->id)
                    ->whereDate('date', $today)
                    ->first();

    // Pass actual times to Blade (nullable)
    $clockedIn = $attendance?->clock_in;
    $clockedOut = $attendance?->clock_out;

    // Off Day counts
    $pendingRequests = \App\Models\OffDayRequest::where('user_id', $user->id)
                        ->where('status', 'pending')
                        ->count();
    $approvedOffDays = \App\Models\OffDayRequest::where('user_id', $user->id)
                        ->where('status', 'approved')
                        ->count();
    $totalOffDays = \App\Models\OffDayRequest::where('user_id', $user->id)->count();

    return view('staff.dashboard', compact(
        'clockedIn', 
        'clockedOut', 
        'pendingRequests', 
        'approvedOffDays', 
        'totalOffDays'
    ));
                })->name('staff.dashboard');


                Route::get('/staff/dashboard', [StaffDashboardController::class, 'dashboard'])->name('staff.dashboard');



            // Staff Payroll    
         Route::get('/staff/payroll', [StaffPayrollController::class,'index'])
             ->name('staff.payroll.index');

         Route::get('/staff/payroll/{id}', [StaffPayrollController::class,'show'])
              ->name('staff.payroll.show');

        // Clock In / Clock Out
        Route::post('/clock-in', [AttendanceController::class, 'clockIn'])->name('attendance.clockin');
        Route::post('/clock-out', [AttendanceController::class, 'clockOut'])->name('attendance.clockout');

        // Off Days
        Route::get('offdays', [OffDayController::class, 'index'])->name('offdays.index'); // List own requests
        Route::post('offdays', [OffDayController::class, 'store'])->name('offdays.store'); // Submit new request

        Route::get('/staff/Attendance', [AttendanceController::class, 'index'])  ->name('staff.Attendance.index');

        Route::post('/staff/Attendance/export/email',[AttendanceController::class, 'exportEmail']) ->name('staff.Attendance.export.email');

        Route::get('/staff/Attendance/export/pdf',[AttendanceController::class, 'exportPdf'])  ->name('staff.Attendance.export.pdf');

        Route::post('/attendance/late-reason', [AttendanceController::class, 'saveLateReason'])->name('attendance.late.reason');

        Route::get('/sick-requests', [SickRequestController::class, 'index']) ->name('sick-requests.index');

        Route::post('/sick-requests', [SickRequestController::class, 'store'])   ->name('sick-requests.store');

        Route::post('/sick-requests/{id}/upload', [SickRequestController::class, 'uploadProof'])   ->name('sick-requests.upload');

        Route::get('offdays/{id}/edit', [OffDayController::class, 'edit'])->name('offdays.edit');
        Route::put('offdays/{id}', [OffDayController::class, 'update'])->name('offdays.update');

        Route::get('/staff/loans', [\App\Http\Controllers\Staff\LoanController::class, 'index'])
        ->name('staff.loans.index');

        Route::post('/staff/loans', [\App\Http\Controllers\Staff\LoanController::class, 'store'])
        ->name('staff.loans.store');

        Route::get('/staff/loans/active', [\App\Http\Controllers\Staff\LoanController::class, 'active'])
    ->name('staff.loans.active');

        Route::get('/staff/loans/pending', [\App\Http\Controllers\Staff\LoanController::class, 'pending'])
    ->name('staff.loans.pending');
          

        Route::get('/staff/loans/total', [\App\Http\Controllers\Staff\LoanController::class, 'total'])
    ->name('staff.loans.total');

    
    });

    Route::post('/save-late-reason', [AttendanceController::class, 'submitLateReason']) ->name('attendance.saveLateReason');

   

    // -------------------------------
    // Admin Routes
    // -------------------------------
    Route::middleware(AdminMiddleware::class)->group(function () {

        // Admin Dashboard
        Route::get('/admin/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');
        Route::get('/admin/dashboard/unit/{unit}', [AdminDashboardController::class, 'unitStaff'])
            ->name('admin.dashboard.unit');

        // Units
        Route::post('/admin/units', [UnitController::class, 'store'])->name('admin.units.store');

        Route::resource('admin/departments', DepartmentController::class) ->names('admin.departments');

        Route::get('/admin/units/{id}/edit', [UnitController::class, 'edit']) ->name('admin.units.edit');

        Route::get('/admin/units', [UnitController::class, 'index']) ->name('admin.units.index');

        Route::put('/admin/units/{id}', [UnitController::class, 'update'])->name('admin.units.update');                                    
        Route::delete('/admin/units/{id}', [UnitController::class, 'destroy']) ->name('admin.units.destroy');


   
        // Staff Management
        Route::get('/staff/create', [StaffController::class, 'create'])->name('admin.staff.create');
        Route::post('/staff/store', [StaffController::class, 'store'])->name('admin.staff.store');
        Route::get('/admin/staff/all', [StaffController::class, 'allStaff'])->name('admin.staff.all');

         Route::get('/admin/loans', [\App\Http\Controllers\Admin\LoanController::class, 'index'])
        ->name('admin.loans.index');

    Route::post('/admin/loans/{id}/approve', [\App\Http\Controllers\Admin\LoanController::class, 'approve'])
        ->name('admin.loans.approve');

    Route::post('/admin/loans/{id}/reject', [\App\Http\Controllers\Admin\LoanController::class, 'reject'])
        ->name('admin.loans.reject');

        Route::get('/admin/loans/active', [\App\Http\Controllers\Admin\LoanController::class, 'active'])
    ->name('admin.loans.active');

        Route::post('/admin/loans/{id}/repay', [\App\Http\Controllers\Admin\LoanController::class, 'repay'])
    ->name('admin.loans.repay');

        // Attendance
        Route::get('/admin/attendance', [AttendanceController::class, 'index'])->name('admin.attendance');
        Route::get('/admin/attendance/export', [AttendanceController::class, 'export'])->name('admin.attendance.export');
        Route::get('/admin/attendance/export-email', [AttendanceController::class, 'exportToEmail'])->name('admin.attendance.export-email');

        // Admin Attendance Reports
        Route::get('/admin/attendance/report', [AdminAttendanceReportController::class, 'index'])->name('admin.attendance.report');

        // Manual Clock In / Out
        Route::post('/admin/clock-in/{user}', [AdminDashboardController::class, 'manualClockIn'])->name('admin.clockin');
        Route::post('/admin/clock-out/{user}', [AdminDashboardController::class, 'manualClockOut'])->name('admin.clockout');

        // Admin Off Days
        Route::get('/admin/offdays', [OffDayRequestController::class, 'index'])->name('admin.offdays.index');
        

        Route::get('/admin/staff/{id}', [StaffController::class, 'show'])->name('admin.staff.show');

        Route::get('/admin/staff/{id}/edit', [StaffController::class, 'edit'])->name('admin.staff.edit');

        Route::put('/admin/staff/{id}', [StaffController::class, 'update'])->name('admin.staff.update');


        // Approve / Decline routes
        Route::put('/admin/offdays/{id}/approve', [OffDayResponseController::class, 'approve'])->name('admin.offdays.approve');

        Route::put('/admin/offdays/{id}/decline', [OffDayResponseController::class, 'decline'])->name('admin.offdays.decline');

        // Clock In Settings
        Route::get('/admin/clockin-settings', [ClockInSettingController::class, 'edit'])->name('admin.clockin-settings.edit');
        Route::put('/admin/clockin-settings', [ClockInSettingController::class, 'update'])->name('admin.clockin-settings.update');

        // Salary Settings
        Route::resource('salary_settings', SalarySettingController::class);

        // Deductions
        Route::resource('deductions', DeductionController::class);

        //bonuus
        Route::resource('bonuses', BonusController::class);

        Route::get('payroll', [App\Http\Controllers\Admin\PayrollController::class, 'index'])->name('payroll.index');

        Route::get('/admin/payroll/unit/{unit}', [PayrollController::class, 'unitPayroll'])->name('payroll.unit');
        Route::post('/admin/payroll/unit/{unit}/pay', [PayrollController::class, 'paySalaries'])->name('payroll.unit.pay');

        Route::get('/admin/payroll/processed', [PayrollController::class, 'processedPayrolls'])
           ->name('payroll.processed');

        Route::get('/admin/payroll/processed/download-pdf', [PayrollController::class, 'downloadPDF'])
           ->name('payroll.processed.pdf');

        Route::get('/admin/payroll/processed/email', [PayrollController::class, 'emailReport'])
           ->name('payroll.processed.email');

        Route::post('/admin/staff/{id}/toggle', [StaffController::class, 'toggleStatus']) ->name('admin.staff.toggle');

        Route::delete('/admin/staff/{id}', [StaffController::class, 'destroy'])->name('admin.staff.delete');

         Route::get('/items', [ItemController::class, 'index'])->name('admin.items.index');
         Route::get('/items/create', [ItemController::class, 'create'])->name('admin.items.create');
         Route::post('/items', [ItemController::class, 'store'])->name('admin.items.store');

    Route::get('/items/assign', [ItemController::class, 'assignForm'])->name('admin.items.assign.form');
    Route::post('/items/assign', [ItemController::class, 'assign'])->name('admin.items.assign');
    Route::post('/items/deassign/{id}', [ItemController::class, 'deassign'])->name('admin.items.deassign');

     Route::get('/admin/sick-requests', [SickRequestController::class, 'index']) ->name('admin.sick-requests.index');

    Route::put('/admin/sick-requests/{id}/approve', [SickRequestController::class, 'approve']) ->name('admin.sick-requests.approve');

    Route::put('/admin/sick-requests/{id}/decline', [SickRequestController::class, 'decline']) ->name('admin.sick-requests.decline');
    });
     
        // -------------------------------
    // SUPER ADMIN ROUTES
    // -------------------------------
    Route::middleware(['auth', SuperAdminMiddleware::class])
    ->prefix('super-admin')
    ->name('super_admin.') // 🔥 IMPORTANT FIX
    ->group(function () {

        // Dashboard
        Route::get('/dashboard', [DashboardController::class, 'index'])
            ->name('dashboard');

        // Admin Management
        Route::get('/admins', [\App\Http\Controllers\SuperAdmin\AdminController::class, 'index'])
            ->name('admins.index');

        Route::get('/admins/create', [\App\Http\Controllers\SuperAdmin\AdminController::class, 'create'])
            ->name('admins.create');

        Route::post('/admins', [\App\Http\Controllers\SuperAdmin\AdminController::class, 'store'])
            ->name('admins.store');

        Route::put('/admins/{id}', [\App\Http\Controllers\SuperAdmin\AdminController::class, 'update'])
            ->name('admins.update');

        Route::post('/admins/{id}/deactivate', [\App\Http\Controllers\SuperAdmin\AdminController::class, 'deactivate'])
            ->name('admins.deactivate');

        Route::post('/admins/{id}/activate', [\App\Http\Controllers\SuperAdmin\AdminController::class, 'activate'])
            ->name('admins.activate');

        Route::delete('/admins/{id}', [\App\Http\Controllers\SuperAdmin\AdminController::class, 'destroy'])
            ->name('admins.destroy');

        Route::get('/admins/{id}/edit', [\App\Http\Controllers\SuperAdmin\AdminController::class, 'edit'])
            ->name('admins.edit');

        Route::get('/admins/{id}', [\App\Http\Controllers\SuperAdmin\AdminController::class, 'show'])
            ->name('admins.show');
    });

    

});

require __DIR__.'/auth.php';
