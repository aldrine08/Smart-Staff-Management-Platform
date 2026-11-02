<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="text-xl font-semibold text-gray-800">
                Attendance Report
            </h2>
            <p class="text-sm text-gray-500 mt-1">
                Monitor staff attendance, working hours and punctuality.
            </p>
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">

        <!-- Page Introduction -->
        <div class="mb-6">
            <h3 class="text-lg font-semibold text-gray-800">
                Staff Attendance Records
            </h3>
            <p class="text-sm text-gray-500 mt-1">
                Filter, review and export attendance records for your staff.
            </p>
        </div>

        <!-- Filter Form -->
        <form method="GET"
              action="{{ route('admin.attendance') }}"
              class="bg-white rounded-xl border border-gray-100 shadow-sm p-5 mb-6">

            <div class="flex items-center justify-between mb-5">
                <div>
                    <h3 class="text-sm font-semibold text-gray-800">
                        Filter Attendance
                    </h3>
                    <p class="text-xs text-gray-500 mt-1">
                        Narrow down records using the available filters.
                    </p>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">

                <!-- Start Date -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">
                        Start Date
                    </label>
                    <input type="date"
                           name="start_date"
                           value="{{ $startDate ?? '' }}"
                           class="w-full border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:border-blue-500 focus:ring-blue-500">
                </div>

                <!-- End Date -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">
                        End Date
                    </label>
                    <input type="date"
                           name="end_date"
                           value="{{ $endDate ?? '' }}"
                           class="w-full border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:border-blue-500 focus:ring-blue-500">
                </div>

                <!-- Unit -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">
                        Unit
                    </label>
                    <select name="unit_id"
                            class="w-full border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:border-blue-500 focus:ring-blue-500">
                        <option value="">All Units</option>

                        @foreach($units as $unit)
                            <option value="{{ $unit->id }}"
                                {{ request('unit_id') == $unit->id ? 'selected' : '' }}>
                                {{ $unit->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Department -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">
                        Department
                    </label>
                    <select name="department_id"
                            class="w-full border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:border-blue-500 focus:ring-blue-500">
                        <option value="">All Departments</option>

                        @foreach($departments as $department)
                            <option value="{{ $department->id }}"
                                {{ request('department_id') == $department->id ? 'selected' : '' }}>
                                {{ $department->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

            </div>

            <!-- Filter Actions -->
            <div class="flex flex-wrap justify-end gap-2 mt-5 pt-4 border-t border-gray-100">

                <a href="{{ route('admin.attendance') }}"
                   class="inline-flex items-center justify-center px-4 py-2.5 text-sm font-medium text-gray-600 bg-gray-100 rounded-lg hover:bg-gray-200 transition">
                    Reset Filters
                </a>

                <button type="submit"
                        class="inline-flex items-center justify-center px-5 py-2.5 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 transition shadow-sm">
                    Filter Records
                </button>

                <a href="{{ route('admin.attendance.export', request()->all()) }}"
                   class="inline-flex items-center justify-center px-4 py-2.5 text-sm font-medium text-white bg-green-600 rounded-lg hover:bg-green-700 transition shadow-sm">
                    Download Excel
                </a>

                <a href="{{ route('admin.attendance.export-email', request()->all()) }}"
                   class="inline-flex items-center justify-center px-4 py-2.5 text-sm font-medium text-white bg-amber-500 rounded-lg hover:bg-amber-600 transition shadow-sm">
                    Email Report
                </a>

            </div>
        </form>

        <!-- Attendance Table -->
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">

            <!-- Table Heading -->
            <div class="px-5 py-4 border-b border-gray-100 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
                <div>
                    <h3 class="text-base font-semibold text-gray-800">
                        Attendance Overview
                    </h3>
                    <p class="text-xs text-gray-500 mt-1">
                        Detailed attendance records for your staff.
                    </p>
                </div>

                <div class="text-xs text-gray-500">
                    Total Records:
                    <span class="font-semibold text-gray-800">
                        {{ number_format($attendances->total()) }}
                    </span>
                </div>
            </div>

            <!-- Responsive Table -->
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">

                    <!-- Table Header -->
                    <thead class="bg-gray-50 text-gray-500 uppercase text-xs tracking-wider">
                        <tr>
                            <th class="px-5 py-4 text-left font-semibold whitespace-nowrap">Name</th>
                            <th class="px-5 py-4 text-left font-semibold whitespace-nowrap">Phone</th>
                            <th class="px-5 py-4 text-left font-semibold whitespace-nowrap">Unit</th>
                            <th class="px-5 py-4 text-left font-semibold whitespace-nowrap">Department</th>
                            <th class="px-5 py-4 text-left font-semibold whitespace-nowrap">Date</th>
                            <th class="px-5 py-4 text-left font-semibold whitespace-nowrap">Clock In</th>
                            <th class="px-5 py-4 text-left font-semibold whitespace-nowrap">Clock Out</th>
                            <th class="px-5 py-4 text-left font-semibold whitespace-nowrap">Status</th>
                            <th class="px-5 py-4 text-left font-semibold whitespace-nowrap">Late Reason</th>
                        </tr>
                    </thead>

                    <!-- Table Body -->
                    <tbody class="divide-y divide-gray-100">

                        @forelse($attendances as $att)
                            <tr class="hover:bg-blue-50/40 transition-colors duration-150">

                                <!-- Name -->
                                <td class="px-5 py-4 whitespace-nowrap">
                                    <div class="font-semibold text-gray-900">
                                        {{ $att->user->name }}
                                    </div>
                                    <div class="text-xs text-gray-400 mt-1">
                                        ID: {{ $att->id }}
                                    </div>
                                </td>

                                <!-- Phone -->
                                <td class="px-5 py-4 text-gray-600 whitespace-nowrap">
                                    {{ $att->user->phone }}
                                </td>

                                <!-- Unit -->
                                <td class="px-5 py-4 text-gray-600 whitespace-nowrap">
                                    {{ $att->user->unit->name ?? '—' }}
                                </td>

                                <!-- Department -->
                                <td class="px-5 py-4 text-gray-600 whitespace-nowrap">
                                    {{ $att->user->department->name ?? '—' }}
                                </td>

                                <!-- Date -->
                                <td class="px-5 py-4 text-gray-700 whitespace-nowrap">
                                    {{ \Carbon\Carbon::parse($att->date)->format('d M, Y') }}
                                </td>

                                <!-- Clock In -->
                                <td class="px-5 py-4 text-gray-700 whitespace-nowrap">
                                    {{ $att->clock_in ? \Carbon\Carbon::parse($att->clock_in)->format('g:i A') : '—' }}
                                </td>

                                <!-- Clock Out -->
                                <td class="px-5 py-4 text-gray-700 whitespace-nowrap">
                                    {{ $att->clock_out ? \Carbon\Carbon::parse($att->clock_out)->format('g:i A') : '—' }}
                                </td>

                                <!-- Status -->
                                <td class="px-5 py-4 whitespace-nowrap">
                                    @if($att->status === 'late')
                                        <span class="inline-flex items-center px-3 py-1 text-xs font-semibold bg-red-50 text-red-700 rounded-full">
                                            Late
                                        </span>
                                    @elseif($att->status === 'on_time')
                                        <span class="inline-flex items-center px-3 py-1 text-xs font-semibold bg-green-50 text-green-700 rounded-full">
                                            On Time
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-3 py-1 text-xs font-medium bg-gray-100 text-gray-700 rounded-full">
                                            {{ ucfirst($att->status ?? '-') }}
                                        </span>
                                    @endif
                                </td>

                                <!-- Late Reason -->
                                <td class="px-5 py-4 text-gray-600 min-w-[180px]">
                                    {{ $att->late_reason ?? '—' }}
                                </td>

                            </tr>

                        @empty
                            <tr>
                                <td colspan="9" class="text-center py-12">
                                    <div class="flex flex-col items-center">
                                        <span class="text-gray-400 text-sm font-medium">
                                            No attendance records found.
                                        </span>
                                        <span class="text-xs text-gray-400 mt-1">
                                            Try adjusting your filters.
                                        </span>
                                    </div>
                                </td>
                            </tr>
                        @endforelse

                    </tbody>
                </table>
            </div>

            <!-- Attendance Pagination Footer -->
            <div class="border-t border-gray-100 bg-gray-50/70 px-4 py-4 sm:px-6">

                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">

                    <!-- Records Summary -->
                    <div class="text-sm text-gray-600">
                        Showing
                        <span class="font-semibold text-gray-900">
                            {{ $attendances->firstItem() ?? 0 }}
                        </span>
                        to
                        <span class="font-semibold text-gray-900">
                            {{ $attendances->lastItem() ?? 0 }}
                        </span>
                        of
                        <span class="font-semibold text-gray-900">
                            {{ number_format($attendances->total()) }}
                        </span>
                        attendance records
                    </div>

                    <!-- Pagination Navigation -->
                    @if($attendances->hasPages())
                        <div class="flex justify-start sm:justify-end">
                            {{ $attendances->onEachSide(1)->links() }}
                        </div>
                    @endif

                </div>
            </div>

        </div>

    </div>

    <!-- Footer -->
    <footer class="text-center text-gray-400 text-sm py-6 border-t mt-8">
        © {{ date('Y') }} {{ config('app.name') }} — Staff Management System
    </footer>

</x-app-layout>
