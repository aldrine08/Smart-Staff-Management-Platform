<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold">Loan Requests Management</h2>
    </x-slot>

    @if(session('success'))
    <div class="mb-4 p-3 bg-green-100 text-green-800 rounded">
        {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div class="mb-4 p-3 bg-red-100 text-red-800 rounded">
        {{ session('error') }}
    </div>
@endif

<div class="grid md:grid-cols-4 gap-4 mb-6">

    <div class="bg-white p-5 rounded-xl shadow border">
        <p class="text-gray-500 text-sm">Total Requests</p>
        <h3 class="text-2xl font-bold text-gray-800">
            {{ $requests->count() }}
        </h3>
    </div>

    <div class="bg-white p-5 rounded-xl shadow border">
        <p class="text-gray-500 text-sm">Pending</p>
        <h3 class="text-2xl font-bold text-yellow-600">
            {{ $requests->where('status','pending')->count() }}
        </h3>
    </div>

    <div class="bg-white p-5 rounded-xl shadow border">
        <p class="text-gray-500 text-sm">Approved</p>
        <h3 class="text-2xl font-bold text-green-600">
            {{ $requests->where('status','approved')->count() }}
        </h3>
    </div>

    <div class="bg-white p-5 rounded-xl shadow border">
        <p class="text-gray-500 text-sm">Rejected</p>
        <h3 class="text-2xl font-bold text-red-600">
            {{ $requests->where('status','rejected')->count() }}
        </h3>
    </div>

</div>

<!-- Search & Filter Panel -->
<div class="bg-white rounded-2xl shadow-sm border border-gray-200 mb-8 overflow-hidden">

    <!-- Header -->
    <div class="px-6 py-4 border-b bg-gradient-to-r from-blue-50 to-indigo-50">
        <h3 class="text-lg font-semibold text-gray-800">
            Loan Request Search & Filters
        </h3>
        <p class="text-sm text-gray-500 mt-1">
            Search loan applications by staff information, department, or unit.
        </p>
    </div>

    <!-- Form -->
    <div class="p-6">

        <form method="GET">

            <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-5">

                <!-- Staff Name -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Staff Name
                    </label>

                    <input
                        type="text"
                        name="name"
                        placeholder="Enter staff name..."
                        value="{{ request('name') }}"
                        class="w-full border border-gray-300 rounded-xl px-4 py-3
                               focus:ring-2 focus:ring-blue-500
                               focus:border-blue-500 transition">
                </div>

                <!-- Email -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Email Address
                    </label>

                    <input
                        type="text"
                        name="email"
                        placeholder="Enter email..."
                        value="{{ request('email') }}"
                        class="w-full border border-gray-300 rounded-xl px-4 py-3
                               focus:ring-2 focus:ring-blue-500
                               focus:border-blue-500 transition">
                </div>

                <!-- Unit -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Unit
                    </label>

                    <input
                        type="text"
                        name="unit"
                        placeholder="Enter unit..."
                        value="{{ request('unit') }}"
                        class="w-full border border-gray-300 rounded-xl px-4 py-3
                               focus:ring-2 focus:ring-blue-500
                               focus:border-blue-500 transition">
                </div>

                <!-- Department -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Department
                    </label>

                    <input
                        type="text"
                        name="department"
                        placeholder="Enter department..."
                        value="{{ request('department') }}"
                        class="w-full border border-gray-300 rounded-xl px-4 py-3
                               focus:ring-2 focus:ring-blue-500
                               focus:border-blue-500 transition">
                </div>

            </div>

            <!-- Buttons -->
            <div class="flex flex-wrap gap-3 mt-6">

                <button
                    type="submit"
                    class="inline-flex items-center px-6 py-3
                           bg-blue-600 hover:bg-blue-700
                           text-white font-medium
                           rounded-xl shadow-sm transition">

                    🔍 Search Loans
                </button>

                <a href="{{ route('admin.loans.index') }}"
                   class="inline-flex items-center px-6 py-3
                          bg-gray-100 hover:bg-gray-200
                          text-gray-700 font-medium
                          rounded-xl transition">

                    ↺ Clear Filters
                </a>

            </div>

        </form>

    </div>

</div>

    <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">

    

@forelse($requests as $req)

    @php
        $loan = \App\Models\Loan::where('user_id', $req->user_id)
            ->latest()
            ->first();
    @endphp

    <a href="{{ route('admin.loans.show', $req->id) }}">

        <div class="bg-white rounded-2xl shadow-md hover:shadow-xl transition duration-300 border border-gray-100 overflow-hidden">

            <!-- Header -->
            <div class="bg-gradient-to-r from-blue-600 to-indigo-600 p-5 text-white">

                <div class="flex justify-between items-start">

                    <div>
                        <h3 class="font-bold text-lg">
                            {{ $req->user->name }}
                        </h3>

                        <p class="text-blue-100 text-sm">
                            {{ $req->user->email }}
                        </p>
                    </div>

                    <!-- Request Status -->
                    <span
                        class="px-3 py-1 rounded-full text-xs font-semibold

                        @if($req->status == 'approved')
                            bg-green-100 text-green-700
                        @elseif($req->status == 'rejected')
                            bg-red-100 text-red-700
                        @else
                            bg-yellow-100 text-yellow-700
                        @endif">

                        {{ ucfirst($req->status) }}

                    </span>

                </div>

            </div>

            <!-- Body -->
            <div class="p-5">

                <div class="space-y-2 text-sm text-gray-600">

                    <p>
                        <span class="font-semibold">
                            Department:
                        </span>

                        {{ optional($req->user->department)->name ?? 'N/A' }}
                    </p>

                    <p>
                        <span class="font-semibold">
                            Unit:
                        </span>

                        {{ optional($req->user->unit)->name ?? 'N/A' }}
                    </p>

                    <p>
                        <span class="font-semibold">
                            Requested:
                        </span>

                        KES {{ number_format($req->amount_requested) }}
                    </p>

                    <p>
                        <span class="font-semibold">
                            Repayment:
                        </span>

                        {{ $req->repayment_months }} Months
                    </p>

                </div>

                <!-- Divider -->
                <div class="border-t my-4"></div>

                <!-- Loan Status -->
                <div class="flex items-center justify-between">

                    <span class="text-sm font-semibold text-gray-700">
                        Loan Status
                    </span>

                    @if($loan)

                        @if($loan->status == 'active')

                            <span class="px-3 py-1 rounded-full bg-green-100 text-green-700 text-xs font-semibold">
                                Active Loan
                            </span>

                        @elseif($loan->status == 'completed')

                            <span class="px-3 py-1 rounded-full bg-blue-100 text-blue-700 text-xs font-semibold">
                                Completed
                            </span>

                        @else

                            <span class="px-3 py-1 rounded-full bg-gray-100 text-gray-700 text-xs font-semibold">
                                {{ ucfirst($loan->status) }}
                            </span>

                        @endif

                    @else

                        <span class="px-3 py-1 rounded-full bg-gray-100 text-gray-700 text-xs font-semibold">
                            No Loan
                        </span>

                    @endif

                </div>

                <!-- Active Loan Details -->
                @if($loan && $loan->status == 'active')

                    <div class="mt-4 bg-green-50 border border-green-100 rounded-lg p-3">

                        <div class="text-sm">

                            <div class="flex justify-between mb-2">
                                <span>Approved Amount</span>

                                <span class="font-semibold">
                                    KES {{ number_format($loan->approved_amount) }}
                                </span>
                            </div>

                            <div class="flex justify-between mb-2">
                                <span>Monthly Installment</span>

                                <span class="font-semibold">
                                    KES {{ number_format($loan->monthly_installment) }}
                                </span>
                            </div>

                            <div class="flex justify-between">
                                <span>Remaining Balance</span>

                                <span class="font-bold text-red-600">
                                    KES {{ number_format($loan->remaining_balance) }}
                                </span>
                            </div>

                        </div>

                    </div>

                @endif

                <!-- Footer -->
                <div class="mt-5 flex justify-end">

                    <span class="text-blue-600 text-sm font-semibold">
                        View Details →
                    </span>

                </div>

            </div>

        </div>

    </a>

@empty

    <div class="col-span-3">

        <div class="bg-white rounded-xl shadow p-10 text-center">

            <svg xmlns="http://www.w3.org/2000/svg"
                 class="w-12 h-12 mx-auto text-gray-400 mb-3"
                 fill="none"
                 viewBox="0 0 24 24"
                 stroke="currentColor">

                <path stroke-linecap="round"
                      stroke-linejoin="round"
                      stroke-width="2"
                      d="M9 14l2 2 4-4"/>

            </svg>

            <h3 class="text-lg font-semibold text-gray-700">
                No Loan Requests Found
            </h3>

            <p class="text-gray-500 mt-2">
                No matching loan requests are available.
            </p>

        </div>

    </div>

@endforelse

</div>
</x-app-layout>
