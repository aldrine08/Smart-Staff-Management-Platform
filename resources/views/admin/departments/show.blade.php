
<x-app-layout>

    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center
                    sm:justify-between gap-3">

            <div>
                <h2 class="text-xl font-bold text-gray-900">
                    {{ $department->name }}
                </h2>

                <p class="text-sm text-gray-500 mt-1">
                    Department Overview &amp; Staff Directory
                </p>
            </div>

            

<div class="flex flex-wrap items-center gap-3">

    {{-- OFF-DAY ANALYTICS --}}
    <a href="{{ route('admin.departments.offday-analytics', $department->id) }}"
       class="inline-flex items-center justify-center gap-2
              px-4 py-2 rounded-xl bg-indigo-600
              text-white font-semibold text-sm
              hover:bg-indigo-700 transition shadow-sm">

        📊 Off-Day Analytics

    </a>

    {{-- OFF-DAY SETTINGS --}}
    <a href="{{ route('admin.departments.offday-settings.edit', $department->id) }}"
       class="inline-flex items-center justify-center gap-2
              px-4 py-2 rounded-xl bg-purple-700
              text-white font-semibold text-sm
              hover:bg-purple-800 transition shadow-sm">

        ⚙ Off-Day Settings

    </a>

    {{-- BACK TO DEPARTMENTS --}}
    <a href="{{ route('admin.departments.index') }}"
       class="inline-flex items-center justify-center
              px-4 py-2 rounded-xl bg-gray-100
              text-gray-700 font-medium text-sm
              hover:bg-gray-200 transition">

        ← Back to Departments

    </a>

</div>



        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto space-y-8">

        {{-- DEPARTMENT INFORMATION --}}
        <div class="bg-white rounded-2xl shadow-sm
                    border border-gray-100 p-6">

            <div class="flex flex-col md:flex-row
                        md:items-center md:justify-between gap-5">

                <div class="flex items-center gap-4">

                    <div class="w-16 h-16 bg-purple-100
                                text-purple-700 rounded-2xl
                                flex items-center justify-center
                                text-3xl shrink-0">
                        🏢
                    </div>

                    <div>
                        <h3 class="text-2xl font-bold text-gray-900
                                   break-words">
                            {{ $department->name }}
                        </h3>

                        <p class="text-sm text-gray-500 mt-1">
                            Department ID: {{ $department->id }}
                        </p>

                        <p class="text-sm text-gray-500">
                            Created:
                            {{ $department->created_at->format('d M Y') }}
                        </p>
                    </div>

                </div>

                <span class="inline-flex items-center self-start
                             px-4 py-2 bg-purple-50 text-purple-700
                             text-sm font-semibold rounded-full">
                    Department Overview
                </span>

            </div>

        </div>

        {{-- DEPARTMENT KPI CARDS --}}
        <div class="grid grid-cols-1 sm:grid-cols-2
                    xl:grid-cols-4 gap-5">

            {{-- TOTAL STAFF --}}
            <div class="bg-white rounded-2xl shadow-sm
                        border border-gray-100 p-6">

                <div class="flex justify-between items-center">
                    <span class="text-sm font-medium text-gray-500">
                        Total Employees
                    </span>

                    <span class="bg-blue-100 text-blue-700
                                 rounded-xl p-3">
                        👥
                    </span>
                </div>

                <h3 class="text-3xl font-bold text-gray-900 mt-4">
                    {{ $totalStaff }}
                </h3>

                <p class="text-xs text-gray-500 mt-2">
                    Assigned department employees
                </p>

            </div>

            {{-- ACTIVE STAFF --}}
            <div class="bg-white rounded-2xl shadow-sm
                        border border-gray-100 p-6">

                <div class="flex justify-between items-center">
                    <span class="text-sm font-medium text-gray-500">
                        Active Employees
                    </span>

                    <span class="bg-green-100 text-green-700
                                 rounded-xl p-3">
                        ✓
                    </span>
                </div>

                <h3 class="text-3xl font-bold text-green-700 mt-4">
                    {{ $activeStaff }}
                </h3>

                <p class="text-xs text-gray-500 mt-2">
                    Currently active staff
                </p>

            </div>

            {{-- INACTIVE STAFF --}}
            <div class="bg-white rounded-2xl shadow-sm
                        border border-gray-100 p-6">

                <div class="flex justify-between items-center">
                    <span class="text-sm font-medium text-gray-500">
                        Inactive Employees
                    </span>

                    <span class="bg-orange-100 text-orange-700
                                 rounded-xl p-3">
                        ◷
                    </span>
                </div>

                <h3 class="text-3xl font-bold text-orange-600 mt-4">
                    {{ $inactiveStaff }}
                </h3>

                <p class="text-xs text-gray-500 mt-2">
                    Currently inactive staff
                </p>

            </div>

            {{-- ACTIVE RATE --}}
            <div class="bg-white rounded-2xl shadow-sm
                        border border-gray-100 p-6">

                <div class="flex justify-between items-center">
                    <span class="text-sm font-medium text-gray-500">
                        Active Rate
                    </span>

                    <span class="bg-purple-100 text-purple-700
                                 rounded-xl p-3">
                        📊
                    </span>
                </div>

                <h3 class="text-3xl font-bold text-purple-700 mt-4">
                    {{ number_format($activePercentage, 1) }}%
                </h3>

                <div class="w-full bg-gray-100 rounded-full
                            h-2 mt-4 overflow-hidden">

                    <div class="bg-purple-600 h-2 rounded-full"
                         style="width: {{ $activePercentage }}%">
                    </div>

                </div>

            </div>

        </div>

        {{-- DEPARTMENT STAFF DIRECTORY --}}
        <section class="bg-white rounded-2xl shadow-sm
                        border border-gray-100 overflow-hidden">

            {{-- TABLE HEADING --}}
            <div class="p-6 border-b border-gray-100">

                <div class="flex flex-col lg:flex-row
                            lg:items-center lg:justify-between gap-5">

                    <div>
                        <h3 class="text-xl font-bold text-gray-900">
                            Department Employees
                        </h3>

                        <p class="text-sm text-gray-500 mt-1">
                            Browse staff assigned to
                            {{ $department->name }}.
                        </p>
                    </div>

                    <div class="flex flex-col sm:flex-row gap-3">

                        {{-- SEARCH --}}
                        <input
                            type="search"
                            id="staffSearch"
                            aria-label="Search department employees"
                            placeholder="Search employees..."
                            class="rounded-xl border-gray-300
                                   focus:border-purple-500
                                   focus:ring-purple-500 text-sm">

                        {{-- STATUS FILTER --}}
                        <select
                            id="statusFilter"
                            aria-label="Filter by employee status"
                            class="rounded-xl border-gray-300
                                   focus:border-purple-500
                                   focus:ring-purple-500 text-sm">

                            <option value="all">All Staff</option>
                            <option value="active">Active Staff</option>
                            <option value="inactive">Inactive Staff</option>

                        </select>

                    </div>

                </div>

            </div>

            {{-- STAFF TABLE --}}
            <div class="overflow-x-auto">

                <table class="w-full text-left">

                    <thead class="bg-gray-50 border-b border-gray-100">

                        <tr>
                            <th class="px-6 py-4 text-xs font-semibold
                                       text-gray-500 uppercase">
                                Employee
                            </th>

                            <th class="px-6 py-4 text-xs font-semibold
                                       text-gray-500 uppercase">
                                Employment No.
                            </th>

                            <th class="px-6 py-4 text-xs font-semibold
                                       text-gray-500 uppercase">
                                Unit
                            </th>

                            <th class="px-6 py-4 text-xs font-semibold
                                       text-gray-500 uppercase">
                                Status
                            </th>

                            <th class="px-6 py-4 text-xs font-semibold
                                       text-gray-500 uppercase">
                                Action
                            </th>
                        </tr>

                    </thead>

                    <tbody id="staffTableBody"
                           class="divide-y divide-gray-100">

                        @forelse($staff as $employee)

                            <tr
                                class="staff-row hover:bg-gray-50 transition"
                                data-search="{{ strtolower($employee->name . ' ' . ($employee->employment_number ?? '') . ' ' . $employee->email . ' ' . ($employee->unit?->name ?? '')) }}"
                                data-status="{{ $employee->is_active ? 'active' : 'inactive' }}">

                                {{-- EMPLOYEE --}}
                                <td class="px-6 py-4">

                                    <div class="flex items-center gap-3">

                                        <div class="w-10 h-10 rounded-full
                                                    bg-purple-100 text-purple-700
                                                    flex items-center justify-center
                                                    font-bold shrink-0">

                                            {{ strtoupper(substr($employee->name, 0, 1)) }}

                                        </div>

                                        <div>
                                            <p class="text-sm font-semibold
                                                      text-gray-900">
                                                {{ $employee->name }}
                                            </p>

                                            <p class="text-xs text-gray-500">
                                                {{ $employee->email }}
                                            </p>
                                        </div>

                                    </div>

                                </td>

                                {{-- EMPLOYMENT NUMBER --}}
                                <td class="px-6 py-4 text-sm text-gray-600">
                                    {{ $employee->employment_number ?? 'N/A' }}
                                </td>

                                {{-- UNIT --}}
                                <td class="px-6 py-4 text-sm text-gray-600">
                                    {{ $employee->unit?->name ?? 'Unassigned' }}
                                </td>

                                {{-- STATUS --}}
                                <td class="px-6 py-4">

                                    @if($employee->is_active)

                                        <span class="inline-flex items-center
                                                     px-3 py-1 rounded-full
                                                     text-xs font-semibold
                                                     bg-green-100 text-green-700">
                                            Active
                                        </span>

                                    @else

                                        <span class="inline-flex items-center
                                                     px-3 py-1 rounded-full
                                                     text-xs font-semibold
                                                     bg-red-100 text-red-700">
                                            Inactive
                                        </span>

                                    @endif

                                </td>

                                {{-- VIEW STAFF PROFILE --}}
                                <td class="px-6 py-4">

                                    <a href="{{ route('admin.staff.show', $employee->id) }}"
                                       class="inline-flex items-center
                                              px-4 py-2 bg-blue-600
                                              hover:bg-blue-700 text-white
                                              text-xs font-semibold
                                              rounded-lg transition">
                                        View Profile
                                    </a>

                                </td>

                            </tr>

                        @empty

                            <tr>
                                <td colspan="5"
                                    class="py-14 px-6 text-center">

                                    <div class="text-4xl mb-4">👥</div>

                                    <h4 class="text-lg font-semibold
                                               text-gray-900">
                                        No employees assigned
                                    </h4>

                                    <p class="text-sm text-gray-500 mt-2">
                                        This department has no staff members yet.
                                    </p>

                                </td>
                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

            {{-- FILTER EMPTY STATE --}}
            <div
                id="noMatchingStaff"
                class="hidden p-10 text-center border-t border-gray-100">

                <h4 class="font-semibold text-gray-900">
                    No matching employees
                </h4>

                <p class="text-sm text-gray-500 mt-2">
                    Try changing your search or status filter.
                </p>

            </div>

            {{-- DIRECTORY FOOTER --}}
            <div class="bg-gray-50 px-6 py-4
                        border-t border-gray-100">

                <p class="text-sm text-gray-600">
                    Showing
                    <span id="visibleStaffCount">{{ $totalStaff }}</span>
                    of {{ $totalStaff }} employees
                </p>

            </div>

        </section>

        {{-- BACK NAVIGATION --}}
        <div class="flex justify-start">

            <a href="{{ route('admin.departments.index') }}"
               class="inline-flex items-center gap-2
                      px-5 py-3 bg-gray-900 text-white
                      rounded-xl hover:bg-gray-800
                      transition text-sm font-medium">

                &larr; Back to All Departments

            </a>

        </div>

    </div>

    {{-- SEARCH AND FILTER SCRIPT --}}
    <script>

        document.addEventListener('DOMContentLoaded', function () {

            const searchInput = document.getElementById('staffSearch');
            const statusFilter = document.getElementById('statusFilter');
            const rows = document.querySelectorAll('.staff-row');

            const emptyMessage = document.getElementById('noMatchingStaff');
            const visibleCount = document.getElementById('visibleStaffCount');

            function filterStaff() {

                const searchTerm = searchInput.value.toLowerCase().trim();
                const selectedStatus = statusFilter.value;

                let count = 0;

                rows.forEach(function (row) {

                    const employeeData = row.dataset.search;
                    const employeeStatus = row.dataset.status;

                    const matchesSearch = employeeData.includes(searchTerm);

                    const matchesStatus =
                        selectedStatus === 'all' ||
                        employeeStatus === selectedStatus;

                    const shouldShow = matchesSearch && matchesStatus;

                    row.classList.toggle('hidden', !shouldShow);

                    if (shouldShow) {
                        count++;
                    }

                });

                visibleCount.textContent = count;

                emptyMessage.classList.toggle(
                    'hidden',
                    count > 0 || rows.length === 0
                );

            }

            searchInput.addEventListener('input', filterStaff);

            statusFilter.addEventListener('change', filterStaff);

        });

    </script>

</x-app-layout>
