<x-app-layout>
<x-slot name="header">
    <h2 class="text-xl font-semibold text-gray-800">
        My Payroll
    </h2>
</x-slot>

<div class="py-6 max-w-7xl mx-auto sm:px-6 lg:px-8">

    {{-- Latest Payslip Card --}}
    <div class="bg-white shadow rounded-lg p-6 mb-6">
        <h3 class="font-semibold mb-4">💰 Latest Payslip</h3>

        @if($latest)
            <div class="border rounded p-4 text-center">
                <p class="font-medium">
                    {{ $latest->month }}/{{ $latest->year }}
                </p>

                <p class="text-sm text-gray-600">
                    Net Salary: KES {{ number_format($latest->net_salary,2) }}
                </p>

                {{-- Show date range for latest payroll --}}
                <p class="text-sm text-gray-500 mt-1">
                    Period: 
                    {{ \Carbon\Carbon::parse($latest->start_date)->format('d M, Y') ?? 'N/A' }}
                    to 
                    {{ \Carbon\Carbon::parse($latest->end_date)->format('d M, Y') ?? 'N/A' }}
                </p>
            </div>

            <a href="{{ route('staff.payroll.show',$latest->id) }}"
               class="mt-4 block w-full bg-green-600 text-white py-2 rounded hover:bg-green-700 text-center">
               View Payslip
            </a>

        @else
            <p class="text-gray-500">No payroll generated yet.</p>
        @endif
    </div>


    {{-- Payroll History --}}
    <div class="bg-white shadow rounded-lg p-6">
        <h3 class="font-semibold mb-4">📜 Payroll History</h3>

        <table class="w-full border">
            <thead class="bg-gray-100">
                <tr>
                    <th class="p-2 border">Month</th>
                    <th class="p-2 border">From Date</th>
                    <th class="p-2 border">To Date</th>
                    <th class="p-2 border">Days</th>
                    <th class="p-2 border">Net Salary</th>
                    <th class="p-2 border">Action</th>
                </tr>
            </thead>

            <tbody>
            @forelse($payrolls as $p)
                <tr>
                    <td class="p-2 border">{{ $p->month }}/{{ $p->year }}</td>
                    <td class="p-2 border">
                        {{ optional($p->start_date) ? \Carbon\Carbon::parse($p->start_date)->format('d M, Y') : 'N/A' }}
                    </td>
                    <td class="p-2 border">
                        {{ optional($p->end_date) ? \Carbon\Carbon::parse($p->end_date)->format('d M, Y') : 'N/A' }}
                    </td>
                    <td class="p-2 border">{{ $p->days_present }}</td>
                    <td class="p-2 border">
                        KES {{ number_format($p->net_salary,2) }}
                    </td>
                    <td class="p-2 border">
                        <a href="{{ route('staff.payroll.show',$p->id) }}"
                           class="bg-blue-600 text-white px-3 py-1 rounded">
                           Open
                        </a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="p-2 text-center text-gray-500">No payroll records found.</td>
                </tr>
            @endforelse
            </tbody>
        </table>

    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

</x-app-layout>
