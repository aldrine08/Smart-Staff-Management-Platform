<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class DepartmentOffDayAnalyticsController extends Controller
{
    public function index(Request $request, $departmentId)
    {
        $adminId = auth()->id();

        // Only allow the administrator to access their department.
        $department = Department::query()
            ->where('admin_id', $adminId)
            ->with('offDaySetting')
            ->findOrFail($departmentId);

        $validated = $request->validate([
            'search' => ['nullable', 'string', 'max:100'],
            'filter' => [
                'nullable',
                'in:all,used,unused,available,no_balance',
            ],
        ]);

        $search = trim($validated['search'] ?? '');
        $filter = $validated['filter'] ?? 'all';

        $asOf = now()->toDateString();

        /*
         * Retrieve staff and their ledger aggregates.
         * Ledger entries are filtered by effective date
         * so future-dated transactions cannot inflate
         * today's figures.
         *
         * We intentionally include the employee's entire
         * ledger history. A transferred employee may
         * bring a balance into their new department.
         */
        $staff = User::query()
            ->where('role', 'staff')
            ->where('department_id', $department->id)
            ->whereHas('unit', function ($query) use ($adminId) {
                $query->where('admin_id', $adminId);
            })
            ->with('unit')

            ->withSum([
                'offDayLedgerEntries as ledger_balance' =>
                    function ($query) use ($asOf) {
                        $query->whereDate('effective_on', '<=', $asOf);
                    },
            ], 'amount_hundredths')

            ->withSum([
                'offDayLedgerEntries as earned_amount' =>
                    function ($query) use ($asOf) {
                        $query
                            ->where('entry_type', 'accrual')
                            ->whereDate('effective_on', '<=', $asOf);
                    },
            ], 'amount_hundredths')

            ->withSum([
                'offDayLedgerEntries as used_amount' =>
                    function ($query) use ($asOf) {
                        $query
                            ->where('entry_type', 'approved_usage')
                            ->whereDate('effective_on', '<=', $asOf);
                    },
            ], 'amount_hundredths')

            ->withSum([
                'offDayLedgerEntries as expired_amount' =>
                    function ($query) use ($asOf) {
                        $query
                            ->where('entry_type', 'carryover_expiration')
                            ->whereDate('effective_on', '<=', $asOf);
                    },
            ], 'amount_hundredths')

            ->withCount([
                'offDayLedgerEntries as used_requests_count' =>
                    function ($query) use ($asOf) {
                        $query
                            ->where('entry_type', 'approved_usage')
                            ->whereDate('effective_on', '<=', $asOf);
                    },
            ])

            ->orderBy('name')
            ->orderBy('id')
            ->get();

        /*
         * Normalize the aggregated values to integer
         * hundredths of a day.
         */
        $staff = $staff->map(function ($employee) {
            $employee->balance_hundredths =
                (int) ($employee->ledger_balance ?? 0);

            $employee->earned_hundredths =
                (int) ($employee->earned_amount ?? 0);

            $employee->used_hundredths =
                abs((int) ($employee->used_amount ?? 0));

            $employee->expired_hundredths =
                abs((int) ($employee->expired_amount ?? 0));

            return $employee;
        });

        /*
         * Department KPIs use ALL eligible employees,
         * not only the search/filter results.
         */
        $totalStaff = $staff->count();

        $staffWithBalance = $staff
            ->filter(fn ($employee) =>
                $employee->balance_hundredths > 0
            )->count();

        $staffWhoUsed = $staff
            ->filter(fn ($employee) =>
                $employee->used_requests_count > 0
            )->count();

        $staffNotUtilized = $staff
            ->filter(fn ($employee) =>
                $employee->used_requests_count === 0
            )->count();

        $totalEarnedHundredths = $staff
            ->sum('earned_hundredths');

        $totalUsedHundredths = $staff
            ->sum('used_hundredths');

        $totalExpiredHundredths = $staff
            ->sum('expired_hundredths');

        $totalRemainingHundredths = $staff
            ->sum('balance_hundredths');

        /*
         * Filter the employee directory.
         */
        $filtered = $staff->filter(
            function ($employee) use ($search, $filter) {

                if ($search !== '') {
                    $haystack = mb_strtolower(
                        $employee->name . ' ' .
                        $employee->email . ' ' .
                        ($employee->unit?->name ?? '')
                    );

                    if (!str_contains(
                        $haystack,
                        mb_strtolower($search)
                    )) {
                        return false;
                    }
                }

                return match ($filter) {
                    'used' =>
                        $employee->used_requests_count > 0,

                    'unused' =>
                        $employee->used_requests_count === 0,

                    'available' =>
                        $employee->balance_hundredths > 0,

                    'no_balance' =>
                        $employee->balance_hundredths <= 0,

                    default => true,
                };
            }
        )->values();

        /*
         * Paginate after filtering.
         * All KPIs remain department-wide.
         */
        $perPage = 15;

        $page = LengthAwarePaginator::resolveCurrentPage();

        $paginatedStaff = new LengthAwarePaginator(
            $filtered->forPage($page, $perPage)->values(),
            $filtered->count(),
            $perPage,
            $page,
            [
                'path' => $request->url(),
                'query' => $request->query(),
            ]
        );

        return view(
            'admin.departments.offday-analytics',
            [
                'department' => $department,
                'staff' => $paginatedStaff,
                'search' => $search,
                'filter' => $filter,
                'asOf' => $asOf,
                'totalStaff' => $totalStaff,
                'staffWithBalance' => $staffWithBalance,
                'staffWhoUsed' => $staffWhoUsed,
                'staffNotUtilized' => $staffNotUtilized,
                'totalEarnedHundredths' => $totalEarnedHundredths,
                'totalUsedHundredths' => $totalUsedHundredths,
                'totalExpiredHundredths' => $totalExpiredHundredths,
                'totalRemainingHundredths' => $totalRemainingHundredths,
            ]
        );
    }
}
