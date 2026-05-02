<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold">Active Loans</h2>
    </x-slot>

    <div class="py-6 max-w-7xl mx-auto">

        <table class="w-full border">
            <thead>
                <tr class="bg-gray-100">
                    <th class="border p-2">Staff</th>
                    <th class="border p-2">Amount</th>
                    <th class="border p-2">Installment</th>
                    <th class="border p-2">Remaining</th>
                    <th class="border p-2">Status</th>
                </tr>
            </thead>

            <tbody>
                @foreach($loans as $loan)
                    <tr>
                        <td class="border p-2">{{ $loan->user->name }}</td>
                        <td class="border p-2">{{ $loan->approved_amount }}</td>
                        <td class="border p-2">{{ $loan->monthly_installment }}</td>
                        <td class="border p-2">{{ $loan->remaining_balance }}</td>
                        <td class="border p-2 capitalize">{{ $loan->status }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

    </div>
</x-app-layout>