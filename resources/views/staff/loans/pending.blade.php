<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold">My Pending Loans</h2>
    </x-slot>

    <div class="py-6 max-w-6xl mx-auto">

        <!-- LOAN HISTORY TREE VIEW -->
        <div class="mb-8">
            <div class="bg-white rounded-xl shadow p-6">
                <h3 class="font-bold text-lg text-gray-700 mb-4">Loan Request History</h3>
                <div class="space-y-2">
                    @forelse($requests as $req)
                        <div class="border-l-4 border-green-600 pl-4 py-2">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="font-semibold text-gray-800">KES {{ number_format($req->amount_requested) }}</p>
                                    <p class="text-sm text-gray-600">{{ $req->repayment_months }} months • {{ $req->created_at->format('d M Y') }}</p>
                                    <p class="text-sm text-gray-500 mt-1">{{ Str::limit($req->reason, 60) }}</p>
                                </div>
                                <span class="px-3 py-1 rounded-full text-sm font-medium @if($req->status === 'approved') bg-green-100 text-green-800 @elseif($req->status === 'rejected') bg-red-100 text-red-800 @else bg-yellow-100 text-yellow-800 @endif">
                                    {{ ucfirst($req->status) }}
                                </span>
                            </div>
                        </div>
                    @empty
                        <p class="text-gray-500 text-center py-4">No loan requests yet</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</x-app-layout>