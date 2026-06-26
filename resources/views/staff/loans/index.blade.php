<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold">My Loan Requests</h2>
    </x-slot>

    <div class="py-6 max-w-6xl mx-auto">

        <!-- LOAN APPLICATION FORM -->
        <div class="mb-8">
            <!-- Header -->
            <div class="bg-gradient-to-r from-green-600 to-green-800 rounded-xl shadow-lg text-white p-6 mb-6">
                <h2 class="text-3xl font-bold">Welfare Loan Application Form</h2>
                <p class="mt-2 text-green-100">Submit your loan request for review and approval.</p>
            </div>

            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-4">
                    {{ session('success') }}
                </div>
            @endif

            @if($errors->any())
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-4">
                    <ul class="list-disc ml-5">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('staff.loans.store') }}">
                @csrf

                <!-- Applicant Details -->
                <div class="bg-white rounded-xl shadow mb-6 overflow-hidden">
                    <div class="bg-gray-100 px-6 py-4 border-b">
                        <h3 class="font-bold text-lg text-gray-700">Applicant Details</h3>
                    </div>
                    <div class="p-6 grid md:grid-cols-3 gap-5">
                        <div>
                            <label class="block text-sm text-gray-500 mb-1">Employee Name</label>
                            <input type="text" readonly value="{{ auth()->user()->name }}" class="w-full border rounded-lg p-3 bg-gray-50">
                        </div>
                        <div>
                            <label class="block text-sm text-gray-500 mb-1">Email Address</label>
                            <input type="text" readonly value="{{ auth()->user()->email }}" class="w-full border rounded-lg p-3 bg-gray-50">
                        </div>
                        <div>
                            <label class="block text-sm text-gray-500 mb-1">Application Date</label>
                            <input type="text" readonly value="{{ now()->format('d M Y') }}" class="w-full border rounded-lg p-3 bg-gray-50">
                        </div>
                    </div>
                </div>

                <!-- Loan Details -->
                <div class="bg-white rounded-xl shadow overflow-hidden">
                    <div class="bg-gray-100 px-6 py-4 border-b">
                        <h3 class="font-bold text-lg text-gray-700">Loan Details</h3>
                    </div>
                    <div class="p-6">
                        <div class="grid md:grid-cols-2 gap-5">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Requested Amount (KES)</label>
                                <input type="number" name="amount_requested" id="amount_requested" value="{{ old('amount_requested') }}" required class="w-full border rounded-lg p-3 focus:ring-2 focus:ring-green-500 focus:border-green-500">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Repayment Period (Months)</label>
                                <input type="number" name="repayment_months" id="repayment_months" value="{{ old('repayment_months') }}" required class="w-full border rounded-lg p-3 focus:ring-2 focus:ring-green-500 focus:border-green-500">
                            </div>
                        </div>
                        <div class="mt-5">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Purpose / Reason for Loan</label>
                            <textarea name="reason" rows="5" required class="w-full border rounded-lg p-3 focus:ring-2 focus:ring-green-500 focus:border-green-500">{{ old('reason') }}</textarea>
                        </div>

                        <!-- GUARANTOR DETAILS --> <div class="bg-white rounded-xl shadow overflow-hidden mt-6"> <div class="bg-gray-100 px-6 py-4 border-b"> <h3 class="font-bold text-lg text-gray-700"> Guarantor Details </h3> <p class="text-sm text-gray-500 mt-1"> Provide details of up to three guarantors. </p> </div> <div class="p-6 space-y-8"> <!-- GUARANTOR 1 --> <div class="border rounded-xl p-5 bg-gray-50"> <h4 class="font-semibold text-green-700 mb-4"> Guarantor 1 </h4> <div class="grid md:grid-cols-2 gap-4"> <div> <label class="block text-sm font-medium mb-2"> Full Name </label> <input type="text" name="guarantor1_name" class="w-full border rounded-lg p-3"> </div> <div> <label class="block text-sm font-medium mb-2"> ID Number </label> <input type="text" name="guarantor1_id" class="w-full border rounded-lg p-3"> </div> <div> <label class="block text-sm font-medium mb-2"> Phone Number </label> <input type="text" name="guarantor1_phone" class="w-full border rounded-lg p-3"> </div> <div> <label class="block text-sm font-medium mb-2"> Physical Address </label> <input type="text" name="guarantor1_address" class="w-full border rounded-lg p-3"> </div> </div> </div> <!-- GUARANTOR 2 --> <div class="border rounded-xl p-5 bg-gray-50"> <h4 class="font-semibold text-green-700 mb-4"> Guarantor 2 </h4> <div class="grid md:grid-cols-2 gap-4"> <div> <label class="block text-sm font-medium mb-2"> Full Name </label> <input type="text" name="guarantor2_name" class="w-full border rounded-lg p-3"> </div> <div> <label class="block text-sm font-medium mb-2"> ID Number </label> <input type="text" name="guarantor2_id" class="w-full border rounded-lg p-3"> </div> <div> <label class="block text-sm font-medium mb-2"> Phone Number </label> <input type="text" name="guarantor2_phone" class="w-full border rounded-lg p-3"> </div> <div> <label class="block text-sm font-medium mb-2"> Physical Address </label> <input type="text" name="guarantor2_address" class="w-full border rounded-lg p-3"> </div> </div> </div> <!-- GUARANTOR 3 --> <div class="border rounded-xl p-5 bg-gray-50"> <h4 class="font-semibold text-green-700 mb-4"> Guarantor 3 </h4> <div class="grid md:grid-cols-2 gap-4"> <div> <label class="block text-sm font-medium mb-2"> Full Name </label> <input type="text" name="guarantor3_name" class="w-full border rounded-lg p-3"> </div> <div> <label class="block text-sm font-medium mb-2"> ID Number </label> <input type="text" name="guarantor3_id" class="w-full border rounded-lg p-3"> </div> <div> <label class="block text-sm font-medium mb-2"> Phone Number </label> <input type="text" name="guarantor3_phone" class="w-full border rounded-lg p-3"> </div> <div> <label class="block text-sm font-medium mb-2"> Physical Address </label> <input type="text" name="guarantor3_address" class="w-full border rounded-lg p-3"> </div> </div> </div> </div> </div>

                        <!-- Loan Summary -->
                        <div class="mt-6 bg-green-50 border border-green-200 rounded-xl p-5">
                            <h4 class="font-bold text-green-700 mb-3">Loan Summary</h4>
                            <div class="grid md:grid-cols-2 gap-4">
                                <div>
                                    <span class="text-gray-600">Estimated Monthly Deduction:</span>
                                    <div id="monthlyDeduction" class="text-2xl font-bold text-green-700 mt-2">KES 0</div>
                                </div>
                                <div>
                                    <span class="text-gray-600">Current Status:</span>
                                    <div class="mt-2">
                                        <span class="bg-yellow-100 text-yellow-800 px-3 py-1 rounded-full text-sm font-medium">Pending Approval</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- APPROVAL DETAILS --> <div class="bg-white rounded-xl shadow overflow-hidden mt-6"> <div class="bg-gray-100 px-6 py-4 border-b"> <h3 class="font-bold text-lg text-gray-700"> Approval Details </h3> </div> <div class="p-6"> <div class="grid md:grid-cols-2 gap-6"> <!-- VERIFIED BY --> <div class="border rounded-xl p-5 bg-gray-50"> <h4 class="font-semibold text-green-700 mb-4"> Verified By </h4> <div class="space-y-4"> <div> <label class="block text-sm text-gray-500"> Name </label> <input type="text" readonly value="Pending Verification" class="w-full border rounded-lg p-3 bg-gray-100"> </div> <div> <label class="block text-sm text-gray-500"> Designation </label> <input type="text" readonly value="-" class="w-full border rounded-lg p-3 bg-gray-100"> </div> <div> <label class="block text-sm text-gray-500"> Date </label> <input type="text" readonly value="-" class="w-full border rounded-lg p-3 bg-gray-100"> </div> </div> </div> <!-- APPROVED BY --> <div class="border rounded-xl p-5 bg-gray-50"> <h4 class="font-semibold text-green-700 mb-4"> Approved By </h4> <div class="space-y-4"> <div> <label class="block text-sm text-gray-500"> Name </label> <input type="text" readonly value="Pending Approval" class="w-full border rounded-lg p-3 bg-gray-100"> </div> <div> <label class="block text-sm text-gray-500"> Designation </label> <input type="text" readonly value="-" class="w-full border rounded-lg p-3 bg-gray-100"> </div> <div> <label class="block text-sm text-gray-500"> Date </label> <input type="text" readonly value="-" class="w-full border rounded-lg p-3 bg-gray-100"> </div> </div> </div> </div> <!-- PAYMENT DETAILS --> <div class="mt-6 border-t pt-6"> <h4 class="font-semibold text-green-700 mb-4"> Payment Details </h4> <div class="grid md:grid-cols-2 gap-4"> <div> <label class="block text-sm text-gray-500 mb-1"> Bank </label> <input readonly value="CO-OP BANK" class="w-full border rounded-lg p-3 bg-gray-100"> </div> <div> <label class="block text-sm text-gray-500 mb-1"> Paybill / Account </label> <input readonly value="******-******" class="w-full border rounded-lg p-3 bg-gray-100"> </div> </div> </div> </div> </div>

                        <div class="mt-6 flex justify-end">
                            <button type="submit" class="bg-green-600 hover:bg-green-700 text-white font-semibold px-8 py-3 rounded-lg shadow-md transition">Submit Loan Request</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <!-- LOAN HISTORY TREE VIEW
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
        </div> -->

        <!-- TABLE
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
        </div> -->

    </div>

    <script> document.addEventListener('DOMContentLoaded', function () { const amount = document.getElementById('amount_requested'); const months = document.getElementById('repayment_months'); const result = document.getElementById('monthlyDeduction'); function calculateMonthlyDeduction() { let loanAmount = parseFloat(amount.value) || 0; let repaymentMonths = parseFloat(months.value) || 1; let monthly = loanAmount / repaymentMonths; result.innerHTML = 'KES ' + monthly.toLocaleString(undefined,{ minimumFractionDigits:2, maximumFractionDigits:2 }); } amount.addEventListener('input', calculateMonthlyDeduction); months.addEventListener('input', calculateMonthlyDeduction); }); </script>

    
</x-app-layout>