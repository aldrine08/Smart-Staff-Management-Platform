<x-app-layout>
<x-slot name="header">
    <h2 class="text-xl font-semibold text-gray-800">
        Payslip Details
    </h2>
</x-slot>

<div class="py-6 max-w-4xl mx-auto">

<div class="bg-white shadow rounded-lg p-6">

<h3 class="text-lg font-bold mb-4">
    Payslip — {{ $payroll->month }}/{{ $payroll->year }}
</h3>

<div class="grid grid-cols-2 gap-4">

<div>
    <p>Days Present</p>
    <p class="font-semibold">{{ $payroll->days_present }}</p>
</div>

<div>
    <p>Base Salary</p>
    <p class="font-semibold">
        KES {{ number_format($payroll->base_salary,2) }}
    </p>
</div>

<div>
    <p>Total Bonus</p>
    <p class="font-semibold text-green-600">
        + {{ number_format($payroll->total_bonus,2) }}
    </p>
</div>

<div>
    <p>Total Deductions</p>
    <p class="font-semibold text-red-600">
        - {{ number_format($payroll->total_deductions,2) }}
    </p>
</div>

</div>

<hr class="my-4">

<div class="text-xl font-bold">
    Net Salary:
    KES {{ number_format($payroll->net_salary,2) }}
</div>

</div>

</div>
</x-app-layout>
