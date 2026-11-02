<x-app-layout>

    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div>
                <p class="text-sm text-gray-500">HRMS / Administration</p>
                <h2 class="text-2xl font-bold text-gray-800">
                    Off Day Management
                </h2>
                <p class="text-sm text-gray-500 mt-1">
                    Manage staff off-day requests and generate reports.
                </p>
            </div>

            <span class="inline-flex items-center rounded-full bg-blue-50
                         px-4 py-2 text-sm font-medium text-blue-700">
                <span class="h-2 w-2 rounded-full bg-blue-500 mr-2"></span>
                Off Day Reports
            </span>
        </div>
    </x-slot>

    <div class="py-8 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- SUCCESS MESSAGE --}}
            @if(session('success'))
                <div role="status"
                     class="mb-5 rounded-xl border border-green-200
                            bg-green-50 p-4 text-sm text-green-800">
                    {{ session('success') }}
                </div>
            @endif

            {{-- ERROR MESSAGE --}}
            @if(session('error'))
                <div role="alert"
                     class="mb-5 rounded-xl border border-red-200
                            bg-red-50 p-4 text-sm text-red-800">
                    {{ session('error') }}
                </div>
            @endif

            {{-- VALIDATION ERRORS --}}
            @if($errors->any())
                <div role="alert"
                     class="mb-5 rounded-xl border border-red-200
                            bg-red-50 p-4 text-sm text-red-800">
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

            {{-- LEDGER ACTIVATION NOTICE --}}
            @if(!config('offdays.go_live_at'))
                <div role="alert"
                     class="mb-6 rounded-xl border border-amber-200
                            bg-amber-50 p-4 text-sm text-amber-900">
                    Ledger-based approvals are not yet activated.
                    Existing requests remain visible, but approval
                    requires a configured go-live timestamp.
                </div>
            @endif


            {{-- ================================================= --}}
            {{-- REPORTING FILTERS --}}
            {{-- ================================================= --}}

            <div class="bg-white rounded-2xl shadow-sm border border-gray-200
                        p-5 md:p-6 mb-7">

                <div class="flex flex-col md:flex-row md:items-center
                            md:justify-between gap-3 mb-5">

                    <div>
                        <h3 class="text-lg font-bold text-gray-800">
                            Reporting Filters
                        </h3>

                        <p class="text-sm text-gray-500 mt-1">
                            Filter requests by the date staff submitted
                            their applications.
                        </p>
                    </div>

                    <span class="inline-flex items-center rounded-lg
                                 bg-blue-50 px-3 py-2 text-xs font-semibold
                                 text-blue-700">
                        Application date: created_at
                    </span>

                </div>


                <form
                    id="offday-filter-form"
                    method="GET"
                    action="{{ route('admin.offdays.index') }}"
                >

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">

                        {{-- MONTHLY FILTER --}}
                        <div>
                            <label for="created_month"
                                   class="block text-sm font-semibold
                                          text-gray-700 mb-2">
                                Reporting Month
                            </label>

                            <input
                                type="month"
                                name="created_month"
                                id="created_month"
                                value="{{ request('created_month') }}"
                                class="w-full rounded-xl border border-gray-300
                                       px-4 py-3 text-sm shadow-sm
                                       focus:border-blue-500
                                       focus:ring-blue-500"
                            >

                            <p class="text-xs text-gray-500 mt-1">
                                Select a month, e.g. October 2026.
                            </p>
                        </div>


                        {{-- CUSTOM START DATE --}}
                        <div>
                            <label for="created_from"
                                   class="block text-sm font-semibold
                                          text-gray-700 mb-2">
                                Application Start Date
                            </label>

                            <input
                                type="date"
                                name="created_from"
                                id="created_from"
                                value="{{ request('created_from') }}"
                                class="w-full rounded-xl border border-gray-300
                                       px-4 py-3 text-sm shadow-sm
                                       focus:border-blue-500
                                       focus:ring-blue-500"
                            >
                        </div>


                        {{-- CUSTOM END DATE --}}
                        <div>
                            <label for="created_to"
                                   class="block text-sm font-semibold
                                          text-gray-700 mb-2">
                                Application End Date
                            </label>

                            <input
                                type="date"
                                name="created_to"
                                id="created_to"
                                value="{{ request('created_to') }}"
                                class="w-full rounded-xl border border-gray-300
                                       px-4 py-3 text-sm shadow-sm
                                       focus:border-blue-500
                                       focus:ring-blue-500"
                            >
                        </div>


                        {{-- STAFF NAME --}}
                        <div>
                            <label for="staff_name"
                                   class="block text-sm font-semibold
                                          text-gray-700 mb-2">
                                Staff Name
                            </label>

                            <input
                                type="text"
                                name="staff_name"
                                id="staff_name"
                                value="{{ request('staff_name') }}"
                                placeholder="Search by staff name..."
                                class="w-full rounded-xl border border-gray-300
                                       px-4 py-3 text-sm shadow-sm
                                       focus:border-blue-500
                                       focus:ring-blue-500"
                            >
                        </div>


                        {{-- UNIT FILTER --}}
                        <div>
                            <label for="unit_id"
                                   class="block text-sm font-semibold
                                          text-gray-700 mb-2">
                                Unit
                            </label>

                            <select
                                name="unit_id"
                                id="unit_id"
                                class="w-full rounded-xl border border-gray-300
                                       px-4 py-3 text-sm shadow-sm
                                       focus:border-blue-500
                                       focus:ring-blue-500"
                            >
                                <option value="">All Units</option>

                                @foreach($units as $unit)
                                    <option
                                        value="{{ $unit->id }}"
                                        {{ (string) request('unit_id') === (string) $unit->id ? 'selected' : '' }}
                                    >
                                        {{ $unit->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>


                        {{-- DEPARTMENT FILTER --}}
                        <div>
                            <label for="department_id"
                                   class="block text-sm font-semibold
                                          text-gray-700 mb-2">
                                Department
                            </label>

                            <select
                                name="department_id"
                                id="department_id"
                                class="w-full rounded-xl border border-gray-300
                                       px-4 py-3 text-sm shadow-sm
                                       focus:border-blue-500
                                       focus:ring-blue-500"
                            >
                                <option value="">All Departments</option>

                                @foreach($departments as $department)
                                    <option
                                        value="{{ $department->id }}"
                                        {{ (string) request('department_id') === (string) $department->id ? 'selected' : '' }}
                                    >
                                        {{ $department->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>


                        {{-- STATUS FILTER --}}
                        <div>
                            <label for="status"
                                   class="block text-sm font-semibold
                                          text-gray-700 mb-2">
                                Request Status
                            </label>

                            <select
                                name="status"
                                id="status"
                                class="w-full rounded-xl border border-gray-300
                                       px-4 py-3 text-sm shadow-sm
                                       focus:border-blue-500
                                       focus:ring-blue-500"
                            >
                                <option value="">All Statuses</option>

                                <option value="pending"
                                    {{ request('status') === 'pending' ? 'selected' : '' }}>
                                    Pending
                                </option>

                                <option value="approved"
                                    {{ request('status') === 'approved' ? 'selected' : '' }}>
                                    Approved
                                </option>

                                <option value="declined"
                                    {{ request('status') === 'declined' ? 'selected' : '' }}>
                                    Declined
                                </option>
                            </select>
                        </div>

                    </div>


                    {{-- FILTER ACTIONS --}}
                    <div class="flex flex-col sm:flex-row gap-3 mt-6
                                border-t border-gray-100 pt-5">

                        {{-- SEARCH --}}
                        <button
                            type="submit"
                            class="inline-flex items-center justify-center
                                   gap-2 rounded-xl bg-blue-600 px-6 py-3
                                   text-sm font-semibold text-white
                                   shadow-sm hover:bg-blue-700
                                   transition"
                        >
                            <svg xmlns="http://www.w3.org/2000/svg"
                                 class="h-5 w-5" fill="none"
                                 viewBox="0 0 24 24"
                                 stroke="currentColor">
                                <path stroke-linecap="round"
                                      stroke-linejoin="round"
                                      stroke-width="2"
                                      d="M21 21l-5.2-5.2m2.2-5.3a7.5 7.5 0 11-15 0 7.5 7.5 0 0115 0z"/>
                            </svg>

                            Search Requests
                        </button>


                        {{-- EXCEL DOWNLOAD --}}
                        <button
                            type="submit"
                            formaction="{{ route('admin.offdays.export') }}"
                            formmethod="GET"
                            class="inline-flex items-center justify-center
                                   gap-2 rounded-xl bg-green-600 px-6 py-3
                                   text-sm font-semibold text-white
                                   shadow-sm hover:bg-green-700
                                   transition"
                        >
                            <svg xmlns="http://www.w3.org/2000/svg"
                                 class="h-5 w-5" fill="none"
                                 viewBox="0 0 24 24"
                                 stroke="currentColor">
                                <path stroke-linecap="round"
                                      stroke-linejoin="round"
                                      stroke-width="2"
                                      d="M12 16V4m0 12l-4-4m4 4l4-4M4 20h16"/>
                            </svg>

                            Download Excel
                        </button>


                        {{-- CLEAR FILTERS --}}
                        <a
                            href="{{ route('admin.offdays.index') }}"
                            class="inline-flex items-center justify-center
                                   rounded-xl border border-gray-300
                                   bg-white px-6 py-3 text-sm
                                   font-semibold text-gray-700
                                   hover:bg-gray-50 transition"
                        >
                            Clear Filters
                        </a>

                    </div>

                </form>

            </div>


            {{-- ================================================= --}}
            {{-- KPI SUMMARY CARDS --}}
            {{-- ================================================= --}}

            <div class="mb-4 flex flex-col sm:flex-row
                        sm:items-center sm:justify-between gap-2">

                <div>
                    <h3 class="text-lg font-bold text-gray-800">
                        Off Day Overview
                    </h3>

                    <p class="text-sm text-gray-500">
                        Summary of requests matching your selected filters.
                    </p>
                </div>

            </div>


            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-8">

                {{-- TOTAL --}}
                <div class="bg-white rounded-2xl border border-gray-200
                            shadow-sm p-5">

                    <div class="flex items-center justify-between">

                        <div>
                            <p class="text-sm font-medium text-gray-500">
                                Total Requests
                            </p>

                            <h3 class="text-3xl font-bold text-gray-800 mt-2">
                                {{ number_format($summary['total'] ?? 0) }}
                            </h3>

                            <p class="text-xs text-gray-500 mt-2">
                                All matching applications
                            </p>
                        </div>

                        <div class="h-12 w-12 rounded-xl bg-blue-50
                                    flex items-center justify-center
                                    text-blue-600">
                            <svg class="h-6 w-6" fill="none"
                                 stroke="currentColor"
                                 viewBox="0 0 24 24">
                                <path stroke-linecap="round"
                                      stroke-linejoin="round"
                                      stroke-width="2"
                                      d="M9 5h6m-8 4h10M7 13h10m-10 4h6M5 3h14a1 1 0 011 1v16H4V4a1 1 0 011-1z"/>
                            </svg>
                        </div>

                    </div>

                </div>


                {{-- APPROVED --}}
                <div class="bg-white rounded-2xl border border-gray-200
                            shadow-sm p-5">

                    <div class="flex items-center justify-between">

                        <div>
                            <p class="text-sm font-medium text-gray-500">
                                Approved Requests
                            </p>

                            <h3 class="text-3xl font-bold text-green-600 mt-2">
                                {{ number_format($summary['approved'] ?? 0) }}
                            </h3>

                            <p class="text-xs text-gray-500 mt-2">
                                Successfully approved
                            </p>
                        </div>

                        <div class="h-12 w-12 rounded-xl bg-green-50
                                    flex items-center justify-center
                                    text-green-600">
                            <svg class="h-6 w-6" fill="none"
                                 stroke="currentColor"
                                 viewBox="0 0 24 24">
                                <path stroke-linecap="round"
                                      stroke-linejoin="round"
                                      stroke-width="2"
                                      d="M5 13l4 4L19 7"/>
                            </svg>
                        </div>

                    </div>

                </div>


                {{-- PENDING --}}
                <div class="bg-white rounded-2xl border border-gray-200
                            shadow-sm p-5">

                    <div class="flex items-center justify-between">

                        <div>
                            <p class="text-sm font-medium text-gray-500">
                                Pending Requests
                            </p>

                            <h3 class="text-3xl font-bold text-amber-500 mt-2">
                                {{ number_format($summary['pending'] ?? 0) }}
                            </h3>

                            <p class="text-xs text-gray-500 mt-2">
                                Awaiting administrator action
                            </p>
                        </div>

                        <div class="h-12 w-12 rounded-xl bg-amber-50
                                    flex items-center justify-center
                                    text-amber-600">
                            <svg class="h-6 w-6" fill="none"
                                 stroke="currentColor"
                                 viewBox="0 0 24 24">
                                <path stroke-linecap="round"
                                      stroke-linejoin="round"
                                      stroke-width="2"
                                      d="M12 8v4l3 2m6-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>

                    </div>

                </div>


                {{-- DECLINED --}}
                <div class="bg-white rounded-2xl border border-gray-200
                            shadow-sm p-5">

                    <div class="flex items-center justify-between">

                        <div>
                            <p class="text-sm font-medium text-gray-500">
                                Declined Requests
                            </p>

                            <h3 class="text-3xl font-bold text-red-600 mt-2">
                                {{ number_format($summary['declined'] ?? 0) }}
                            </h3>

                            <p class="text-xs text-gray-500 mt-2">
                                Requests not approved
                            </p>
                        </div>

                        <div class="h-12 w-12 rounded-xl bg-red-50
                                    flex items-center justify-center
                                    text-red-600">
                            <svg class="h-6 w-6" fill="none"
                                 stroke="currentColor"
                                 viewBox="0 0 24 24">
                                <path stroke-linecap="round"
                                      stroke-linejoin="round"
                                      stroke-width="2"
                                      d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </div>

                    </div>

                </div>

            </div>


            {{-- ================================================= --}}
            {{-- STAFF OFF DAY REQUEST CARDS --}}
            {{-- ================================================= --}}

            <div class="flex flex-col sm:flex-row sm:items-center
                        sm:justify-between gap-3 mb-5">

                <div>
                    <h3 class="text-lg font-bold text-gray-800">
                        Staff Off Day Requests
                    </h3>

                    <p class="text-sm text-gray-500 mt-1">
                        Review applications and manage approval decisions.
                    </p>
                </div>

                <span class="rounded-lg bg-gray-100 px-3 py-2
                             text-sm font-medium text-gray-600">
                    {{ $offDayRequests->count() }} Requests Found
                </span>

            </div>


            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

                @forelse($offDayRequests as $offDayRequest)

                    @php

                        $startDate = \Carbon\CarbonImmutable::parse(
                            $offDayRequest->start_date
                        );

                        $endDate = \Carbon\CarbonImmutable::parse(
                            $offDayRequest->end_date
                        );

                        // End date is exclusive, matching approval logic.
                        $calendarDays = $endDate->greaterThan($startDate)
                            ? (int) $startDate->diffInDays($endDate)
                            : 0;

                        $statusClasses = match($offDayRequest->status) {
                            'approved' => 'bg-green-100 text-green-700',
                            'pending' => 'bg-amber-100 text-amber-700',
                            'declined' => 'bg-red-100 text-red-700',
                            default => 'bg-gray-100 text-gray-700',
                        };

                    @endphp


                    <div class="bg-white rounded-2xl border border-gray-200
                                shadow-sm hover:shadow-md transition
                                flex flex-col">

                        <div class="p-5 flex-1">

                            {{-- STAFF NAME AND STATUS --}}
                            <div class="flex items-start justify-between gap-3 mb-4">

                                <div>
                                    <h4 class="text-lg font-bold text-gray-800">
                                        {{ optional($offDayRequest->user)->name ?? 'Unknown Staff' }}
                                    </h4>

                                    <p class="text-xs text-gray-500 mt-1">
                                        Staff ID:
                                        {{ optional($offDayRequest->user)->employment_number ?? 'N/A' }}
                                    </p>
                                </div>

                                <span class="inline-flex items-center rounded-full
                                             px-3 py-1 text-xs font-bold
                                             {{ $statusClasses }}">
                                    {{ ucfirst($offDayRequest->status) }}
                                </span>

                            </div>


                            {{-- UNIT AND DEPARTMENT --}}
                            <div class="space-y-2 mb-4">

                                <div class="flex justify-between text-sm gap-3">
                                    <span class="text-gray-500">Unit</span>

                                    <span class="font-medium text-gray-800 text-right">
                                        {{ optional(optional($offDayRequest->user)->unit)->name ?? 'N/A' }}
                                    </span>
                                </div>

                                <div class="flex justify-between text-sm gap-3">
                                    <span class="text-gray-500">Department</span>

                                    <span class="font-medium text-gray-800 text-right">
                                        {{ optional(optional($offDayRequest->user)->department)->name ?? 'N/A' }}
                                    </span>
                                </div>

                            </div>


                            <div class="border-t border-gray-100 my-4"></div>


                            {{-- APPLICATION DATE --}}
                            <div class="mb-4 rounded-xl bg-blue-50 p-3">

                                <p class="text-xs font-semibold uppercase
                                          tracking-wide text-blue-700">
                                    Application Submitted
                                </p>

                                <p class="text-sm font-bold text-blue-900 mt-1">
                                    @if($offDayRequest->created_at)
                                        {{ $offDayRequest->created_at->format('d M Y, h:i A') }}
                                    @else
                                        N/A
                                    @endif
                                </p>

                            </div>


                            {{-- REQUESTED OFF-DAY PERIOD --}}
                            <div class="grid grid-cols-2 gap-3 mb-4">

                                <div>
                                    <p class="text-xs text-gray-500">
                                        Off Day Starts
                                    </p>

                                    <p class="text-sm font-semibold text-gray-800 mt-1">
                                        {{ $startDate->format('d M Y') }}
                                    </p>
                                </div>

                                <div>
                                    <p class="text-xs text-gray-500">
                                        Off Day Ends
                                    </p>

                                    <p class="text-sm font-semibold text-gray-800 mt-1">
                                        {{ $endDate->format('d M Y') }}
                                    </p>
                                </div>

                            </div>


                            {{-- DURATION --}}
                            <div class="mb-4">

                                <span class="inline-flex items-center rounded-lg
                                             bg-purple-50 px-3 py-2
                                             text-sm font-semibold text-purple-700">

                                    Requested Duration:
                                    {{ $calendarDays }}
                                    {{ $calendarDays === 1 ? 'calendar day' : 'calendar days' }}

                                </span>

                            </div>


                            {{-- REASON --}}
                            <div class="mb-4">

                                <p class="text-xs font-semibold text-gray-500 mb-1">
                                    Reason for Request
                                </p>

                                <p class="text-sm text-gray-800 break-words">
                                    {{ $offDayRequest->reason ?? 'No reason provided.' }}
                                </p>

                            </div>


                            {{-- ADMIN COMMENT --}}
                            @if($offDayRequest->admin_comment)

                                <div class="rounded-lg bg-gray-50 p-3 mb-2">

                                    <p class="text-xs font-semibold text-gray-500 mb-1">
                                        Administrator Comment
                                    </p>

                                    <p class="text-sm text-gray-700 break-words">
                                        {{ $offDayRequest->admin_comment }}
                                    </p>

                                </div>

                            @endif

                        </div>


                        {{-- APPROVAL ACTIONS --}}
                        @if($offDayRequest->status === 'pending')

                            <div class="border-t border-gray-100
                                        p-5 pt-4 flex gap-3">

                                <form
                                    action="{{ route('admin.offdays.approve', $offDayRequest->id) }}"
                                    method="POST"
                                    class="flex-1"
                                    onsubmit="return confirm('Are you sure you want to approve this off-day request?');"
                                >
                                    @csrf
                                    @method('PUT')

                                    <button
                                        type="submit"
                                        class="w-full rounded-xl bg-green-600
                                               px-4 py-2.5 text-sm font-semibold
                                               text-white hover:bg-green-700
                                               transition"
                                    >
                                        Approve
                                    </button>

                                </form>


                                <form
                                    action="{{ route('admin.offdays.decline', $offDayRequest->id) }}"
                                    method="POST"
                                    class="flex-1"
                                    onsubmit="return confirm('Are you sure you want to decline this off-day request?');"
                                >
                                    @csrf
                                    @method('PUT')

                                    <button
                                        type="submit"
                                        class="w-full rounded-xl bg-red-600
                                               px-4 py-2.5 text-sm font-semibold
                                               text-white hover:bg-red-700
                                               transition"
                                    >
                                        Decline
                                    </button>

                                </form>

                            </div>

                        @else

                            <div class="border-t border-gray-100 px-5 py-4">

                                <p class="text-xs text-gray-500">
                                    This request has already been
                                    {{ strtolower($offDayRequest->status) }}.
                                </p>

                            </div>

                        @endif

                    </div>


                @empty

                    <div class="col-span-full rounded-2xl border-2
                                border-dashed border-gray-300
                                bg-white p-12 text-center">

                        <div class="mx-auto h-14 w-14 rounded-full
                                    bg-gray-100 flex items-center
                                    justify-center mb-4">

                            <svg class="h-7 w-7 text-gray-400"
                                 fill="none" stroke="currentColor"
                                 viewBox="0 0 24 24">
                                <path stroke-linecap="round"
                                      stroke-linejoin="round"
                                      stroke-width="2"
                                      d="M9 5h6m-8 4h10M7 13h10m-10 4h6M5 3h14a1 1 0 011 1v16H4V4a1 1 0 011-1z"/>
                            </svg>

                        </div>

                        <h4 class="text-lg font-bold text-gray-800">
                            No Off Day Requests Found
                        </h4>

                        <p class="text-sm text-gray-500 mt-2">
                            No staff applications match the selected filters.
                            Try adjusting your reporting period or filters.
                        </p>

                        <a
                            href="{{ route('admin.offdays.index') }}"
                            class="inline-flex mt-5 rounded-xl bg-blue-600
                                   px-5 py-2.5 text-sm font-semibold
                                   text-white hover:bg-blue-700 transition"
                        >
                            View All Requests
                        </a>

                    </div>

                @endforelse

            </div>


            {{-- FOOTER INFORMATION --}}
            <div class="mt-8 rounded-xl border border-gray-200
                        bg-white p-4 text-center">

                <p class="text-xs text-gray-500">
                    Off Day Management & Reporting
                    &bull;
                    {{ config('app.name') }}
                    &bull;
                    {{ date('Y') }}
                </p>

            </div>

        </div>
    </div>


    {{-- ================================================= --}}
    {{-- FILTER BEHAVIOUR --}}
    {{-- ================================================= --}}

    <script>
        document.addEventListener('DOMContentLoaded', function () {

            const monthInput = document.getElementById('created_month');
            const fromInput = document.getElementById('created_from');
            const toInput = document.getElementById('created_to');

            // Selecting a month clears the custom date range.
            monthInput.addEventListener('change', function () {

                if (this.value) {
                    fromInput.value = '';
                    toInput.value = '';
                }

            });

            // Selecting either custom date clears the month.
            [fromInput, toInput].forEach(function (input) {

                input.addEventListener('change', function () {

                    if (this.value) {
                        monthInput.value = '';
                    }

                });

            });

        });
    </script>

</x-app-layout>
