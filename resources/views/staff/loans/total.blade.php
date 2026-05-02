<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold">All Loans Overview</h2>
    </x-slot>

    <div class="py-6 max-w-6xl mx-auto">

        <!-- Summary Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">

            <div class="bg-green-100 p-4 rounded text-center">
                <p class="font-bold">Active Loans</p>
                <p class="text-2xl">{{ $active }}</p>
            </div>

            <div class="bg-amber-100 p-4 rounded text-center">
                <p class="font-bold">Pending Loans</p>
                <p class="text-2xl">{{ $pending }}</p>
            </div>

            <div class="bg-red-100 p-4 rounded text-center">
                <p class="font-bold">Declined Loans</p>
                <p class="text-2xl">{{ $declined }}</p>
            </div>

        </div>

        <!-- All Loans List -->
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
                            @if($loan->status == 'active') bg-green-600
                            @elseif($loan->status == 'pending') bg-amber-600
                            @else bg-red-600
                            @endif">
                            {{ $loan->status }}
                        </span>
                    </div>
                </div>

            </div>

        @empty
            <p>No loans found.</p>
        @endforelse

    </div>
</x-app-layout>