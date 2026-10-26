
<x-app-layout>

    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center
                    sm:justify-between gap-4">

            <div>
                <h2 class="text-xl font-bold text-gray-900">
                    Off-Day Settings
                </h2>

                <p class="text-sm text-gray-500 mt-1">
                    {{ $department->name }} — Department Policy
                </p>
            </div>

            <a href="{{ route('admin.departments.show', $department->id) }}"
               class="inline-flex items-center justify-center
                      px-4 py-2 rounded-xl bg-gray-100
                      text-gray-700 text-sm font-medium
                      hover:bg-gray-200 transition">
                ← Back to Department
            </a>

        </div>
    </x-slot>

    <div class="max-w-5xl mx-auto py-8 px-4 sm:px-6 lg:px-8
                space-y-6">

        @if(session('success'))
            <div class="rounded-xl bg-green-50 border border-green-200
                        px-5 py-4 text-green-800 text-sm"
                 role="status">
                {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="rounded-xl bg-red-50 border border-red-200
                        px-5 py-4 text-red-800 text-sm"
                 role="alert">

                <p class="font-semibold mb-2">
                    Please correct the following:
                </p>

                <ul class="list-disc pl-5 space-y-1">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>

            </div>
        @endif

        {{-- INTRODUCTION --}}
        <section class="bg-gradient-to-r from-purple-800
                        to-indigo-700 rounded-3xl
                        p-7 text-white shadow-lg">

            <div class="flex flex-col sm:flex-row
                        sm:items-center gap-5">

                <div class="w-16 h-16 rounded-2xl
                            bg-white/20 flex items-center
                            justify-center text-3xl">
                    ⚙
                </div>

                <div>
                    <h3 class="text-2xl font-bold">
                        Department Off-Day Policy
                    </h3>

                    <p class="text-purple-100 mt-2 text-sm">
                        Configure monthly off-day accrual,
                        annual carryover limits, and eligibility
                        for employees in {{ $department->name }}.
                    </p>
                </div>

            </div>

        </section>

        {{-- CURRENT POLICY SUMMARY --}}
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">

            <div class="bg-white rounded-2xl border
                        border-gray-100 shadow-sm p-5">

                <p class="text-sm text-gray-500">
                    Current Status
                </p>

                @if($settings?->is_enabled)
                    <p class="mt-2 text-lg font-bold text-green-700">
                        Enabled
                    </p>
                @else
                    <p class="mt-2 text-lg font-bold text-gray-600">
                        Disabled
                    </p>
                @endif

            </div>

            <div class="bg-white rounded-2xl border
                        border-gray-100 shadow-sm p-5">

                <p class="text-sm text-gray-500">
                    Monthly Entitlement
                </p>

                <p class="mt-2 text-2xl font-bold text-purple-700">
                    {{ number_format((float) ($settings?->monthly_accrual ?? 1.75), 2) }}
                    <span class="text-sm font-normal text-gray-500">
                        days
                    </span>
                </p>

            </div>

            <div class="bg-white rounded-2xl border
                        border-gray-100 shadow-sm p-5">

                <p class="text-sm text-gray-500">
                    Carryover Cap
                </p>

                <p class="mt-2 text-2xl font-bold text-blue-700">
                    {{ number_format((float) ($settings?->carryover_cap ?? 10), 2) }}
                    <span class="text-sm font-normal text-gray-500">
                        days
                    </span>
                </p>

            </div>

        </div>

        {{-- SETTINGS FORM --}}
        <section class="bg-white rounded-3xl shadow-sm
                        border border-gray-100 overflow-hidden">

            <div class="px-6 py-5 border-b border-gray-100">
                <h3 class="text-lg font-bold text-gray-900">
                    Policy Configuration
                </h3>

                <p class="text-sm text-gray-500 mt-1">
                    These settings apply only to this department.
                </p>
            </div>

            <form method="POST"
                  action="{{ route('admin.departments.offday-settings.update', $department->id) }}"
                  class="p-6 space-y-7">

                @csrf
                @method('PUT')

                {{-- ENABLE / DISABLE --}}
                <div>
                    <label for="is_enabled"
                           class="block text-sm font-semibold
                                  text-gray-800 mb-2">
                        Off-Day Accrual
                    </label>

                    <select id="is_enabled"
                            name="is_enabled"
                            class="w-full rounded-xl border-gray-300
                                   focus:border-purple-500
                                   focus:ring-purple-500"
                            required>

                        <option value="0"
                            @selected(old('is_enabled', $settings?->is_enabled ? '1' : '0') == '0')>
                            Disabled
                        </option>

                        <option value="1"
                            @selected(old('is_enabled', $settings?->is_enabled ? '1' : '0') == '1')>
                            Enabled
                        </option>

                    </select>

                    <p class="mt-2 text-xs text-gray-500">
                        Enabling this policy saves the configuration.
                        Automatic accrual begins only after the
                        accrual engine is implemented.
                    </p>
                </div>

                {{-- MONTHLY ACCRUAL --}}
                <div>
                    <label for="monthly_accrual"
                           class="block text-sm font-semibold
                                  text-gray-800 mb-2">
                        Monthly Off-Day Entitlement
                    </label>

                    <div class="relative">
                        <input type="number"
                               id="monthly_accrual"
                               name="monthly_accrual"
                               min="0.01"
                               max="999999.99"
                               step="0.01"
                               value="{{ old('monthly_accrual', $settings?->monthly_accrual ?? '1.75') }}"
                               required
                               class="w-full rounded-xl border-gray-300
                                      pr-20 focus:border-purple-500
                                      focus:ring-purple-500">

                        <span class="absolute right-4 top-1/2
                                     -translate-y-1/2
                                     text-sm text-gray-500">
                            Days
                        </span>
                    </div>

                    <p class="mt-2 text-xs text-gray-500">
                        Default: 1.75 days per month per eligible employee.
                    </p>
                </div>

                {{-- CARRYOVER CAP --}}
                <div>
                    <label for="carryover_cap"
                           class="block text-sm font-semibold
                                  text-gray-800 mb-2">
                        Maximum Annual Carryover
                    </label>

                    <div class="relative">
                        <input type="number"
                               id="carryover_cap"
                               name="carryover_cap"
                               min="0"
                               max="999999.99"
                               step="0.01"
                               value="{{ old('carryover_cap', $settings?->carryover_cap ?? '10.00') }}"
                               required
                               class="w-full rounded-xl border-gray-300
                                      pr-20 focus:border-purple-500
                                      focus:ring-purple-500">

                        <span class="absolute right-4 top-1/2
                                     -translate-y-1/2
                                     text-sm text-gray-500">
                            Days
                        </span>
                    </div>

                    <p class="mt-2 text-xs text-gray-500">
                        Maximum unused days permitted to carry
                        into the following year. A value of zero
                        means no carryover.
                    </p>
                </div>


{{-- ADVANCE OFF-DAY POLICY --}}
<div class="rounded-2xl border border-purple-200 bg-purple-50 p-5 space-y-5">

    <div>
        <h4 class="font-bold text-purple-900">
            Advance Off-Day Approval
        </h4>

        <p class="text-sm text-purple-800 mt-2">
            Allow administrators to approve off days before
            employees have accumulated sufficient entitlement.

            Approved days are deducted immediately, and future
            monthly accruals reduce any negative balance.
        </p>
    </div>

    <div>
        <label for="allow_advance_off_days"
               class="block text-sm font-semibold text-gray-800 mb-2">
            Allow Advance Approvals
        </label>

        <select id="allow_advance_off_days"
                name="allow_advance_off_days"
                required
                class="w-full rounded-xl border-gray-300">

            <option value="0"
                @selected(old(
                    'allow_advance_off_days',
                    $settings?->allow_advance_off_days ? '1' : '0'
                ) == '0')>
                Disabled
            </option>

            <option value="1"
                @selected(old(
                    'allow_advance_off_days',
                    $settings?->allow_advance_off_days ? '1' : '0'
                ) == '1')>
                Enabled
            </option>

        </select>
    </div>

    <div>
        <label for="maximum_advance_days"
               class="block text-sm font-semibold text-gray-800 mb-2">
            Maximum Advance Balance (Days)
        </label>

        <input type="number"
               id="maximum_advance_days"
               name="maximum_advance_days"
               min="0"
               max="999999.99"
               step="0.01"
               value="{{ old(
                   'maximum_advance_days',
                   $settings?->maximum_advance_days ?? '0.00'
               ) }}"
               required
               class="w-full rounded-xl border-gray-300">

        <p class="text-xs text-gray-600 mt-2">
            Example: 3.00 allows an employee's recorded balance
            to fall as low as -3.00 days after approval.
        </p>
    </div>

</div>


                {{-- FIXED POLICY RULES --}}
                <div class="rounded-2xl bg-gray-50
                            border border-gray-200 p-5">

                    <h4 class="font-semibold text-gray-800 mb-4">
                        Confirmed Policy Rules
                    </h4>

                    <dl class="space-y-3 text-sm">

                        <div class="flex justify-between gap-4">
                            <dt class="text-gray-500">
                                Accrual begins
                            </dt>
                            <dd class="font-semibold text-gray-800 text-right">
                                Employee joining date
                            </dd>
                        </div>

                        <div class="flex justify-between gap-4">
                            <dt class="text-gray-500">
                                Day counting
                            </dt>
                            <dd class="font-semibold text-gray-800 text-right">
                                Calendar days
                            </dd>
                        </div>

                        <div class="flex justify-between gap-4">
                            <dt class="text-gray-500">
                                Historical approvals
                            </dt>
                            <dd class="font-semibold text-gray-800 text-right">
                                No automatic backfill
                            </dd>
                        </div>

                    </dl>

                </div>

                <div class="flex flex-col sm:flex-row
                            sm:justify-end gap-3 pt-4
                            border-t border-gray-100">

                    <a href="{{ route('admin.departments.show', $department->id) }}"
                       class="px-5 py-3 rounded-xl bg-gray-100
                              text-gray-700 font-semibold text-sm
                              text-center hover:bg-gray-200 transition">
                        Cancel
                    </a>

                    <button type="submit"
                            class="px-6 py-3 rounded-xl bg-purple-700
                                   text-white font-semibold text-sm
                                   hover:bg-purple-800 transition
                                   shadow-sm">
                        Save Off-Day Settings
                    </button>

                </div>

            </form>

        </section>

        <div class="rounded-2xl border border-blue-200
                    bg-blue-50 p-5 text-sm text-blue-900">

            <p class="font-semibold">
                Stage 1 — Configuration Only
            </p>

            <p class="mt-2">
                This page stores department policy settings.
                Employee accruals, balance deductions,
                annual carryover calculations, and analytics
                will be activated in later stages.
            </p>

        </div>

    </div>

</x-app-layout>
