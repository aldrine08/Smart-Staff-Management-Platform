<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\OffDayRequest;
use App\Models\Unit;
use App\Models\Department;
use Carbon\CarbonImmutable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use App\Exports\OffDayRequestsExport;
use Maatwebsite\Excel\Facades\Excel;

class OffDayRequestController extends Controller
{
    /**
     * Display off-day requests using application-date filters.
     */
    public function index(Request $request)
    {
        $this->validateFilters($request);

        // Do not allow monthly and custom date filters
        // to be applied simultaneously.
        if (
            $request->filled('created_month') &&
            (
                $request->filled('created_from') ||
                $request->filled('created_to')
            )
        ) {
            throw ValidationException::withMessages([
                'created_month' =>
                    'Please select either a reporting month or a custom date range, not both.',
            ]);
        }

        // Ensure custom date range is valid.
        if (
            $request->filled('created_from') &&
            $request->filled('created_to')
        ) {
            $from = CarbonImmutable::parse(
                $request->created_from
            );

            $to = CarbonImmutable::parse(
                $request->created_to
            );

            if ($to->lessThan($from)) {
                throw ValidationException::withMessages([
                    'created_to' =>
                        'The end date must be on or after the start date.',
                ]);
            }
        }

        $adminId = Auth::id();

        // Build the filtered query.
        $query = $this->buildFilteredQuery(
            $request,
            $adminId
        );

        // Display matching requests.
        $offDayRequests = (clone $query)
            ->orderByDesc('created_at')
            ->get();

        // KPI statistics based on the same filters.
        $summary = [
            'total' => (clone $query)->count(),

            'pending' => (clone $query)
                ->where('status', 'pending')
                ->count(),

            'approved' => (clone $query)
                ->where('status', 'approved')
                ->count(),

            'declined' => (clone $query)
                ->where('status', 'declined')
                ->count(),
        ];

        // Only units belonging to this administrator.
        $units = Unit::query()
            ->where('admin_id', $adminId)
            ->orderBy('name')
            ->get();

        // Only departments belonging to staff
        // within this administrator's units.
        $departments = Department::query()
            ->whereHas('users.unit', function ($q) use ($adminId) {
                $q->where('admin_id', $adminId);
            })
            ->orderBy('name')
            ->get();

        return view('admin.offdays.index', compact(
            'offDayRequests',
            'units',
            'departments',
            'summary'
        ));
    }


/**
 * Download filtered off-day requests as an Excel workbook.
 */
public function export(Request $request)
{
    // Apply exactly the same validation as the listing page.
    $this->validateFilters($request);

    $adminId = Auth::id();

    // Reuse the listing query so the export matches
    // the records displayed on the page.
    $offDayRequests = $this->buildFilteredQuery(
        $request,
        $adminId
    )
        ->orderByDesc('created_at')
        ->get();

    /*
     * Resolve selected filter names safely.
     * Never expose another administrator's units
     * or departments in the report metadata.
     */

    $unitName = null;

    if ($request->filled('unit_id')) {
        $unitName = Unit::query()
            ->where('admin_id', $adminId)
            ->whereKey($request->unit_id)
            ->value('name');
    }

    $departmentName = null;

    if ($request->filled('department_id')) {
        $departmentName = Department::query()
            ->whereKey($request->department_id)
            ->whereHas('users.unit', function ($q) use ($adminId) {
                $q->where('admin_id', $adminId);
            })
            ->value('name');
    }

    // Information displayed in the Excel report.
    $filters = [
        'created_month' => $request->created_month,
        'created_from' => $request->created_from,
        'created_to' => $request->created_to,
        'staff_name' => $request->staff_name,
        'unit' => $unitName,
        'department' => $departmentName,
        'status' => $request->status,
    ];

    /*
     * Generate a descriptive filename.
     */

    if ($request->filled('created_month')) {

        $period = CarbonImmutable::parse(
            $request->created_month . '-01'
        )->format('Y-m');

    } elseif (
        $request->filled('created_from') ||
        $request->filled('created_to')
    ) {

        $from = $request->created_from ?: 'any-date';
        $to = $request->created_to ?: 'any-date';

        $period = $from . '-to-' . $to;

    } else {

        $period = 'all-dates';
    }

    if ($request->filled('status')) {
        $period .= '-' . $request->status;
    }

    $filename = 'off-day-requests-' . $period . '.xlsx';

    // Download the Excel workbook.
    return Excel::download(
        new OffDayRequestsExport(
            $offDayRequests,
            $filters
        ),
        $filename
    );
}

/**
 * Validate report filters for both the listing and export.
 */
private function validateFilters(Request $request): void
{
    $request->validate([
        'staff_name' => ['nullable', 'string', 'max:255'],
        'unit_id' => ['nullable', 'integer'],
        'department_id' => ['nullable', 'integer'],
        'status' => ['nullable', 'in:pending,approved,declined'],

        'created_month' => [
            'nullable',
            'regex:/^\d{4}-(0[1-9]|1[0-2])$/'
        ],

        'created_from' => ['nullable', 'date_format:Y-m-d'],
        'created_to' => ['nullable', 'date_format:Y-m-d'],
    ]);

    // Monthly and custom date filters cannot be combined.
    if (
        $request->filled('created_month') &&
        (
            $request->filled('created_from') ||
            $request->filled('created_to')
        )
    ) {
        throw ValidationException::withMessages([
            'created_month' =>
                'Please select either a reporting month or a custom date range, not both.',
        ]);
    }

    // Validate custom date range order.
    if (
        $request->filled('created_from') &&
        $request->filled('created_to')
    ) {
        $from = CarbonImmutable::parse(
            $request->created_from
        );

        $to = CarbonImmutable::parse(
            $request->created_to
        );

        if ($to->lessThan($from)) {
            throw ValidationException::withMessages([
                'created_to' =>
                    'The end date must be on or after the start date.',
            ]);
        }
    }
}


    /**
     * Build the common filtered request query.
     *
     * This method will also be reused by the Excel export.
     */
    private function buildFilteredQuery(
        Request $request,
        int $adminId
    ) {
        $query = OffDayRequest::query()
            ->with([
                'user.unit',
                'user.department'
            ])
            ->whereHas('user.unit', function ($q) use ($adminId) {
                $q->where('admin_id', $adminId);
            });

        // Staff name.
        if ($request->filled('staff_name')) {
            $query->whereHas('user', function ($q) use ($request) {
                $q->where(
                    'name',
                    'like',
                    '%' . $request->staff_name . '%'
                );
            });
        }

        // Unit.
        if ($request->filled('unit_id')) {
            $query->whereHas('user', function ($q) use ($request) {
                $q->where('unit_id', $request->unit_id);
            });
        }

        // Department.
        if ($request->filled('department_id')) {
            $query->whereHas('user', function ($q) use ($request) {
                $q->where(
                    'department_id',
                    $request->department_id
                );
            });
        }

        // Request status.
        if ($request->filled('status')) {
            $query->where(
                'status',
                $request->status
            );
        }

        /*
         * REPORTING PERIOD
         *
         * IMPORTANT:
         * All date filters below use created_at,
         * NOT start_date or end_date.
         */

        // Monthly application-date filter.
        if ($request->filled('created_month')) {

            $monthStart = CarbonImmutable::parse(
                $request->created_month . '-01'
            )->startOfMonth();

            $monthEnd = $monthStart->endOfMonth()->endOfDay();

            $query->whereBetween('created_at', [
                $monthStart,
                $monthEnd
            ]);
        } else {

            // Custom starting application date.
            if ($request->filled('created_from')) {
                $from = CarbonImmutable::parse(
                    $request->created_from
                )->startOfDay();

                $query->where(
                    'created_at',
                    '>=',
                    $from
                );
            }

            // Custom ending application date.
            if ($request->filled('created_to')) {
                $to = CarbonImmutable::parse(
                    $request->created_to
                )->endOfDay();

                $query->where(
                    'created_at',
                    '<=',
                    $to
                );
            }
        }

        return $query;
    }
}
