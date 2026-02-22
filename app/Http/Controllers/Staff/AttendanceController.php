<?php


namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\AttendanceExportMail;
use PDF;
use Storage;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\AttendanceExport;



class AttendanceController extends Controller
{
  public function index(Request $request)
{
    // 🔒 Step 1: Lock records to logged-in staff
    $query = Attendance::where('user_id', auth()->id());

    // Step 2: Apply date filters
    if ($request->filled('start_date')) {
        $query->whereDate('date', '>=', $request->start_date);
    }

    if ($request->filled('end_date')) {
        $query->whereDate('date', '<=', $request->end_date);
    }

    // Step 3: Fetch records
    $attendances = $query
        ->orderBy('date', 'desc')
        ->get();

    return view('staff.Attendance.index', compact('attendances'));
}

   public function exportEmail(Request $request)
{
    $user = Auth::user();

    // 🔒 Step 1: Lock to staff user
    $attendances = Attendance::where('user_id', $user->id)
        ->when($request->filled('start_date'), function ($q) use ($request) {
            $q->whereDate('date', '>=', $request->start_date);
        })
        ->when($request->filled('end_date'), function ($q) use ($request) {
            $q->whereDate('date', '<=', $request->end_date);
        })
        ->get();

    // Step 2: Create Excel file
    $fileName = 'attendance_' . now()->format('Ymd_His') . '.xlsx';
    Excel::store(new AttendanceExport($attendances), $fileName);

    // Step 3: Email file
    Mail::to($user->email)->send(
        new AttendanceExportMail(storage_path('app/' . $fileName))
    );

    // Step 4: Clean up
    Storage::delete($fileName);

    return back()->with('success', 'Attendance report emailed successfully!');
}



  public function exportPdf(Request $request)
{
    $attendances = Attendance::where('user_id', Auth::id())
        ->when($request->filled('start_date'), function ($q) use ($request) {
            $q->whereDate('date', '>=', $request->start_date);
        })
        ->when($request->filled('end_date'), function ($q) use ($request) {
            $q->whereDate('date', '<=', $request->end_date);
        })
        ->orderBy('date', 'desc')
        ->get();

    $pdf = PDF::loadView('staff.Attendance.pdf', compact('attendances'));

    return $pdf->download('my_attendance.pdf');
}


}
