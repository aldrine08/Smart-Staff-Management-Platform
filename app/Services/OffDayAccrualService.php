<?php

namespace App\Services;

use App\Models\Department;
use App\Models\OffDayLedgerEntry;
use App\Models\User;
use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Date;

class OffDayAccrualService
{
    /**
     * Synchronize all eligible employees in one department.
     *
     * Returns counts, not personal employee data.
     */
    public function syncDepartment(Department $department): array
    {
        $settings = $department->offDaySetting;

        $result = [
            'processed' => 0,
            'skipped_missing_joining_date' => 0,
            'entries_created' => 0,
        ];

        if (!$settings || !$settings->is_enabled) {
            return $result;
        }

        User::query()
            ->where('role', 'staff')
            ->where('department_id', $department->id)
            ->whereHas('unit', function ($query) use ($department) {
                $query->where('admin_id', $department->admin_id);
            })
            ->orderBy('id')
            ->chunkById(100, function ($staff) use (
                $department,
                &$result
            ) {
                foreach ($staff as $employee) {
                    if (!$employee->start_date) {
                        $result['skipped_missing_joining_date']++;
                        continue;
                    }

                    $result['entries_created'] +=
                        $this->syncEmployee($employee, $department);

                    $result['processed']++;
                }
            });

        return $result;
    }

    /**
     * Add any missing monthly credits and annual carryover
     * expiration entries for a single eligible employee.
     */
    public function syncEmployee(
        User $employee,
        Department $department
    ): int {
        return DB::transaction(function () use ($employee, $department) {
            // Serialize accrual operations for the same employee.
            $employee = User::query()
                ->whereKey($employee->id)
                ->where('role', 'staff')
                ->where('department_id', $department->id)
                ->whereHas('unit', function ($query) use ($department) {
                    $query->where('admin_id', $department->admin_id);
                })
                ->lockForUpdate()
                ->firstOrFail();

            $settings = $department->offDaySetting;

            if (!$settings || !$settings->is_enabled ||
                !$employee->start_date) {
                return 0;
            }

            $joiningDate = CarbonImmutable::parse(
                $employee->start_date
            )->startOfDay();

            $today = CarbonImmutable::parse(
                Date::now()
            )->startOfDay();

            if ($joiningDate->greaterThan($today)) {
                return 0;
            }

            // The joining month earns a credit only if
            // employment began on its first calendar day.
            $firstMonth = $joiningDate->day === 1
                ? $joiningDate->startOfMonth()
                : $joiningDate->startOfMonth()->addMonth();

            // Do not credit the current incomplete month.
            $lastCompletedMonth = $today
                ->startOfMonth()
                ->subMonth();

            if ($firstMonth->greaterThan($lastCompletedMonth)) {
                return 0;
            }

            $monthlyHundredths = (int) round(
                (float) $settings->monthly_accrual * 100
            );

            $capHundredths = (int) round(
                (float) $settings->carryover_cap * 100
            );

            $created = 0;

            for (
                $month = $firstMonth;
                $month->lessThanOrEqualTo($lastCompletedMonth);
                $month = $month->addMonth()
            ) {
                // Before crediting January, apply the cap
                // to the balance brought forward from
                // previous calendar years.
                if ($month->month === 1 &&
                    $month->year > $joiningDate->year) {

                    $previousYearEnd = $month
                        ->startOfYear()
                        ->subDay();

                    $previousBalance = (int)
                        OffDayLedgerEntry::query()
                            ->where('user_id', $employee->id)
                            ->whereDate(
                                'effective_on',
                                '<=',
                                $previousYearEnd->toDateString()
                            )
                            ->sum('amount_hundredths');

                    $excess = max(
                        0,
                        $previousBalance - $capHundredths
                    );

                    $carryoverKey = 'carryover:' . $month->year;

                    // Record the annual decision once,
                    // even when no days must expire.
                    $entry = OffDayLedgerEntry::firstOrCreate(
                        [
                            'user_id' => $employee->id,
                            'entry_key' => $carryoverKey,
                        ],
                        [
                            'department_id' => $department->id,
                            'entry_type' => 'carryover_expiration',
                            'amount_hundredths' => -$excess,
                            'effective_on' => $month->toDateString(),
                            'description' =>
                                'Annual carryover cap applied.',
                        ]
                    );

                    if ($entry->wasRecentlyCreated) {
                        $created++;
                    }
                }

                $key = 'accrual:' . $month->format('Y-m');

                $entry = OffDayLedgerEntry::firstOrCreate(
                    [
                        'user_id' => $employee->id,
                        'entry_key' => $key,
                    ],
                    [
                        'department_id' => $department->id,
                        'entry_type' => 'accrual',
                        'amount_hundredths' => $monthlyHundredths,
                        'effective_on' => $month
                            ->endOfMonth()
                            ->toDateString(),
                        'description' =>
                            'Monthly off-day accrual: ' .
                            $month->format('F Y'),
                    ]
                );

                if ($entry->wasRecentlyCreated) {
                    $created++;
                }
            }

            return $created;
        });
    }

    /**
     * Read the recorded balance as integer hundredths.
     */
    public function balanceHundredths(
        User $employee,
        ?CarbonImmutable $asOf = null
    ): int {
        $asOf ??= CarbonImmutable::parse(
            Date::now()
        )->startOfDay();

        return (int) OffDayLedgerEntry::query()
            ->where('user_id', $employee->id)
            ->whereDate(
                'effective_on',
                '<=',
                $asOf->toDateString()
            )
            ->sum('amount_hundredths');
    }

    /**
     * Format 525 hundredths as 5.25 days.
     */
    public function formatDays(int $hundredths): string
    {
        return number_format($hundredths / 100, 2, '.', '');
    }
}
