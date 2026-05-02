<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold">My Active Loans</h2>
    </x-slot>

    <div class="py-6 max-w-6xl mx-auto">

        @forelse($loans as $loan)

            <div class="bg-white p-6 rounded shadow mb-4">

                <div class="flex justify-between">
                    <div>
                        <p class="text-lg font-bold">KES {{ $loan->approved_amount }}</p>
                        <p class="text-sm text-gray-500">
                            Monthly Installment: {{ $loan->monthly_installment }}
                        </p>
                    </div>

                    <div>
                        <span class="px-3 py-1 rounded text-white 
                            {{ $loan->status === 'active' ? 'bg-green-600' : 'bg-gray-600' }}">
                            {{ $loan->status }}
                        </span>
                    </div>
                </div>

                <div class="mt-4">
                    <p>Remaining Balance: <b>{{ $loan->remaining_balance }}</b></p>
                    <p>Repayment Months: {{ $loan->repayment_months }}</p>
                </div>

            </div>

        @empty
            <p>No active loans found.</p>
        @endforelse

    </div>
</x-app-layout>