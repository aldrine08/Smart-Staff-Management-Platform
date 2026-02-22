<x-app-layout>
<x-slot name="header">
    <h2 class="text-xl font-semibold text-gray-800">
        Processed Payrolls
    </h2>
</x-slot>

<div class="py-6 max-w-7xl mx-auto sm:px-6 lg:px-8">

    <!-- Filters -->
    <form method="GET" class="mb-4 flex gap-4">
        <select name="unit_id" class="border rounded px-2 py-1">
            <option value="">All Units</option>
            @foreach($units as $unit)
                <option value="{{ $unit->id }}" @if(request('unit_id') == $unit->id) selected @endif>{{ $unit->name }}</option>
            @endforeach
        </select>

        <select name="user_id" class="border rounded px-2 py-1">
            <option value="">All Staff</option>
            @foreach($staffs as $staff)
                <option value="{{ $staff->id }}" @if(request('user_id') == $staff->id) selected @endif>{{ $staff->name }}</option>
            @endforeach
        </select>

        <input type="date" name="start_date" value="{{ request('start_date') }}" class="border rounded px-2 py-1">
        <input type="date" name="end_date" value="{{ request('end_date') }}" class="border rounded px-2 py-1">

        <button type="submit" class="bg-blue-600 text-white px-4 py-1 rounded hover:bg-blue-700">Filter</button>
    </form>


    <div class="mb-4 flex gap-4">
    <a href="{{ route('payroll.processed.pdf', request()->query()) }}" 
       class="bg-gray-800 text-white px-4 py-1 rounded hover:bg-gray-900">
       Download PDF
    </a>
    <a href="{{ route('payroll.processed.email', request()->query()) }}" 
       class="bg-green-600 text-white px-4 py-1 rounded hover:bg-green-700">
       Email Report
    </a>
</div>


    <!-- Table -->
    <div class="bg-white shadow rounded-lg p-4 overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th>Staff</th>
                    <th>Unit</th>
                    <th>Department</th>
                    <th>Start Date</th>
                    <th>End Date</th>
                    <th>Net Salary</th>
                    <th>Processed At</th>
                </tr>
            </thead>
            <tbody>
                @foreach($processedPayrolls as $pay)
                <tr>
                    <td>{{ $pay->user->name }}</td>
                    <td>{{ $pay->unit->name }}</td>
                    <td>{{ $pay->department->name ?? '-' }}</td>
                    <td>{{ $pay->start_date }}</td>
                    <td>{{ $pay->end_date }}</td>
                    <td>{{ number_format($pay->net_salary,2) }}</td>
                    <td>{{ $pay->processed_at }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <div class="mt-4">
            {{ $processedPayrolls->withQueryString()->links() }}
        </div>
    </div>

</div>
</x-app-layout>
