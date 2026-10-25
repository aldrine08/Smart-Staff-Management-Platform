<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\OffDayLedgerEntry;
use App\Models\OffDayRequest;
use App\Models\User;
use App\Services\OffDayAccrualService;
use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\DB;

class OffDayResponseController extends Controller
{
    public function approve(
        $id,
        OffDayAccrualService $accrualService
    ) {
        $adminId = auth()->id();

        /*
         * First find the request using tenant-safe ownership.
         * The employee must belong to the current admin's
         * unit and department.
         */
        $offDay = OffDayRequest::query()
            ->whereHas('user', function ($query) use ($adminId) {
                $query->where('role', 'staff')
                    ->whereHas('unit', function ($unit) use ($adminId) {
                        $unit->where('admin_id', $adminId);
                    })
                    ->whereHas('department', function ($department) use ($adminId) {
                        $department->where('admin_id', $adminId);
                    });
            })
            ->findOrFail($id);

        $goLive = config('offdays.go_live_at');

        if (!$goLive) {
            return back()->with(
                'error',
                'Ledger approvals are not activated. Configure the go-live timestamp after opening balances are reconciled.'
            );
        }

        try {
            $goLiveDate = CarbonImmutable::parse($goLive);
        } catch (\Throwable $exception) {
            return back()->with(
                'error',
                'The off-day ledger activation timestamp is invalid.'
            );
        }

        if (!$offDay->created_at ||
            $offDay->created_at->lt($goLiveDate)) {
            return back()->with(
                'error',
                'This is a historical request. It cannot be automatically deducted under the forward-only policy.'
            );
        }

        $result = DB::transaction(function () use (
            $offDay,
            $adminId,
            $goLiveDate,
            $accrualService
        ) {
            /*
             * Lock the employee first. This serializes
             * simultaneous approval attempts affecting
             * the same employee balance.
             */
            $employee = User::query()
                ->whereKey($offDay->user_id)
                ->where('role', 'staff')
                ->whereHas('unit', function ($query) use ($adminId) {
                    $query->where('admin_id', $adminId);
                })
                ->whereHas('department', function ($query) use ($adminId) {
                    $query->where('admin_id', $adminId);
                })
                ->lockForUpdate()
                ->firstOrFail();

            /*
             * Re-read and lock the request after locking
             * the employee, so we use its current status.
             */
            $request = OffDayRequest::query()
                ->whereKey($offDay->id)
                ->where('user_id', $employee->id)
                ->lockForUpdate()
                ->firstOrFail();

            if ($request->status !== 'pending') {
                return [
                    'success' => false,
                    'message' => 'Only pending requests may be approved.',
                ];
            }

            if (!$request->created_at ||
                $request->created_at->lt($goLiveDate)) {
                return [
                    'success' => false,
                    'message' => 'Historical requests require a separate reconciliation process.',
                ];
            }

            $department = $employee->department;

            $settings = $department?->offDaySetting;

            if (!$settings || !$settings->is_enabled) {
                return [
                    'success' => false,
                    'message' => 'Off-day accrual is disabled for this department.',
                ];
            }

            try {
                $start = CarbonImmutable::parse(
                    $request->start_date
                )->startOfDay();

                $end = CarbonImmutable::parse(
                    $request->end_date
                )->startOfDay();
            } catch (\Throwable $exception) {
                return [
                    'success' => false,
                    'message' => 'The request dates are invalid.',
                ];
            }

            if ($end->lessThanOrEqualTo($start)) {
                return [
                    'success' => false,
                    'message' => 'The request end date precedes its start date.',
                ];
            }

            /*
             * Calendar days, inclusive.
             *
             * One-day request: 1
             * Monday through Tuesday: 2
             * Friday through Monday: 4
             */
            $requestedDays = (int) $start->diffInDays($end);

            if ($requestedDays > config('offdays.maximum_request_days', 366)) {
                return [
                    'success' => false,
                    'message' => 'The reporting date must be after the off-day start date.',
                ];
            }

            /*
             * Reject overlap with any already approved
             * request for this employee, including legacy
             * approved records.
             */
            $hasOverlap = OffDayRequest::query()
                ->where('user_id', $employee->id)
                ->where('id', '!=', $request->id)
                ->where('status', 'approved')
                ->whereDate('start_date', '<', $end->toDateString())
                ->whereDate('end_date', '>', $start->toDateString())
                ->exists();

            if ($hasOverlap) {
                return [
                    'success' => false,
                    'message' => 'These dates overlap another approved off-day request.',
                ];
            }

            /*
             * Synchronize missing earned days before
             * calculating the balance.
             */
            $accrualService->syncEmployee(
                $employee,
                $department
            );

            $balance = $accrualService->balanceHundredths(
                $employee
            );

            $deduction = $requestedDays * 100;

            if ($balance < $deduction) {
                return [
                    'success' => false,
                    'message' => sprintf(
                        'Insufficient off-day balance. Available: %s days; requested: %d days.',
                        $accrualService->formatDays($balance),
                        $requestedDays
                    ),
                ];
            }

            /*
             * Unique event key prevents duplicate
             * deductions for this request.
             */
            $entryKey = 'approved-request:' . $request->id;

            if (OffDayLedgerEntry::query()
                ->where('user_id', $employee->id)
                ->where('entry_key', $entryKey)
                ->exists()) {
                return [
                    'success' => false,
                    'message' => 'This request already has a ledger deduction.',
                ];
            }

            OffDayLedgerEntry::create([
                'user_id' => $employee->id,
                'department_id' => $department->id,
                'entry_key' => $entryKey,
                'entry_type' => 'approved_usage',
                'amount_hundredths' => -$deduction,
                'effective_on' => now()->toDateString(),
                'description' => sprintf(
                    'Approved off-day request #%d (%s to %s)',
                    $request->id,
                    $start->toDateString(),
                    $end->toDateString()
                ),
            ]);

            $request->status = 'approved';
            $request->save();

            return [
                'success' => true,
                'message' => sprintf(
                    '%d calendar day(s) approved and deducted successfully.',
                    $requestedDays
                ),
            ];
        }, 3);

        return back()->with(
            $result['success'] ? 'success' : 'error',
            $result['message']
        );
    }

    public function decline($id)
    {
        $adminId = auth()->id();

        $offDay = OffDayRequest::query()
            ->whereHas('user', function ($query) use ($adminId) {
                $query->where('role', 'staff')
                    ->whereHas('unit', function ($unit) use ($adminId) {
                        $unit->where('admin_id', $adminId);
                    })
                    ->whereHas('department', function ($department) use ($adminId) {
                        $department->where('admin_id', $adminId);
                    });
            })
            ->findOrFail($id);

        $result = DB::transaction(function () use ($offDay, $adminId) {
            $employee = User::query()
                ->whereKey($offDay->user_id)
                ->where('role', 'staff')
                ->whereHas('unit', function ($query) use ($adminId) {
                    $query->where('admin_id', $adminId);
                })
                ->whereHas('department', function ($query) use ($adminId) {
                    $query->where('admin_id', $adminId);
                })
                ->lockForUpdate()
                ->firstOrFail();

            $request = OffDayRequest::query()
                ->whereKey($offDay->id)
                ->where('user_id', $employee->id)
                ->lockForUpdate()
                ->firstOrFail();

            if ($request->status !== 'pending') {
                return [
                    'success' => false,
                    'message' => 'Only pending requests may be declined.',
                ];
            }

            $request->status = 'declined';
            $request->save();

            return [
                'success' => true,
                'message' => 'Off-day request declined. No days were deducted.',
            ];
        }, 3);

        return back()->with(
            $result['success'] ? 'success' : 'error',
            $result['message']
        );
    }
}
