<?php

namespace App\Services;

use App\Models\OffDayLedgerEntry;
use App\Models\OffDayRequest;
use App\Models\User;
use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\DB;

class StaffOffDaySummaryService
{
    public function forUser(User $user): array
    {
        $today = CarbonImmutable::today();

        // Read only the authenticated employee's records.
        $ledger = OffDayLedgerEntry::query()
            ->where('user_id', $user->id)
            ->whereDate('effective_on', '<=', $today->toDateString());

        $totals = (clone $ledger)
            ->selectRaw('COALESCE(SUM(amount_hundredths), 0) as balance')
            ->selectRaw(
                "COALESCE(SUM(CASE WHEN entry_type = 'accrual'
                 THEN amount_hundredths ELSE 0 END), 0) as earned"
            )
            ->selectRaw(
                "COALESCE(SUM(CASE WHEN entry_type = 'approved_usage'
                 THEN -amount_hundredths ELSE 0 END), 0) as used"
            )
            ->selectRaw(
                "COALESCE(SUM(CASE WHEN entry_type = 'carryover_expiration'
                 THEN -amount_hundredths ELSE 0 END), 0) as expired"
            )
            ->first();

        $pending = OffDayRequest::query()
            ->where('user_id', $user->id)
            ->where('status', 'pending')
            ->get(['id', 'start_date', 'end_date']);

        $pendingDays = $pending->sum(function ($request) {
            if (!$request->start_date || !$request->end_date) {
                return 0;
            }

            try {
                $start = CarbonImmutable::parse(
                    $request->start_date
                )->startOfDay();

                $end = CarbonImmutable::parse(
                    $request->end_date
                )->startOfDay();

                return $end->greaterThanOrEqualTo($start)
                    ? (int) $start->diffInDays($end) + 1
                    : 0;
            } catch (\Throwable $exception) {
                return 0;
            }
        });

        // The department relationship is scoped to
        // the staff member, never to a requested user ID.
        $department = $user->department;

        // Only accept a policy when the employee's unit
        // and department belong to the same admin.
        $ownsAssignment =
            $user->role === 'staff' &&
            $department !== null &&
            $user->unit !== null &&
            (int) $department->admin_id ===
                (int) $user->unit->admin_id;

        $settings = $ownsAssignment
            ? $department->offDaySetting
            : null;

        $ledgerApprovalsActive = false;

        if (config('offdays.go_live_at')) {
            try {
                $ledgerApprovalsActive =
                    CarbonImmutable::parse(
                        config('offdays.go_live_at')
                    )->lessThanOrEqualTo(CarbonImmutable::now());
            } catch (\Throwable $exception) {
                $ledgerApprovalsActive = false;
            }
        }

        return [
            'policy_enabled' =>
                (bool) ($settings?->is_enabled ?? false),

            'monthly_entitlement' =>
                $settings?->monthly_accrual ?? '0.00',

            'carryover_cap' =>
                $settings?->carryover_cap ?? '0.00',

            'earned_hundredths' =>
                (int) ($totals->earned ?? 0),

            'used_hundredths' =>
                (int) ($totals->used ?? 0),

            'expired_hundredths' =>
                (int) ($totals->expired ?? 0),

            'balance_hundredths' =>
                (int) ($totals->balance ?? 0),

            'pending_requests' => $pending->count(),

            'pending_calendar_days' => (int) $pendingDays,

            'ledger_approvals_active' =>
                $ledgerApprovalsActive,

            // Historical reconciliation is not yet
            // represented by a confirmed database flag.
            'balance_reconciled' => false,
        ];
    }
}
