<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold">Loan Requests Management</h2>
    </x-slot>

    <div class="py-6 max-w-7xl mx-auto">

        <div class="bg-white p-6 rounded shadow">

            <table class="w-full border">
                <thead>
                    <tr class="bg-gray-100">
                        <th class="p-2 border">Staff</th>
                        <th class="p-2 border">Amount</th>
                        <th class="p-2 border">Months</th>
                        <th class="p-2 border">Status</th>
                        <th class="p-2 border">Actions</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach($requests as $req)
                        <tr>
                            <td class="p-2 border">{{ $req->user->name }}</td>
                            <td class="p-2 border">{{ $req->amount_requested }}</td>
                            <td class="p-2 border">{{ $req->repayment_months }}</td>
                            <td class="p-2 border capitalize">{{ $req->status }}</td>

                            <td class="p-2 border space-x-2">

                                @if($req->status === 'pending')

                                    <!-- APPROVE -->
                                    <form method="POST" action="{{ route('admin.loans.approve', $req->id) }}" class="inline">
                                        @csrf
                                        <input type="hidden" name="approved_amount" value="{{ $req->amount_requested }}">
                                        <input type="hidden" name="repayment_months" value="{{ $req->repayment_months }}">

                                        <button class="bg-green-600 text-white px-2 py-1 rounded text-sm">
                                            Approve
                                        </button>
                                    </form>

                                    <!-- REJECT -->
                                    <form method="POST" action="{{ route('admin.loans.reject', $req->id) }}" class="inline">
                                        @csrf
                                        <input type="text" name="admin_reason" placeholder="Reason"
                                               class="border p-1 text-sm rounded" required>

                                        <button class="bg-red-600 text-white px-2 py-1 rounded text-sm">
                                            Reject
                                        </button>
                                    </form>

                                @else
                                    <span class="text-gray-500">Processed</span>
                                @endif

                            </td>
                        </tr>
                    @endforeach
                </tbody>

            </table>

        </div>
    </div>
</x-app-layout>