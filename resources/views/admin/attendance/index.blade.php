<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800">Attendance Report</h2>
    </x-slot>

    <div class="max-w-7xl mx-auto">

        <!-- Filter Form (UNCHANGED) -->
        <form method="GET" action="{{ route('admin.attendance') }}" 
              class="bg-white rounded-xl shadow-sm p-5 mb-6 grid grid-cols-1 md:grid-cols-4 gap-4">
            
            <div>
                <label class="block text-sm text-gray-800">Start Date</label>
                <input type="date" name="start_date" value="{{ $startDate ?? '' }}" 
                       class="w-full border-gray-300 rounded-lg px-3 py-2 focus:ring focus:ring-blue-200">
            </div>

            <div>
                <label class="block text-sm text-gray-800">End Date</label>
                <input type="date" name="end_date" value="{{ $endDate ?? '' }}" 
                       class="w-full border-gray-300 rounded-lg px-3 py-2 focus:ring focus:ring-blue-200">
            </div>

            <div>
                <label class="block text-sm text-gray-800">Unit</label>
                <select name="unit_id" class="w-full border-gray-300 rounded-lg px-3 py-2">
                    <option value="">All Units</option>
                    @foreach($units as $unit)
                        <option value="{{ $unit->id }}" @if($unitId == $unit->id) selected @endif>
                            {{ $unit->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm text-gray-800">Department</label>
                <select name="department_id" class="w-full border-gray-300 rounded-lg px-3 py-2">
                    <option value="">All Departments</option>
                    @foreach($departments as $dept)
                        <option value="{{ $dept->id }}" @if($departmentId == $dept->id) selected @endif>
                            {{ $dept->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="md:col-span-4 flex justify-end gap-2 mt-2">
                <button type="submit" 
                        class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition">
                    Filter
                </button>

                <a href="{{ route('admin.attendance.export', request()->all()) }}" 
                   class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition">
                    Download
                </a>

                <a href="{{ route('admin.attendance.export-email', request()->all()) }}"
                   class="bg-yellow-500 text-white px-4 py-2 rounded-lg hover:bg-yellow-600 transition">
                    Email
                </a>
            </div>
        </form>

        <!-- Attendance Table -->
        <div class="bg-white rounded-xl shadow-sm overflow-hidden">
            <table class="min-w-full text-sm">

                <!-- Header -->
                <thead class="bg-gray-50 text-gray-500 uppercase text-xs tracking-wider">
                    <tr>
                        <th class="px-5 py-3 text-left">Name</th>
                        <th class="px-5 py-3 text-left">Phone</th>
                        <th class="px-5 py-3 text-left">Unit</th>
                        <th class="px-5 py-3 text-left">Department</th>
                        <th class="px-5 py-3 text-left">Date</th>
                        <th class="px-5 py-3 text-left">Clock In</th>
                        <th class="px-5 py-3 text-left">Clock Out</th>
                        <th class="px-5 py-3 text-left">Status</th>
                        <th class="px-5 py-3 text-left">Late Reason</th>
                    </tr>
                </thead>

                <!-- Body -->
                <tbody class="divide-y">

                    @forelse($attendances as $att)
                        <tr class="hover:bg-gray-50 transition">

                            <!-- Name -->
                            <td class="px-5 py-4">
                                <div class="font-medium text-gray-900">
                                    {{ $att->user->name }}
                                </div>
                                <div class="text-xs text-gray-400">
                                    ID: {{ $att->id }}
                                </div>
                            </td>

                            <!-- Phone -->
                            <td class="px-5 py-4 text-gray-800">
                                {{ $att->user->phone }}
                            </td>

                            <!-- Unit -->
                            <td class="px-5 py-4 text-gray-800">
                                {{ $att->user->unit->name ?? '—' }}
                            </td>

                            <!-- Department -->
                            <td class="px-5 py-4 text-gray-800">
                                {{ $att->user->department->name ?? '—' }}
                            </td>

                            <!-- Date -->
                            <td class="px-5 py-4 text-gray-800">
                                {{ \Carbon\Carbon::parse($att->date)->format('d M, Y') }}
                            </td>

                            <!-- Clock In -->
                            <td class="px-5 py-4 text-gray-800">
                                {{ $att->clock_in ? \Carbon\Carbon::parse($att->clock_in)->format('g:i A') : '—' }}
                            </td>

                            <!-- Clock Out -->
                            <td class="px-5 py-4 text-gray-800">
                                {{ $att->clock_out ? \Carbon\Carbon::parse($att->clock_out)->format('g:i A') : '—' }}
                            </td>

                            <!-- Status -->
                            <td class="px-5 py-4">
                                @if($att->status === 'late')
                                    <span class="px-3 py-1 text-xs font-medium bg-red-100 text-red-600 rounded-full">
                                        Late
                                    </span>
                                @elseif($att->status === 'on_time')
                                    <span class="px-3 py-1 text-xs font-medium bg-green-100 text-green-600 rounded-full">
                                        On Time
                                    </span>
                                @else
                                    <span class="px-3 py-1 text-xs bg-gray-100 text-gray-800 rounded-full">
                                        {{ ucfirst($att->status ?? '-') }}
                                    </span>
                                @endif
                            </td>

                            <!-- Late Reason -->
                            <td class="px-5 py-4 text-gray-800">
                                {{ $att->late_reason ?? '-' }}
                            </td>

                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center py-6 text-gray-400">
                                No attendance records found.
                            </td>
                        </tr>
                    @endforelse

                </tbody>
            </table>
        </div>

    </div>

    <!-- Footer -->
    <footer class="text-center text-gray-400 text-sm py-6 border-t mt-6">
        © {{ date('Y') }} {{ config('app.name') }} — Staff Management System
    </footer>

</x-app-layout>