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

    <!-- CLOCK IN -->
    <form id="clockInForm">
        @csrf
        <button type="submit"
                class="w-full bg-green-600 text-white py-2 rounded hover:scale-105 transition disabled:opacity-50"
                {{ $clockedIn ? 'disabled' : '' }}>
            ⏰ Clock In
        </button>
    </form>

    <!-- CLOCK OUT -->
    <form action="{{ route('attendance.clockout') }}" method="POST">
        @csrf
        <button type="submit"
                class="w-full bg-red-600 text-white py-2 rounded disabled:opacity-50"
                {{ (!$clockedIn || $clockedOut) ? 'disabled' : '' }}>
            ⏱️ Clock Out
        </button>
    </form>

</div>

<!-- 🔥 MOVE MODAL HERE (OUTSIDE GRID) -->
<div id="lateModal"
     class="fixed inset-0 hidden flex items-center justify-center bg-black bg-opacity-50 z-50">

    <div id="modalBox"
         class="bg-white w-full max-w-md p-6 rounded-2xl shadow-2xl transform scale-90 opacity-0 transition duration-300">

        <h3 class="text-xl font-bold text-gray-800 mb-2">
            ⏰ You're Late
        </h3>

        <p class="text-gray-600 mb-4">
            Please provide a reason for arriving late.
        </p>

        <form id="lateReasonForm">
            @csrf

            <textarea name="reason"
                      class="w-full border rounded-lg p-3 mb-4 focus:ring-2 focus:ring-green-500 focus:outline-none"
                      placeholder="Type your reason here..."
                      required></textarea>

            <div class="flex justify-end gap-2">
                <button type="button"
                        onclick="closeModal()"
                        class="px-4 py-2 bg-gray-300 rounded-lg hover:bg-gray-400 transition">
                    Cancel
                </button>

                <button type="submit"
                        class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition">
                    Submit
                </button>
            </div>
        </form>

    </div>
</div>
            



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


            <!-- Sick Leave Management -->
<div class="bg-white shadow rounded-lg p-6 space-y-4">
    <h3 class="text-lg font-semibold flex items-center gap-2">🏥 Sick Leave Management</h3>
    <p class="text-sm text-gray-600">
        Track your sick leave requests, approvals, and remaining balance.
    </p>

    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">

        <!-- Total Sick Requests -->
        <a href="{{ route('sick-requests.index') }}"
           class="block bg-white rounded-xl shadow-sm border border-gray-100 p-5 text-center hover:shadow-md transition">

            <p class="text-sm font-bold text-gray-700">Total Sick Requests</p>
            <p class="text-5xl font-bold text-indigo-600">
                {{ $totalSickRequests }}
            </p>
        </a>

        <!-- Approved Sick Leave -->
        <a href="{{ route('sick-requests.index', ['filter' => 'approved']) }}"
           class="block bg-white rounded-xl shadow-sm border border-gray-100 p-5 text-center hover:shadow-md transition">

            <p class="text-sm font-bold text-gray-700">Approved Sick Leave</p>
            <p class="text-5xl font-bold text-emerald-600">
                {{ $approvedSickRequests }}
            </p>
        </a>

        <!-- Pending Sick Requests -->
        <a href="{{ route('sick-requests.index', ['filter' => 'pending']) }}"
           class="block bg-white rounded-xl shadow-sm border border-gray-100 p-5 text-center hover:shadow-md transition">

            <p class="text-sm font-bold text-gray-700">Pending Requests</p>
            <p class="text-5xl font-bold text-amber-600">
                {{ $pendingSickRequests }}
            </p>
        </a>

    </div>
</div>



           <!-- Sick Request Button / Modal -->
<div x-data="{ openSick: false }" class="mt-4">

    <!-- Trigger Button -->
    <button
        @click="openSick = true"
        class="w-full bg-red-600 text-white py-2 rounded hover:scale-105 transform transition"
    >
        Apply for Sick Leave
    </button>

    <!-- Modal Overlay -->
    <div
        x-show="openSick"
        x-cloak
        x-transition
        class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50"
    >
        <!-- Modal Box -->
        <div
            @click.away="openSick = false"
            class="bg-white rounded-lg shadow-lg w-full max-w-md p-6"
        >
            <h3 class="text-lg font-semibold mb-4">🏥 Sick Request</h3>

            <!-- IMPORTANT: enctype added for file upload -->
            <form action="{{ route('sick-requests.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <!-- Start Date -->
                <div class="mb-4">
                    <label class="block mb-1 font-medium">Start Date</label>
                    <input
                        type="date"
                        name="start_date"
                        class="w-full border px-3 py-2 rounded"
                        required
                    >
                </div>

                <!-- End Date -->
                <div class="mb-4">
                    <label class="block mb-1 font-medium">End Date</label>
                    <input
                        type="date"
                        name="end_date"
                        class="w-full border px-3 py-2 rounded"
                        required
                    >
                </div>

                <!-- Reason -->
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

                <!-- ✅ FILE UPLOAD (NEW PART - STEP 3) -->
                <div class="mb-4">
                    <label class="block mb-1 font-medium">
                        Upload Sick Note (PDF / Image)
                    </label>

                    <input
                        type="file"
                        name="sick_note"
                        accept=".jpg,.jpeg,.png,.pdf"
                        class="w-full border px-3 py-2 rounded"
                        required
                    >

                    <small class="text-gray-500 text-xs">
                        Upload a doctor or hospital sick note (JPG, PNG, PDF)
                    </small>
                </div>

                <!-- Buttons -->
                <div class="flex justify-end gap-2">
                    <button
                        type="button"
                        @click="openSick = false"
                        class="px-4 py-2 rounded bg-gray-300 hover:bg-gray-400"
                    >
                        Cancel
                    </button>

                    <button
                        type="submit"
                        class="px-4 py-2 rounded bg-red-600 text-white hover:bg-red-700"
                    >
                        Submit
                    </button>
                </div>

            </form>
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

<div id="toast-container"
     class="fixed inset-0 flex items-center justify-center z-50 pointer-events-none">
</div>

<script>

// ✅ TOAST SYSTEM
function showToast(message, type = 'success') {
    const container = document.getElementById('toast-container');

    const toast = document.createElement('div');
    toast.className = `toast toast-${type}`;

    toast.innerHTML = `
        <span>${getIcon(type)}</span>
        <span>${message}</span>
    `;

    const wrapper = document.createElement('div');
wrapper.className = "flex flex-col items-center space-y-3";

wrapper.appendChild(toast);
container.appendChild(wrapper);

    setTimeout(() => wrapper.remove(), 4000);
}

function getIcon(type) {
    if (type === 'success') return '✅';
    if (type === 'error') return '❌';
    if (type === 'warning') return '⚠️';
    return 'ℹ️';
}


// CLOCK IN HANDLER WITH GPS
document.getElementById('clockInForm').addEventListener('submit', function(e) {
    e.preventDefault();

    if (!navigator.geolocation) {
        showToast("Geolocation is not supported by your browser.", "error");
        return;
    }

    navigator.geolocation.getCurrentPosition(
        function(position) {
            const lat = position.coords.latitude;
            const lng = position.coords.longitude;

            fetch("{{ route('attendance.clockin') }}", {
                method: "POST",
                headers: {
                    "X-CSRF-TOKEN": "{{ csrf_token() }}",
                    "Content-Type": "application/json",
                    "Accept": "application/json"
                },
                body: JSON.stringify({
                    latitude: lat,
                    longitude: lng
                })
            })
            .then(res => res.json())
            .then(data => {
                if (data.error) {
                    showToast(data.error, "error");
                    return;
                }

                if (data.status === 'late') {
                    openModal();
                } else {
                    showToast(data.message, "success");
                    setTimeout(() => location.reload(), 1500);
                }
            })
            .catch(error => {
                showToast("Something went wrong. Please try again.", "error");
                console.error(error);
            });
        },
        function(error) {
            showToast("Please enable location services to clock in.", "warning");
        }
    );
});


// SUBMIT LATE REASON
document.getElementById('lateReasonForm').addEventListener('submit', function(e) {
    e.preventDefault();

    const reason = this.reason.value;

    fetch("{{ route('attendance.saveLateReason') }}", {
        method: "POST",
        headers: {
            "X-CSRF-TOKEN": "{{ csrf_token() }}",
            "Content-Type": "application/json",
            "Accept": "application/json"
        },
        body: JSON.stringify({ reason: reason })
    })
    .then(res => res.json())
    .then(data => {
        showToast(data.message, "success");
        closeModal();
        setTimeout(() => location.reload(), 1500);
    })
    .catch(error => {
        showToast("Failed to submit. Try again.", "error");
        console.error(error);
    });
});


// OPEN MODAL
function openModal() {
    const modal = document.getElementById('lateModal');
    const box = document.getElementById('modalBox');

    modal.classList.remove('hidden');

    setTimeout(() => {
        box.classList.remove('scale-90', 'opacity-0');
        box.classList.add('scale-100', 'opacity-100');
    }, 10);
}


// CLOSE MODAL
function closeModal() {
    const modal = document.getElementById('lateModal');
    const box = document.getElementById('modalBox');

    box.classList.remove('scale-100', 'opacity-100');
    box.classList.add('scale-90', 'opacity-0');

    setTimeout(() => {
        modal.classList.add('hidden');
    }, 200);
}

</script>

<style>
.toast {
    pointer-events: auto;
    margin-top: 10px;
    display: flex;
    align-items: center;
    gap: 10px;
    min-width: 260px;
    max-width: 320px;
    padding: 14px 16px;
    border-radius: 14px;
    color: #fff;
    font-size: 14px;
    font-weight: 500;
    box-shadow: 0 10px 30px rgba(0,0,0,0.15);
    animation: slideIn 0.35s ease, fadeOut 0.5s ease 3.5s forwards;
}

.toast-success { background: linear-gradient(to right, #16a34a, #22c55e); }
.toast-error { background: linear-gradient(to right, #dc2626, #ef4444); }
.toast-warning { background: linear-gradient(to right, #f59e0b, #fbbf24); }

@keyframes slideIn {
    from { transform: translateX(120%); opacity: 0; }
    to { transform: translateX(0); opacity: 1; }
}

@keyframes fadeOut {
    to { opacity: 0; transform: translateX(120%); }
}
</style>

</x-app-layout>





