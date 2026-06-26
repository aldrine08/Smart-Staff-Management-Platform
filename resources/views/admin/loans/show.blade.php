<x-app-layout>

    <x-slot name="header">
        <h2 class="text-xl font-semibold">
            Loan Request Details
        </h2>
    </x-slot>

    <div class="max-w-7xl mx-auto py-6">

        {{-- SUCCESS --}}
        @if(session('success'))
            <div class="mb-4 p-4 bg-green-100 text-green-800 rounded">
                {{ session('success') }}
            </div>
        @endif

        {{-- ERROR --}}
        @if(session('error'))
            <div class="mb-4 p-4 bg-red-100 text-red-800 rounded">
                {{ session('error') }}
            </div>
        @endif

        {{-- STATUS --}}
        <div class="bg-white rounded-xl shadow p-6 mb-6">

            <div class="flex justify-between items-center">

                <div>
                    <h1 class="text-2xl font-bold">
                        {{ $loanRequest->user->name }}
                    </h1>

                    <p class="text-gray-500">
                        Loan Application #{{ $loanRequest->id }}
                    </p>
                </div>

                <div>
                    @if($loanRequest->status == 'approved')
                        <span class="bg-green-100 text-green-700 px-4 py-2 rounded-full font-semibold">
                            Approved
                        </span>
                    @elseif($loanRequest->status == 'rejected')
                        <span class="bg-red-100 text-red-700 px-4 py-2 rounded-full font-semibold">
                            Rejected
                        </span>
                    @else
                        <span class="bg-yellow-100 text-yellow-700 px-4 py-2 rounded-full font-semibold">
                            Pending Review
                        </span>
                    @endif
                </div>

            </div>

        </div>

        {{-- STAFF PROFILE --}}
        <div class="bg-white rounded-xl shadow mb-6">

            <div class="border-b px-6 py-4">
                <h3 class="font-bold text-lg">
                    Staff Profile
                </h3>
            </div>

            <div class="p-6">

                <div class="grid md:grid-cols-3 gap-6">

                    <div>
                        <label class="text-sm text-gray-500">Full Name</label>
                        <p class="font-semibold">
                            {{ $loanRequest->user->name }}
                        </p>
                    </div>

                    <div>
                        <label class="text-sm text-gray-500">Email</label>
                        <p class="font-semibold">
                            {{ $loanRequest->user->email }}
                        </p>
                    </div>

                    <div>
                        <label class="text-sm text-gray-500">Phone</label>
                        <p class="font-semibold">
                            {{ $loanRequest->user->phone ?? 'N/A' }}
                        </p>
                    </div>

                    <div>
                        <label class="text-sm text-gray-500">Employment Number</label>
                        <p class="font-semibold">
                            {{ $loanRequest->user->employment_number ?? 'N/A' }}
                        </p>
                    </div>

                    <div>
                        <label class="text-sm text-gray-500">Department</label>
                        <p class="font-semibold">
                            {{ optional($loanRequest->user->department)->name }}
                        </p>
                    </div>

                    <div>
                        <label class="text-sm text-gray-500">Unit</label>
                        <p class="font-semibold">
                            {{ optional($loanRequest->user->unit)->name }}
                        </p>
                    </div>

                </div>

            </div>

        </div>

        {{-- LOAN DETAILS --}}
        <div class="bg-white rounded-xl shadow mb-6">

            <div class="border-b px-6 py-4">
                <h3 class="font-bold text-lg">
                    Loan Information
                </h3>
            </div>

            <div class="p-6">

                <div class="grid md:grid-cols-2 gap-6">

                    <div>
                        <label class="text-sm text-gray-500">
                            Requested Amount
                        </label>

                        <p class="text-2xl font-bold text-green-600">
                            KES {{ number_format($loanRequest->amount_requested,2) }}
                        </p>
                    </div>

                    <div>
                        <label class="text-sm text-gray-500">
                            Repayment Period
                        </label>

                        <p class="font-semibold">
                            {{ $loanRequest->repayment_months }} Months
                        </p>
                    </div>

                    <div>
                        <label class="text-sm text-gray-500">
                            Recovery Start Date
                        </label>

                        <p>
                            {{ $loanRequest->recovery_start_date ?? 'Not Specified' }}
                        </p>
                    </div>

                    <div>
                        <label class="text-sm text-gray-500">
                            Application Date
                        </label>

                        <p>
                            {{ $loanRequest->created_at->format('d M Y') }}
                        </p>
                    </div>

                </div>

                <div class="mt-6">

                    <label class="text-sm text-gray-500">
                        Reason For Loan
                    </label>

                    <div class="bg-gray-50 rounded-lg p-4 mt-2">
                        {{ $loanRequest->reason }}
                    </div>

                </div>

                @if($loanRequest->special_notes)

                    <div class="mt-6">

                        <label class="text-sm text-gray-500">
                            Special Notes
                        </label>

                        <div class="bg-blue-50 rounded-lg p-4 mt-2">
                            {{ $loanRequest->special_notes }}
                        </div>

                    </div>

                @endif

            </div>

        </div>

        {{-- GUARANTORS --}}
        <div class="bg-white rounded-xl shadow mb-6">

            <div class="border-b px-6 py-4">
                <h3 class="font-bold text-lg">
                    Guarantor Information
                </h3>
            </div>

            <div class="p-6 space-y-6">

                {{-- GUARANTOR 1 --}}
                <div class="border rounded-lg p-4">

                    <h4 class="font-semibold text-green-700 mb-3">
                        Guarantor 1
                    </h4>

                    <div class="grid md:grid-cols-2 gap-4">

                        <p><strong>Name:</strong> {{ $loanRequest->guarantor1_name ?: 'N/A' }}</p>
                        <p><strong>ID:</strong> {{ $loanRequest->guarantor1_id ?: 'N/A' }}</p>
                        <p><strong>Phone:</strong> {{ $loanRequest->guarantor1_phone ?: 'N/A' }}</p>
                        <p><strong>Address:</strong> {{ $loanRequest->guarantor1_address ?: 'N/A' }}</p>

                    </div>

                </div>

                {{-- GUARANTOR 2 --}}
                <div class="border rounded-lg p-4">

                    <h4 class="font-semibold text-green-700 mb-3">
                        Guarantor 2
                    </h4>

                    <div class="grid md:grid-cols-2 gap-4">

                        <p><strong>Name:</strong> {{ $loanRequest->guarantor2_name ?: 'N/A' }}</p>
                        <p><strong>ID:</strong> {{ $loanRequest->guarantor2_id ?: 'N/A' }}</p>
                        <p><strong>Phone:</strong> {{ $loanRequest->guarantor2_phone ?: 'N/A' }}</p>
                        <p><strong>Address:</strong> {{ $loanRequest->guarantor2_address ?: 'N/A' }}</p>

                    </div>

                </div>

                {{-- GUARANTOR 3 --}}
                <div class="border rounded-lg p-4">

                    <h4 class="font-semibold text-green-700 mb-3">
                        Guarantor 3
                    </h4>

                    <div class="grid md:grid-cols-2 gap-4">

                        <p><strong>Name:</strong> {{ $loanRequest->guarantor3_name ?: 'N/A' }}</p>
                        <p><strong>ID:</strong> {{ $loanRequest->guarantor3_id ?: 'N/A' }}</p>
                        <p><strong>Phone:</strong> {{ $loanRequest->guarantor3_phone ?: 'N/A' }}</p>
                        <p><strong>Address:</strong> {{ $loanRequest->guarantor3_address ?: 'N/A' }}</p>

                    </div>

                </div>

            </div>

        </div>

        {{-- APPROVAL HISTORY --}}
        <div class="bg-white rounded-xl shadow mb-6">

            <div class="border-b px-6 py-4">
                <h3 class="font-bold text-lg">
                    Approval History
                </h3>
            </div>

            <div class="p-6">

                <p>
                    <strong>Status:</strong>
                    {{ ucfirst($loanRequest->status) }}
                </p>

                <p>
                    <strong>Reviewed By ID:</strong>
                    {{ $loanRequest->reviewed_by ?? 'Not Yet Reviewed' }}
                </p>

                @if($loanRequest->admin_reason)

                    <div class="mt-4">

                        <strong>Rejection Reason:</strong>

                        <div class="bg-red-50 p-4 rounded mt-2">
                            {{ $loanRequest->admin_reason }}
                        </div>

                    </div>

                @endif

            </div>

        </div>

        {{-- ACTIONS --}}
        @if($loanRequest->status == 'pending')

            <div class="grid md:grid-cols-2 gap-6">

                {{-- APPROVE --}}
                <div class="bg-white rounded-xl shadow p-6">

                    <h3 class="font-bold text-green-700 mb-4">
                        Approve Loan
                    </h3>

                    <form method="POST"
                          action="{{ route('admin.loans.approve',$loanRequest->id) }}">

                        @csrf

                        <div class="mb-4">

                            <label class="block mb-2">
                                Approved Amount
                            </label>

                            <input
                                type="number"
                                name="approved_amount"
                                value="{{ $loanRequest->amount_requested }}"
                                class="w-full border rounded p-3"
                                required>
                        </div>

                        <div class="mb-4">

                            <label class="block mb-2">
                                Repayment Months
                            </label>

                            <input
                                type="number"
                                name="repayment_months"
                                value="{{ $loanRequest->repayment_months }}"
                                class="w-full border rounded p-3"
                                required>
                        </div>

                        <button
                            type="submit"
                            class="bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded">

                            Approve Loan

                        </button>

                    </form>

                </div>

                {{-- REJECT --}}
                <div class="bg-white rounded-xl shadow p-6">

                    <h3 class="font-bold text-red-700 mb-4">
                        Reject Loan
                    </h3>

                    <form method="POST"
                          action="{{ route('admin.loans.reject',$loanRequest->id) }}">

                        @csrf

                        <div class="mb-4">

                            <label class="block mb-2">
                                Reason For Rejection
                            </label>

                            <textarea
                                name="admin_reason"
                                rows="5"
                                class="w-full border rounded p-3"
                                required></textarea>

                        </div>

                        <button
                            type="submit"
                            class="bg-red-600 hover:bg-red-700 text-white px-6 py-3 rounded">

                            Reject Loan

                        </button>

                    </form>

                </div>

            </div>

        @endif

        <!-- LOAN STATUS -->
<div class="bg-white shadow rounded-lg p-6 mt-6">

    <h3 class="text-lg font-bold mb-4">
        Loan Status
    </h3>
    

    @if($activeLoan)

        <div class="flex items-center justify-between">

            <div>

                <p class="text-gray-600">
                    Approved Amount
                </p>

                <p class="font-bold text-xl">
                    KES {{ number_format($activeLoan->approved_amount) }}
                </p>

            </div>

            <div>

                @if($activeLoan->status === 'active')

                    <span class="px-4 py-2 bg-green-100 text-green-800 rounded-full font-semibold">
                        Active Loan
                    </span>

                @elseif($activeLoan->status === 'completed')

                    <span class="px-4 py-2 bg-blue-100 text-blue-800 rounded-full font-semibold">
                        Completed Loan
                    </span>

                @endif

            </div>

        </div>

    @else

        <span class="px-4 py-2 bg-gray-100 text-gray-800 rounded-full font-semibold">
            No Active Loan
        </span>

    @endif

    @if($activeLoan && $activeLoan->status === 'active')

    <form
        action="{{ route('admin.loans.complete', $activeLoan->id) }}"
        method="POST"
        class="mt-4"
        onsubmit="return confirm(
            'Are you sure?\n\nThis will mark the loan as fully repaid.'
        );"
    >
        @csrf

        <button
            type="submit"
            class="bg-green-600 hover:bg-green-700 text-white px-5 py-2 rounded-lg"
        >
            Mark Loan As Completed
        </button>

    </form>

@endif

</div>

    </div>

    

</x-app-layout>