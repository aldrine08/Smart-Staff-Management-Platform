<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\DepartmentOffDaySetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class DepartmentOffDaySettingController extends Controller
{
    public function edit($departmentId)
    {
        $department = Department::query()
            ->where('admin_id', auth()->id())
            ->findOrFail($departmentId);

        $settings = $department->offDaySetting;

        return view(
            'admin.departments.offday-settings',
            compact('department', 'settings')
        );
    }

    public function update(Request $request, $departmentId)
    {
        $department = Department::query()
            ->where('admin_id', auth()->id())
            ->findOrFail($departmentId);

        $validated = $request->validate([
            'is_enabled' => [
                'required',
                Rule::in(['0', '1']),
            ],
            'monthly_accrual' => [
                'required',
                'numeric',
                'gt:0',
                'max:999999.99',
                'decimal:0,2',
            ],
            'carryover_cap' => [
                'required',
                'numeric',
                'min:0',
                'max:999999.99',
                'decimal:0,2',
            ],

            'allow_advance_off_days' => [
    'required',
    Rule::in(['0', '1']),
],

'maximum_advance_days' => [
    'required',
    'numeric',
    'min:0',
    'max:999999.99',
    'decimal:0,2',
],


        ]);

        if (
    $validated['allow_advance_off_days'] === '1' &&
    (float) $validated['maximum_advance_days'] <= 0
) {
    throw \Illuminate\Validation\ValidationException::withMessages([
        'maximum_advance_days' =>
            'Enter an advance limit greater than zero when advance approvals are enabled.',
    ]);
}

        DB::transaction(function () use ($department, $validated) {
            // Serialize changes to the same department's policy.
            Department::query()
                ->whereKey($department->id)
                ->where('admin_id', auth()->id())
                ->lockForUpdate()
                ->firstOrFail();

            DepartmentOffDaySetting::updateOrCreate(
                [
                    'department_id' => $department->id,
                ],
                [
                    'is_enabled' => (bool) $validated['is_enabled'],
                    'monthly_accrual' => $validated['monthly_accrual'],
                    'carryover_cap' => $validated['carryover_cap'],
                    'accrual_start_rule' => 'joining_date',
                    'day_counting_rule' => 'calendar_days',
                    'historical_request_rule' => 'forward_only',
                    'allow_advance_off_days' =>
    $validated['allow_advance_off_days'] === '1',

'maximum_advance_days' =>
    $validated['allow_advance_off_days'] === '1'
        ? $validated['maximum_advance_days']
        : 0,
                ]
            );
        });

        return redirect()
            ->route('admin.departments.offday-settings.edit', $department->id)
            ->with('success', 'Department off-day settings saved successfully.');
    }
}
