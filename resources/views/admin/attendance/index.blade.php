<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800">Attendance Report</h2>
    </x-slot>

    <div class="max-w-7xl mx-auto">

        <!-- Filter Form -->
        <form method="GET" action="{{ route('admin.attendance') }}" class="bg-white rounded shadow p-4 mb-6 grid grid-cols-1 md:grid-cols-4 gap-4">
            
            <div>
                <label class="block text-gray-700">Start Date</label>
                <input type="date" name="start_date" value="{{ $startDate ?? '' }}" class="w-full border px-3 py-2 rounded">
            </div>

            <div>
                <label class="block text-gray-700">End Date</label>
                <input type="date" name="end_date" value="{{ $endDate ?? '' }}" class="w-full border px-3 py-2 rounded">
            </div>

            <div>
                <label class="block text-gray-700">Unit</label>
                <select name="unit_id" class="w-full border px-3 py-2 rounded">
                    <option value="">All Units</option>
                    @foreach($units as $unit)
                        <option value="{{ $unit->id }}" @if($unitId == $unit->id) selected @endif>{{ $unit->name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-gray-700">Department</label>
                <select name="department_id" class="w-full border px-3 py-2 rounded">
                    <option value="">All Departments</option>
                    @foreach($departments as $dept)
                        <option value="{{ $dept->id }}" @if($departmentId == $dept->id) selected @endif>{{ $dept->name }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Buttons Row -->
            <div class="md:col-span-4 text-right mt-2 flex justify-end space-x-2">
                <!-- Filter Button -->
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                    Filter
                </button>

                <!-- Download Attendance Button -->
                <a href="{{ route('admin.attendance.export', request()->all()) }}" 
                   class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
                    Download Attendance
                </a>

                <!-- Export to Email Button -->
                <a href="{{ route('admin.attendance.export-email', request()->all()) }}"
                   class="bg-yellow-600 text-white px-4 py-2 rounded hover:bg-yellow-700">
                    Export to Email
                </a>
            </div>
        </form>

        <!-- Attendance Table -->
        <div class="bg-white rounded shadow p-4 overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-4 py-2 text-left">Name</th>
                        <th class="px-4 py-2 text-left">Phone</th>
                        <th class="px-4 py-2 text-left">Email</th>
                        <th class="px-4 py-2 text-left">Unit</th>
                        <th class="px-4 py-2 text-left">Department</th>
                        <th class="px-4 py-2 text-left">Date</th>
                        <th class="px-4 py-2 text-left">Clock In</th>
                        <th class="px-4 py-2 text-left">Clock Out</th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    @forelse($attendances as $att)
                        <tr>
                            <td class="px-4 py-2">{{ $att->user->name }}</td>
                            <td class="px-4 py-2">{{ $att->user->phone }}</td>
                            <td class="px-4 py-2">{{ $att->user->email }}</td>
                            <td class="px-4 py-2">{{ $att->user->unit->name ?? '—' }}</td>
                            <td class="px-4 py-2">{{ $att->user->department->name ?? '—' }}</td>
                            <td class="px-4 py-2">{{ \Carbon\Carbon::parse($att->date)->format('d M Y') }}</td>
                            <td class="px-4 py-2">{{ $att->clock_in ? \Carbon\Carbon::parse($att->clock_in)->format('H:i') : '—' }}</td>
                            <td class="px-4 py-2">{{ $att->clock_out ? \Carbon\Carbon::parse($att->clock_out)->format('H:i') : '—' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-4 text-gray-500">No attendance records found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>

    <!-- ================= FOOTER ================= -->
<footer class="text-center text-gray-500 text-sm py-6 border-t border-gray-800">
    © {{ date('Y') }} {{ config('app.name') }} — Staff Management System
</footer>


</x-app-layout>
