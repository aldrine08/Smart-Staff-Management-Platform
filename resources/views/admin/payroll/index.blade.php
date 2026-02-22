<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800">
            Payroll
        </h2>
    </x-slot>

    <div class="py-6 max-w-7xl mx-auto sm:px-6 lg:px-8">

        <!-- Flash Messages -->
        @if (session('success'))
            <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded">
                {{ session('error') }}
            </div>
        @endif

        <!-- Unit Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4 mb-6">
            @foreach(\App\Models\Unit::all() as $unit)
                <a href="{{ route('payroll.unit', $unit->id) }}" class="block bg-blue-600 text-white rounded-lg p-6 hover:bg-blue-700 transition">
                    <h3 class="text-lg font-semibold">{{ $unit->name }}</h3>
                    <p class="text-sm mt-1">{{ \App\Models\User::where('unit_id', $unit->id)->count() }} Staff</p>
                </a>
            @endforeach
        </div>

        <!-- Only show staff list if $payrollData exists -->
        @isset($payrollData)
            <!-- Date Filters -->
            <div class="mb-6 bg-white shadow rounded p-4 flex flex-col md:flex-row md:items-end gap-4">
                <form method="GET" action="{{ route('payroll.unit', $unitId ?? 0) }}" class="flex flex-col md:flex-row gap-4 w-full">
                    <input type="hidden" name="unit_id" value="{{ $unitId ?? '' }}">

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Start Date</label>
                        <input type="date" name="start_date" value="{{ request('start_date') }}" 
                               class="mt-1 block w-full border border-gray-300 rounded px-3 py-2 shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">End Date</label>
                        <input type="date" name="end_date" value="{{ request('end_date') }}" 
                               class="mt-1 block w-full border border-gray-300 rounded px-3 py-2 shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                    </div>

                    <div class="flex items-end">
                        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                            Filter
                        </button>
                    </div>
                </form>
            </div>

            <!-- Staff Payroll Table -->
            <div class="bg-white shadow rounded-lg p-4 overflow-x-auto mb-6">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Staff</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Days Worked</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Daily Salary</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Salary</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Deductions</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Bonuses</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Net Pay</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($payrollData as $data)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $data['staff']->name }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $data['daysWorked'] }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ number_format($data['dailySalary'], 2) }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ number_format($data['totalSalary'], 2) }}</td>
                                <td>{{ $data['daysWorked'] > 0 ? number_format($data['totalDeductions'], 2) : '0.00' }}</td>
                                <td>{{ $data['daysWorked'] > 0 ? number_format($data['totalBonuses'], 2) : '0.00' }}</td>

                                <td class="px-6 py-4 whitespace-nowrap font-semibold">{{ number_format($data['netPay'], 2) }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-4 text-center text-gray-500">No staff found for this unit.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Totals + Pay Salaries -->
            <div class="bg-white shadow rounded-lg p-4 mb-6 flex flex-col md:flex-row gap-4 justify-between items-center">
                <div class="space-y-1">
                    <p>Total Deductions: {{ number_format($totals['deductions'] ?? 0, 2) }}</p>
                    <p>Total Bonuses: {{ number_format($totals['bonuses'] ?? 0, 2) }}</p>
                    <p>Net Salary Total: {{ number_format($totals['netSalary'] ?? 0, 2) }}</p>
                </div>

                <div>
                    <form method="POST" action="{{ route('payroll.unit.pay', $unitId ?? 0) }}">
                        @csrf
                        <input type="hidden" name="start_date" value="{{ request('start_date') }}">
                        <input type="hidden" name="end_date" value="{{ request('end_date') }}">
                        <button type="submit" class="bg-green-600 text-white px-6 py-2 rounded hover:bg-green-700">
                            Pay Salaries
                        </button>
                    </form>
                </div>
            </div>
        @endisset

    </div>

    <footer class="text-center text-gray-500 text-sm py-6 border-t border-gray-300">
        © {{ date('Y') }} {{ config('app.name') }} — Staff Management System
    </footer>
</x-app-layout>
