<x-app-layout>

    <x-slot name="header">
        <div class="flex flex-col sm:flex-row
                    sm:items-center sm:justify-between gap-4">

            <div>
                <h2 class="text-xl font-bold text-gray-900">
                    {{ $department->name }} — Off-Day Analytics
                </h2>

                <p class="text-sm text-gray-500 mt-1">
                    Employee off-day entitlement, usage and balances
                </p>
            </div>

            <div class="flex flex-wrap gap-3">

                <a href="{{ route('admin.departments.offday-settings.edit', $department->id) }}"
                   class="px-4 py-2 rounded-xl bg-purple-700
                          text-white text-sm font-semibold
                          hover:bg-purple-800 transition">
                    ⚙ Settings
                </a>

                <a href="{{ route('admin.departments.show', $department->id) }}"
                   class="px-4 py-2 rounded-xl bg-gray-100
                          text-gray-700 text-sm font-semibold
                          hover:bg-gray-200 transition">
                    ← Department
                </a>

            </div>
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8
                py-8 space-y-7">

        {{-- DASHBOARD HEADER --}}
        <section class="rounded-3xl bg-gradient-to-r
                        from-indigo-800 via-purple-800 to-violet-700
                        text-white p-7 shadow-lg">

            <div class="flex flex-col md:flex-row
                        md:items-center md:justify-between gap-5">

                <div>
                    <p class="text-indigo-200 text-xs font-semibold
                              uppercase tracking-widest">
                        Human Resource Management
                    </p>

                    <h1 class="text-2xl md:text-3xl
                               font-bold mt-2">
                        Department Off-Day Overview
                    </h1>

                    <p class="text-indigo-100 mt-3 text-sm">
                        Track accrued days, recorded usage
                        and employee balances in
                        {{ $department->name }}.
                    </p>
                </div>

                <div class="bg-white/15 rounded-2xl
                            px-5 py-4">

                    <p class="text-indigo-100 text-xs">
                        As of
                    </p>

                    <p class="text-white font-bold">
                        {{ \Carbon\Carbon::parse($asOf)->format('d M Y') }}
                    </p>

                </div>

            </div>

        </section>

        {{-- POLICY STATUS --}}
        @if($department->offDaySetting?->is_enabled)

            <div class="rounded-xl bg-green-50 border
                        border-green-200 p-4 text-sm
                        text-green-900">

                <strong>Accrual policy enabled.</strong>

                Monthly entitlement:
                {{ number_format((float) $department->offDaySetting->monthly_accrual, 2) }}
                days.

                Annual carryover cap:
                {{ number_format((float) $department->offDaySetting->carryover_cap, 2) }}
                days.

            </div>

        @else

            <div role="alert"
                 class="rounded-xl bg-amber-50 border
                        border-amber-200 p-4 text-sm
                        text-amber-900">

                <strong>Accrual policy disabled.</strong>

                Previously recorded ledger balances
                remain visible, but new accrual
                synchronization is disabled.

            </div>

        @endif

        {{-- IMPORTANT ACCOUNTING NOTICE --}}
        <div class="rounded-xl border border-blue-200
                    bg-blue-50 px-5 py-4 text-sm text-blue-900">

            <strong>Ledger-based reporting:</strong>

            These figures include posted accruals, approved
            usage deductions, and carryover adjustments
            effective on or before the displayed date.

            Historical approvals made before ledger activation
            are not automatically included. Balances should
            not be treated as reconciled spendable leave
            until opening balances have been verified.

        </div>

        {{-- KPI CARDS --}}
        <div class="grid grid-cols-1 sm:grid-cols-2
                    xl:grid-cols-3 gap-5">

            {{-- TOTAL EMPLOYEES --}}
            <div class="bg-white rounded-2xl border
                        border-gray-100 shadow-sm p-6">

                <p class="text-sm font-medium text-gray-500">
                    Total Employees
                </p>

                <h3 class="text-3xl font-bold text-gray-900 mt-3">
                    {{ number_format($totalStaff) }}
                </h3>

                <p class="text-xs text-gray-500 mt-2">
                    Assigned staff members
                </p>

            </div>

            {{-- WITH BALANCE --}}
            <div class="bg-white rounded-2xl border
                        border-gray-100 shadow-sm p-6">

                <p class="text-sm font-medium text-gray-500">
                    Employees With Balance
                </p>

                <h3 class="text-3xl font-bold text-green-700 mt-3">
                    {{ number_format($staffWithBalance) }}
                </h3>

                <p class="text-xs text-gray-500 mt-2">
                    Positive recorded balance
                </p>

            </div>

            {{-- UTILIZED --}}
            <div class="bg-white rounded-2xl border
                        border-gray-100 shadow-sm p-6">

                <p class="text-sm font-medium text-gray-500">
                    Employees Who Used Off Days
                </p>

                <h3 class="text-3xl font-bold text-blue-700 mt-3">
                    {{ number_format($staffWhoUsed) }}
                </h3>

                <p class="text-xs text-gray-500 mt-2">
                    At least one ledger deduction
                </p>

            </div>

            {{-- NOT UTILIZED --}}
            <div class="bg-white rounded-2xl border
                        border-gray-100 shadow-sm p-6">

                <p class="text-sm font-medium text-gray-500">
                    Employees Not Utilized
                </p>

                <h3 class="text-3xl font-bold text-orange-600 mt-3">
                    {{ number_format($staffNotUtilized) }}
                </h3>

                <p class="text-xs text-gray-500 mt-2">
                    No recorded approved usage
                </p>

            </div>

            {{-- EARNED --}}
            <div class="bg-white rounded-2xl border
                        border-gray-100 shadow-sm p-6">

                <p class="text-sm font-medium text-gray-500">
                    Total Days Earned
                </p>

                <h3 class="text-3xl font-bold text-purple-700 mt-3">
                    {{ number_format($totalEarnedHundredths / 100, 2) }}
                </h3>

                <p class="text-xs text-gray-500 mt-2">
                    Sum of employee accruals
                </p>

            </div>

            {{-- REMAINING --}}
            <div class="bg-white rounded-2xl border
                        border-gray-100 shadow-sm p-6">

                <p class="text-sm font-medium text-gray-500">
                    Total Recorded Balance
                </p>

                <h3 class="text-3xl font-bold text-indigo-700 mt-3">
                    {{ number_format($totalRemainingHundredths / 100, 2) }}
                </h3>

                <p class="text-xs text-gray-500 mt-2">
                    After recorded deductions and adjustments
                </p>

            </div>

        </div>

        {{-- ACCOUNTING SUMMARY --}}
        <section class="bg-white rounded-2xl border
                        border-gray-100 shadow-sm p-6">

            <h3 class="text-lg font-bold text-gray-900">
                Off-Day Accounting Summary
            </h3>

            <div class="grid grid-cols-1 sm:grid-cols-3
                        gap-4 mt-5">

                <div class="rounded-xl bg-purple-50 p-5">
                    <p class="text-sm text-purple-700">
                        Accrued
                    </p>

                    <p class="text-2xl font-bold
                              text-purple-900 mt-2">
                        {{ number_format($totalEarnedHundredths / 100, 2) }}
                    </p>
                </div>

                <div class="rounded-xl bg-red-50 p-5">
                    <p class="text-sm text-red-700">
                        Approved Days Used
                    </p>

                    <p class="text-2xl font-bold
                              text-red-900 mt-2">
                        {{ number_format($totalUsedHundredths / 100, 2) }}
                    </p>
                </div>

                <div class="rounded-xl bg-amber-50 p-5">
                    <p class="text-sm text-amber-700">
                        Carryover Expired
                    </p>

                    <p class="text-2xl font-bold
                              text-amber-900 mt-2">
                        {{ number_format($totalExpiredHundredths / 100, 2) }}
                    </p>
                </div>

            </div>

            <p class="text-xs text-gray-500 mt-5">
                The recorded balance also includes any other
                ledger adjustments. It is not necessarily equal
                to accrued minus used minus expired if additional
                transaction types exist.
            </p>

        </section>

        {{-- EMPLOYEE DIRECTORY --}}
        <section class="bg-white rounded-2xl border
                        border-gray-100 shadow-sm overflow-hidden">

            <div class="px-6 py-5 border-b border-gray-100">

                <h3 class="text-xl font-bold text-gray-900">
                    Employee Off-Day Balances
                </h3>

                <p class="text-sm text-gray-500 mt-1">
                    Search staff and filter by recorded
                    off-day utilization.
                </p>

                <form method="GET"
                      action="{{ route('admin.departments.offday-analytics', $department->id) }}"
                      class="grid grid-cols-1 md:grid-cols-3
                             gap-3 mt-5">

                    <input type="search"
                           name="search"
                           value="{{ $search }}"
                           placeholder="Search name, email or unit"
                           aria-label="Search employees"
                           class="rounded-xl border-gray-300
                                  focus:border-purple-500
                                  focus:ring-purple-500
                                  text-sm">

                    <select name="filter"
                            aria-label="Filter off-day balances"
                            class="rounded-xl border-gray-300
                                   focus:border-purple-500
                                   focus:ring-purple-500 text-sm">

                        <option value="all"
                            @selected($filter === 'all')>
                            All Employees
                        </option>

                        <option value="used"
                            @selected($filter === 'used')>
                            Used Off Days
                        </option>

                        <option value="unused"
                            @selected($filter === 'unused')>
                            Not Utilized
                        </option>

                        <option value="available"
                            @selected($filter === 'available')>
                            Positive Balance
                        </option>

                        <option value="no_balance"
                            @selected($filter === 'no_balance')>
                            Zero / Negative Balance
                        </option>

                    </select>

                    <div class="flex gap-2">

                        <button type="submit"
                                class="flex-1 px-5 py-2.5 rounded-xl
                                       bg-purple-700 text-white
                                       font-semibold text-sm
                                       hover:bg-purple-800 transition">
                            Filter
                        </button>

                        <a href="{{ route('admin.departments.offday-analytics', $department->id) }}"
                           class="px-4 py-2.5 rounded-xl bg-gray-100
                                  text-gray-700 font-semibold text-sm
                                  hover:bg-gray-200 transition">
                            Reset
                        </a>

                    </div>

                </form>

            </div>

            <div class="overflow-x-auto">

                <table class="min-w-full text-left text-sm">

                    <thead class="bg-gray-50 text-gray-600
                                  border-b border-gray-100">

                        <tr>
                            <th class="px-5 py-4 font-semibold">
                                Employee
                            </th>
                            <th class="px-5 py-4 font-semibold">
                                Unit
                            </th>
                            <th class="px-5 py-4 font-semibold">
                                Earned
                            </th>
                            <th class="px-5 py-4 font-semibold">
                                Used
                            </th>
                            <th class="px-5 py-4 font-semibold">
                                Expired
                            </th>
                            <th class="px-5 py-4 font-semibold">
                                Balance
                            </th>
                            <th class="px-5 py-4 font-semibold">
                                Utilization
                            </th>
                            <th class="px-5 py-4 font-semibold">
                                Action
                            </th>
                        </tr>

                    </thead>

                    <tbody class="divide-y divide-gray-100">

                        @forelse($staff as $employee)

                            <tr class="hover:bg-gray-50 transition">

                                <td class="px-5 py-4">

                                    <p class="font-semibold text-gray-900">
                                        {{ $employee->name }}
                                    </p>

                                    <p class="text-xs text-gray-500 mt-1">
                                        {{ $employee->email }}
                                    </p>

                                </td>

                                <td class="px-5 py-4 text-gray-600">
                                    {{ $employee->unit?->name ?? 'N/A' }}
                                </td>

                                <td class="px-5 py-4 font-medium">
                                    {{ number_format($employee->earned_hundredths / 100, 2) }}
                                </td>

                                <td class="px-5 py-4 text-red-700 font-medium">
                                    {{ number_format($employee->used_hundredths / 100, 2) }}
                                </td>

                                <td class="px-5 py-4 text-amber-700">
                                    {{ number_format($employee->expired_hundredths / 100, 2) }}
                                </td>

                                <td class="px-5 py-4">

                                    @if($employee->balance_hundredths > 0)

                                        <span class="font-bold text-green-700">
                                            {{ number_format($employee->balance_hundredths / 100, 2) }}
                                        </span>

                                    @else

                                        <span class="font-bold text-gray-600">
                                            {{ number_format($employee->balance_hundredths / 100, 2) }}
                                        </span>

                                    @endif

                                </td>

                                <td class="px-5 py-4">

                                    @if($employee->used_requests_count > 0)

                                        <span class="inline-flex px-3 py-1
                                                     rounded-full bg-blue-100
                                                     text-blue-700 text-xs
                                                     font-semibold">
                                            Utilized
                                        </span>

                                    @else

                                        <span class="inline-flex px-3 py-1
                                                     rounded-full bg-orange-100
                                                     text-orange-700 text-xs
                                                     font-semibold">
                                            Not Utilized
                                        </span>

                                    @endif

                                </td>

                                <td class="px-5 py-4">

                                    <a href="{{ route('admin.staff.show', $employee->id) }}"
                                       class="inline-flex items-center
                                              px-3 py-2 rounded-lg
                                              bg-indigo-600 text-white
                                              text-xs font-semibold
                                              hover:bg-indigo-700 transition">

                                        View Profile

                                    </a>

                                </td>

                            </tr>

                        @empty

                            <tr>
                                <td colspan="8"
                                    class="px-6 py-14 text-center">

                                    <p class="font-semibold text-gray-800">
                                        No matching employees
                                    </p>

                                    <p class="text-gray-500 text-sm mt-2">
                                        Try another search or filter.
                                    </p>

                                </td>
                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

            <div class="px-6 py-5 border-t border-gray-100">

                <div class="flex flex-col sm:flex-row
                            sm:items-center sm:justify-between gap-4">

                    <p class="text-sm text-gray-500">
                        Showing {{ $staff->count() }}
                        of {{ $staff->total() }}
                        matching employees
                    </p>

                    <div>
                        {{ $staff->links() }}
                    </div>

                </div>

            </div>

        </section>

    </div>

</x-app-layout>
