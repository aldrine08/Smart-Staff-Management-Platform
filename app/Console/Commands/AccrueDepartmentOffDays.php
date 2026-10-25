<?php

namespace App\Console\Commands;

use App\Models\Department;
use App\Services\OffDayAccrualService;
use Illuminate\Console\Command;

class AccrueDepartmentOffDays extends Command
{
    protected $signature = 'offdays:accrue
        {department : The department ID}';

    protected $description =
        'Synchronize earned off days for an enabled department';

    public function handle(
        OffDayAccrualService $service
    ): int {
        $department = Department::query()
            ->findOrFail($this->argument('department'));

        $settings = $department->offDaySetting;

        if (!$settings || !$settings->is_enabled) {
            $this->warn(
                'Off-day accrual is disabled for this department.'
            );

            return self::FAILURE;
        }

        $this->info(
            "Synchronizing off days for {$department->name}"
        );

        $result = $service->syncDepartment($department);

        $this->table(
            ['Metric', 'Result'],
            [
                ['Employees processed', $result['processed']],
                [
                    'Missing joining dates',
                    $result['skipped_missing_joining_date'],
                ],
                ['New ledger entries', $result['entries_created']],
            ]
        );

        $this->info('Off-day accrual synchronization completed.');

        return self::SUCCESS;
    }
}
