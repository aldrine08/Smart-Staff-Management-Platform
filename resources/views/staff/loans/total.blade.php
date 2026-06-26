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

        <!-- ACTIVE / APPROVED LOANS -->
<div class="bg-white rounded-xl shadow p-6 mb-6">
    <h3 class="text-lg font-bold mb-4 text-green-700">
        Approved / Active Loans
    </h3>

    @forelse($loans as $loan)

        <div class="border rounded-lg p-4 mb-3">

            <div class="flex justify-between">

                <div>
                    <h4 class="font-bold text-lg">
                        KES {{ number_format($loan->approved_amount, 2) }}
                    </h4>

                    <p class="text-sm text-gray-600">
                        Monthly Installment:
                        KES {{ number_format($loan->monthly_installment, 2) }}
                    </p>

                    <p class="text-sm text-gray-600">
                        Remaining Balance:
                        KES {{ number_format($loan->remaining_balance, 2) }}
                    </p>
                </div>

                <div>
                    <span class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-sm">
                        {{ ucfirst($loan->status) }}
                    </span>
                </div>

            </div>

        </div>

    @empty

        <p class="text-gray-500">
            No approved loans found.
        </p>

    @endforelse
</div>

<div class="bg-white rounded-xl shadow p-6">

    <h3 class="text-lg font-bold mb-4 text-blue-700">
        Loan Application History
    </h3>

    @forelse($requests as $request)

        <div class="border rounded-lg p-4 mb-4">

            <div class="flex justify-between mb-2">

                <div>
                    <h4 class="font-bold">
                        KES {{ number_format($request->amount_requested, 2) }}
                    </h4>

                    <p class="text-sm text-gray-500">
                        {{ $request->repayment_months }} Months
                    </p>
                </div>

                <div>

                    @if($request->status == 'approved')

                        <span class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-sm">
                            Approved
                        </span>

                    @elseif($request->status == 'rejected')

                        <span class="bg-red-100 text-red-800 px-3 py-1 rounded-full text-sm">
                            Rejected
                        </span>

                    @else

                        <span class="bg-yellow-100 text-yellow-800 px-3 py-1 rounded-full text-sm">
                            Pending
                        </span>

                    @endif

                </div>

            </div>

            <div class="text-sm text-gray-700 mb-2">
                <strong>Reason:</strong>
                {{ $request->reason }}
            </div>

            @if($request->status == 'rejected')

                <div class="bg-red-50 border border-red-200 rounded p-3 mt-2">

                    <p class="font-semibold text-red-700">
                        Rejection Reason
                    </p>

                    <p class="text-red-600">
                        {{ $request->admin_reason }}
                    </p>

                </div>

            @endif

        </div>

    @empty

        <p class="text-gray-500">
            No loan applications found.
        </p>

    @endforelse

</div>

    </div>
</x-app-layout>