<x-app-layout>
<x-slot name="header">
    <h2 class="text-xl font-semibold">My Attendance</h2>
</x-slot>

<div class="p-6 bg-white rounded shadow">

    <form method="GET" class="flex gap-4 mb-4">
        <input type="date" name="start_date" class="border rounded px-3 py-2">
        <input type="date" name="end_date" class="border rounded px-3 py-2">

        <button class="bg-blue-600 text-white px-4 py-2 rounded">
            Filter
        </button>
    </form>

    <div class="flex gap-3 mb-4">
        <form method="POST" action="{{ route('staff.Attendance.export.email') }}">
            @csrf
            <input type="hidden" name="start_date" value="{{ request('start_date') }}">
            <input type="hidden" name="end_date" value="{{ request('end_date') }}">
            <button class="bg-green-600 text-white px-4 py-2 rounded">
                Email Report
            </button>
        </form>

        <a href="{{ route('staff.Attendance.export.pdf', request()->all()) }}"
           class="bg-red-600 text-white px-4 py-2 rounded">
            Download PDF
        </a>
    </div>

    <table class="w-full border">
        <thead class="bg-gray-100">
            <tr>
                <th class="p-2 border">Date</th>
                <th class="p-2 border">Clock In</th>
                <th class="p-2 border">Clock Out</th>
            </tr>
        </thead>
        <tbody>
            @forelse($attendances as $attendance)
            <tr>
                <td class="p-2 border">{{ $attendance->clock_in->format('Y-m-d') }}</td>
                <td class="p-2 border">{{ $attendance->clock_in }}</td>
                <td class="p-2 border">{{ $attendance->clock_out ?? '—' }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="3" class="text-center p-4">No attendance found</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
</x-app-layout>
