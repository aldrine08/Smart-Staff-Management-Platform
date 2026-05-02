<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold">My Loan Requests</h2>
    </x-slot>

    <div class="py-6 max-w-6xl mx-auto">

        <!-- FORM -->
        <div class="bg-white p-6 rounded shadow mb-6">
            <h3 class="font-semibold mb-4">Request a Loan</h3>

            <form method="POST" action="{{ route('staff.loans.store') }}">
                @csrf

                <div class="mb-3">
                    <label>Amount</label>
                    <input type="number" name="amount_requested" class="w-full border p-2 rounded" required>
                </div>

                <div class="mb-3">
                    <label>Repayment Months</label>
                    <input type="number" name="repayment_months" class="w-full border p-2 rounded" required>
                </div>

                <div class="mb-3">
                    <label>Reason</label>
                    <textarea name="reason" class="w-full border p-2 rounded" required></textarea>
                </div>

                <button class="bg-green-600 text-white px-4 py-2 rounded">
                    Submit Request
                </button>
            </form>
        </div>

        <!-- TABLE -->
        <div class="bg-white p-6 rounded shadow">
            <h3 class="font-semibold mb-4">My Requests</h3>

            <table class="w-full border">
                <thead>
                    <tr class="bg-gray-100">
                        <th class="p-2 border">Amount</th>
                        <th class="p-2 border">Months</th>
                        <th class="p-2 border">Status</th>
                        <th class="p-2 border">Reason</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach($requests as $req)
                        <tr>
                            <td class="p-2 border">{{ $req->amount_requested }}</td>
                            <td class="p-2 border">{{ $req->repayment_months }}</td>
                            <td class="p-2 border capitalize">{{ $req->status }}</td>
                            <td class="p-2 border">{{ $req->reason }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

    </div>
</x-app-layout>