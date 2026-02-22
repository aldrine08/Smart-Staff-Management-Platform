<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Staff Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- Greeting -->
            <div class="p-6 bg-gradient-to-r from-blue-100 to-blue-200 rounded-lg shadow text-gray-800 animate-fadeIn">
                @php
                    $hour = now()->format('H');
                    $greeting = match(true) {
                        $hour < 12 => 'Good morning',
                        $hour < 17 => 'Good afternoon',
                        $hour < 20 => 'Good evening',
                        default => 'Good night'
                    };
                @endphp
                <h3 class="text-xl font-semibold">{{ $greeting }}, {{ Auth::user()->name }}! 👋</h3>
                <p class="text-gray-700 mt-1">Here’s a quick overview of your attendance and leave status.</p>
            </div>

            <!-- Profile Card -->
            <div class="bg-white shadow-sm rounded-lg p-6 flex flex-col sm:flex-row items-center gap-6">
                <img
                    src="{{ Auth::user()->avatar ? asset('storage/'.Auth::user()->avatar) : 'https://ui-avatars.com/api/?name='.urlencode(Auth::user()->name) }}"
                    alt="User Avatar"
                    class="w-24 h-24 rounded-full object-cover">

                <div class="space-y-1">
                    <p><strong>Name:</strong> {{ Auth::user()->name }}</p>
                    <p><strong>Operation Unit:</strong> {{ Auth::user()->unit->name ?? 'Not Assigned' }}</p>
                    <p><strong>Department:</strong> {{ Auth::user()->department->name ?? 'Not Assigned' }}</p>
                    <p><strong>Email:</strong> {{ Auth::user()->email }}</p>
                    <p><strong>Phone:</strong> {{ Auth::user()->phone ?? 'Not provided' }}</p>
                    <p><strong>Role:</strong> {{ Auth::user()->role }}</p>
                </div>
            </div>

            <!-- Attendance Quick Access -->
            <div class="bg-white shadow rounded-lg p-6 space-y-4">
                <h3 class="font-semibold text-lg">⚡ Attendance</h3>
                <p class="text-sm text-gray-600">⏰Clock in and out for today so that your attendance is properly recorded.</p>

                <!-- Status Message -->
                <div>
    @if($clockedIn && $clockedOut)
        <p class="text-green-600 font-semibold">
            ✅ You have clocked in at {{ $clockedIn->format('h:i A') }} 
            and clocked out at {{ $clockedOut->format('h:i A') }}
        </p>
    @elseif($clockedIn)
        <p class="text-green-600 font-semibold">
            ✅ You have clocked in at {{ $clockedIn->format('h:i A') }}. Don't forget to clock out later.
        </p>
    @else
        <p class="text-red-600 font-semibold">⏱️ You haven’t clocked in yet today.</p>
    @endif
</div>

                <!-- Clock In / Clock Out Buttons -->
                <div class="grid grid-cols-2 gap-4">
                    <form action="{{ route('attendance.clockin') }}" method="POST">
                        @csrf
                        <button type="submit"
                                class="w-full bg-green-600 text-white py-2 rounded disabled:opacity-50"
                                {{ $clockedIn ? 'disabled' : '' }}>
                            ⏰ Clock In
                        </button>
                    </form>

                    <form action="{{ route('attendance.clockout') }}" method="POST">
                        @csrf
                        <button type="submit"
                                class="w-full bg-red-600 text-white py-2 rounded disabled:opacity-50"
                                {{ (!$clockedIn || $clockedOut) ? 'disabled' : '' }}>
                            ⏱️ Clock Out
                        </button>
                    </form>
                </div>
            </div>


            <!-- <a href="{{ route('staff.Attendance.index') }}"
   class="block bg-white rounded-xl shadow hover:shadow-lg transition p-6">

    <div class="flex items-center justify-between">
        <div>
            <h3 class="text-lg font-semibold text-gray-700">
                My Attendance
            </h3>
            <p class="text-sm text-gray-500">
                View all your clock-ins & clock-outs
            </p>
        </div>

        <div class="text-blue-600 text-3xl font-bold">
            {{ $attendanceCount ?? '' }}
        </div>
    </div>
</a> -->


            <!-- Off Days Management -->
            <div class="bg-white shadow rounded-lg p-6 space-y-4">
                <h3 class="text-lg font-semibold flex items-center gap-2">📆 Off Days Management</h3>
                <p class="text-sm text-gray-600">Manage your leave applications and view your leave balance.</p>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">

                    <!-- Total Off Days -->
                    <a href="{{ route('offdays.index') }}" class="block bg-white rounded-xl shadow-sm border border-gray-100 p-5 text-center hover:shadow-md transition">
                        <p class="text-sm font-bold text-gray-700">Total Off Days</p>
                        <p class="text-5xl font-bold text-indigo-600">{{ $totalOffDays }}</p>
                    </a>

                    <!-- Off Days Taken -->
                    <a href="{{ route('offdays.index', ['filter' => 'approved']) }}" class="block bg-white rounded-xl shadow-sm border border-gray-100 p-5 text-center hover:shadow-md transition">
                        <p class="text-sm font-bold text-gray-700">Off Days Taken</p>
                        <p class="text-5xl font-bold text-emerald-600">{{ $approvedOffDays }}</p>
                    </a>

                    <!-- Pending Requests -->
                    <a href="{{ route('offdays.index', ['filter' => 'pending']) }}" class="block bg-white rounded-xl shadow-sm border border-gray-100 p-5 text-center hover:shadow-md transition">
                        <p class="text-sm font-bold text-gray-700">Pending Requests</p>
                        <p class="text-5xl font-bold text-amber-600">{{ $pendingRequests }}</p>
                    </a>

                </div>

               <!-- Apply Off Day Button / Modal -->
<div x-data="{ openOffDay: false }" class="mt-4">

    <!-- Trigger Button -->
    <button
        @click="openOffDay = true"
        class="w-full bg-green-600 text-white py-2 rounded hover:scale-105 transform transition"
    >
        Apply for an Off Day
    </button>

    <!-- Modal Overlay -->
    <div
        x-show="openOffDay"
        x-cloak
        x-transition
        class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50"
    >
        <!-- Modal Box -->
        <div
            @click.away="openOffDay = false"
            class="bg-white rounded-lg shadow-lg w-full max-w-md p-6"
        >
            <h3 class="text-lg font-semibold mb-4">Apply for Off Day</h3>

            <form action="{{ route('offdays.store') }}" method="POST">
                @csrf

                <div class="mb-4">
                    <label class="block mb-1 font-medium">Start Date</label>
                    <input
                        type="date"
                        name="start_date"
                        class="w-full border px-3 py-2 rounded"
                        required
                    >
                </div>

                <div class="mb-4">
                    <label class="block mb-1 font-medium">End Date</label>
                    <input
                        type="date"
                        name="end_date"
                        class="w-full border px-3 py-2 rounded"
                        required
                    >
                </div>

                <div class="mb-4">
                    <label class="block mb-1 font-medium">
                        Reason (max 100 words)
                    </label>
                    <textarea
                        name="reason"
                        rows="4"
                        maxlength="600"
                        class="w-full border px-3 py-2 rounded"
                        required
                    ></textarea>
                </div>

                <div class="flex justify-end gap-2">
                    <button
                        type="button"
                        @click="openOffDay = false"
                        class="px-4 py-2 rounded bg-gray-300 hover:bg-gray-400"
                    >
                        Cancel
                    </button>

                    <button
                        type="submit"
                        class="px-4 py-2 rounded bg-green-600 text-white hover:bg-green-700"
                    >
                        Apply
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

            </div>

            <!-- Payslip -->
            <div class="bg-white shadow rounded-lg p-6">
                <h3 class="font-semibold mb-4">💰 Payslip</h3>

                <p>View and Download your payslip details below simply by clicking the buttons below.</p>

                 <button class="mt-2 w-full border py-2 rounded hover:bg-gray-100">
                    <a href="{{ route('staff.payroll.index') }}" class="block bg-green-600 text-white p-4 rounded shadow hover:bg-green-700">View 💰Payroll History</a>
                </button>        

                <button class="mt-4 w-full bg-green-600 text-white py-2 rounded hover:bg-green-700">
                    Download Payslip
                </button>

               
            </div>

            <!-- Assigned Items -->
<div class="bg-white shadow rounded-lg p-6 space-y-4">
    <h3 class="font-semibold mb-4">📦 Assigned Items</h3>

    @if($assignedItems->isEmpty())
        <p class="text-gray-600">No items have been assigned to you yet.</p>
    @else
        <div class="overflow-x-auto">
            <table class="w-full table-auto border border-gray-200">
                <thead>
                    <tr class="bg-gray-100">
                        <th class="border px-4 py-2 text-left">Item Name</th>
                        <th class="border px-4 py-2 text-left">Serial Number</th>
                        <th class="border px-4 py-2 text-left">Assigned At</th>
                        <th class="border px-4 py-2 text-left">Status</th>
                        <th class="border px-4 py-2 text-left">Condition / Notes</th>
                        <th class="border px-4 py-2 text-left">Assigned By</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($assignedItems as $item)
                        <tr>
                            <td class="border px-4 py-2">{{ $item->name }}</td>
                            <td class="border px-4 py-2">{{ $item->serial_number ?? '-' }}</td>
                            <td class="border px-4 py-2">{{ \Carbon\Carbon::parse($item->pivot->assigned_at)->format('d M Y h:i A') }}</td>
                            <td class="border px-4 py-2 capitalize">{{ $item->pivot->status }}</td>
                            <td class="border px-4 py-2">{{ $item->pivot->condition_notes ?? '-' }}</td>
                            <td class="border px-4 py-2">
                                {{ \App\Models\User::find($item->pivot->assigned_by)?->name ?? 'Admin' }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>




            {{-- Payroll Chart --}}
<div class="bg-white shadow rounded-lg p-6 mb-6">
    <h3 class="font-semibold mb-4">📊 Salary Trend</h3>
    <canvas id="payrollChart"></canvas>
    <p>coming soon </p>
</div>



        </div>
    </div>

    <!-- ================= FOOTER ================= -->
<footer class="text-center text-gray-500 text-sm py-6 border-t border-gray-800">
    © {{ date('Y') }} {{ config('app.name') }} — Staff Management System
</footer>

<script>
    const labels = @json($chartLabels);
    const data = @json($chartData);

    const ctx = document.getElementById('myChart').getContext('2d');
    const myChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                label: 'Attendance',
                data: data,
                backgroundColor: 'rgba(54, 162, 235, 0.2)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1
            }]
        },
    });
</script>


</x-app-layout>





