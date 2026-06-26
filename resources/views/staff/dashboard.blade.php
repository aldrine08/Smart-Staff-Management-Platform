<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Staff Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-8 bg-gray-50 min-h-screen">

    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">

        {{-- ================= WELCOME BANNER ================= --}}
        @php
            $hour = now()->format('H');

            $greeting = match(true) {
                $hour < 12 => 'Good Morning',
                $hour < 17 => 'Good Afternoon',
                $hour < 20 => 'Good Evening',
                default => 'Good Night'
            };
        @endphp

        <div class="relative overflow-hidden rounded-3xl shadow-xl">

            <div class="bg-gradient-to-r from-indigo-700 via-blue-700 to-cyan-600 p-8 lg:p-10">

                <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between">

                    <div>

                        <h1 class="text-3xl md:text-4xl font-bold text-white">
                            {{ $greeting }}, {{ Auth::user()->name }}
                        </h1>

                        <p class="mt-3 text-blue-100 text-lg">
                            Welcome back to your Employee Self-Service Portal.
                        </p>

                        <p class="mt-2 text-sm text-blue-200">
                            Manage attendance, loans, leave requests, payroll and assigned resources from one place.
                        </p>

                    </div>

                    <div class="mt-6 lg:mt-0">

                        <div class="bg-white/20 backdrop-blur-lg rounded-2xl px-6 py-4 text-center">

                            <p class="text-blue-100 text-sm">
                                Today's Date
                            </p>

                            <h3 class="text-white font-bold text-xl">
                                {{ now()->format('d M Y') }}
                            </h3>

                        </div>

                    </div>

                </div>

            </div>

        </div>

        {{-- ================= QUICK OVERVIEW ================= --}}
        <div>

            <div class="mb-4">
                <h2 class="text-xl font-bold text-gray-800">
                    Dashboard Overview
                </h2>

                <p class="text-sm text-gray-500">
                    Quick summary of your current activities.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">

                {{-- Attendance Status --}}
                <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6">

                    <div class="flex justify-between items-center">

                        <div>
                            <p class="text-sm font-medium text-gray-500">
                                Attendance
                            </p>

                            <h3 class="text-2xl font-bold text-gray-800 mt-2">
                                @if($clockedIn)
                                    Present
                                @else
                                    Pending
                                @endif
                            </h3>
                        </div>

                        <div class="w-14 h-14 rounded-full bg-blue-100 flex items-center justify-center text-2xl">
                            ⏰
                        </div>

                    </div>

                </div>

                {{-- Active Loans --}}
                <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6">

                    <div class="flex justify-between items-center">

                        <div>
                            <p class="text-sm font-medium text-gray-500">
                                Active Loans
                            </p>

                            <h3 class="text-3xl font-bold text-green-600 mt-2">
                                {{ $approvedLoans ?? 0 }}
                            </h3>
                        </div>

                        <div class="w-14 h-14 rounded-full bg-green-100 flex items-center justify-center text-2xl">
                            💰
                        </div>

                    </div>

                </div>

                {{-- Off Days --}}
                <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6">

                    <div class="flex justify-between items-center">

                        <div>
                            <p class="text-sm font-medium text-gray-500">
                                Pending Off Days
                            </p>

                            <h3 class="text-3xl font-bold text-amber-500 mt-2">
                                {{ $pendingRequests }}
                            </h3>
                        </div>

                        <div class="w-14 h-14 rounded-full bg-yellow-100 flex items-center justify-center text-2xl">
                            📅
                        </div>

                    </div>

                </div>

                {{-- Sick Requests --}}
                <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6">

                    <div class="flex justify-between items-center">

                        <div>
                            <p class="text-sm font-medium text-gray-500">
                                Sick Requests
                            </p>

                            <h3 class="text-3xl font-bold text-red-500 mt-2">
                                {{ $pendingSickRequests }}
                            </h3>
                        </div>

                        <div class="w-14 h-14 rounded-full bg-red-100 flex items-center justify-center text-2xl">
                            🏥
                        </div>

                    </div>

                </div>

            </div>

        </div>

        {{-- ================= PROFILE CARD ================= --}}
        <div class="bg-white rounded-3xl shadow-xl border border-gray-100 overflow-hidden">

            <div class="bg-gradient-to-r from-gray-900 to-gray-700 h-28"></div>

            <div class="px-8 pb-8">

                <div class="-mt-14 flex flex-col md:flex-row md:items-center md:justify-between">

                    <div class="flex flex-col md:flex-row md:items-center gap-6">

                        <img
                            src="{{ Auth::user()->avatar ? asset('storage/'.Auth::user()->avatar) : 'https://ui-avatars.com/api/?name='.urlencode(Auth::user()->name) }}"
                            class="w-28 h-28 rounded-full border-4 border-white shadow-lg object-cover">

                        <div>

                            <h2 class="text-2xl font-bold text-white">
                                {{ Auth::user()->name }}
                            </h2>

                            <p class="text-black-300 mt-1">
                                {{ Auth::user()->email }}
                            </p>

                            <span class="inline-flex mt-3 px-4 py-1 rounded-full bg-green-100 text-green-700 text-xs font-bold uppercase tracking-wider">
                                Active Staff Member
                            </span>

                        </div>

                    </div>

                </div>

                <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-6 mt-8">

                    <div>
                        <p class="text-xs uppercase tracking-wider text-gray-400">
                            Operation Unit
                        </p>

                        <p class="font-semibold text-gray-800 mt-1">
                            {{ Auth::user()->unit->name ?? 'Not Assigned' }}
                        </p>
                    </div>

                    <div>
                        <p class="text-xs uppercase tracking-wider text-gray-400">
                            Department
                        </p>

                        <p class="font-semibold text-gray-800 mt-1">
                            {{ Auth::user()->department->name ?? 'Not Assigned' }}
                        </p>
                    </div>

                    <div>
                        <p class="text-xs uppercase tracking-wider text-gray-400">
                            Phone
                        </p>

                        <p class="font-semibold text-gray-800 mt-1">
                            {{ Auth::user()->phone ?? 'Not Provided' }}
                        </p>
                    </div>

                    <div>
                        <p class="text-xs uppercase tracking-wider text-gray-400">
                            Role
                        </p>

                        <p class="font-semibold text-gray-800 mt-1 capitalize">
                            {{ Auth::user()->role }}
                        </p>
                    </div>

                </div>

            </div>

        </div>

            <!-- ================= ATTENDANCE SECTION ================= -->
<div class="bg-white rounded-3xl shadow-xl border border-gray-100 overflow-hidden">

    <!-- Header -->
    <div class="bg-gradient-to-r from-blue-600 to-indigo-700 px-6 py-5">
        <div class="flex items-center justify-between">

            <div>
                <h3 class="text-xl font-bold text-white flex items-center gap-2">
                    ⚡ Attendance Management
                </h3>

                <p class="text-blue-100 text-sm mt-1">
                    Clock in and out to record your daily attendance.
                </p>
            </div>

            <div class="hidden md:block">
                <span class="bg-white/20 text-white px-4 py-2 rounded-full text-sm font-semibold">
                    {{ now()->format('d M Y') }}
                </span>
            </div>

        </div>
    </div>

    <div class="p-6 space-y-6">

        <!-- Attendance Status Card -->
        <div
            class="rounded-2xl border p-5

            @if($clockedIn && $clockedOut)
                bg-green-50 border-green-200
            @elseif($clockedIn)
                bg-blue-50 border-blue-200
            @else
                bg-red-50 border-red-200
            @endif
        ">

            @if($clockedIn && $clockedOut)

                <div class="flex items-start gap-3">

                    <div class="text-3xl">
                        ✅
                    </div>

                    <div>
                        <h4 class="font-bold text-green-800">
                            Attendance Completed
                        </h4>

                        <p class="text-green-700 mt-1">
                            You have successfully completed today's attendance.
                        </p>

                        <div class="mt-3 flex flex-wrap gap-3">

                            <span class="bg-white px-3 py-1 rounded-full text-sm font-medium border border-green-200">
                                ⏰ Clock In:
                                {{ $clockedIn->format('h:i A') }}
                            </span>

                            <span class="bg-white px-3 py-1 rounded-full text-sm font-medium border border-green-200">
                                ⏱️ Clock Out:
                                {{ $clockedOut->format('h:i A') }}
                            </span>

                        </div>
                    </div>

                </div>

            @elseif($clockedIn)

                <div class="flex items-start gap-3">

                    <div class="text-3xl">
                        ⏰
                    </div>

                    <div>

                        <h4 class="font-bold text-blue-800">
                            Clocked In Successfully
                        </h4>

                        <p class="text-blue-700 mt-1">
                            You are currently clocked in. Remember to clock out before leaving.
                        </p>

                        <div class="mt-3">
                            <span class="bg-white px-3 py-1 rounded-full text-sm font-medium border border-blue-200">
                                Clock In Time:
                                {{ $clockedIn->format('h:i A') }}
                            </span>
                        </div>

                    </div>

                </div>

            @else

                <div class="flex items-start gap-3">

                    <div class="text-3xl">
                        ⚠️
                    </div>

                    <div>

                        <h4 class="font-bold text-red-800">
                            Attendance Pending
                        </h4>

                        <p class="text-red-700 mt-1">
                            You have not clocked in today. Please clock in to begin your workday.
                        </p>

                    </div>

                </div>

            @endif

        </div>

        <!-- Action Buttons -->
        <div class="grid md:grid-cols-2 gap-4">

            <!-- CLOCK IN -->
            <form id="clockInForm">
                @csrf

                <button
                    type="submit"

                    class="w-full py-4 rounded-2xl font-semibold text-lg shadow-md transition-all duration-300

                    @if($clockedIn)
                        bg-gray-300 text-gray-600 cursor-not-allowed
                    @else
                        bg-green-600 hover:bg-green-700 text-white hover:shadow-xl hover:-translate-y-1
                    @endif"

                    {{ $clockedIn ? 'disabled' : '' }}
                >

                    @if($clockedIn)
                        ✅ Clocked In
                    @else
                        ⏰ Clock In
                    @endif

                </button>

            </form>

            <!-- CLOCK OUT -->
            <form action="{{ route('attendance.clockout') }}" method="POST">

                @csrf

                <button
                    type="submit"

                    class="w-full py-4 rounded-2xl font-semibold text-lg shadow-md transition-all duration-300

                    @if(!$clockedIn || $clockedOut)
                        bg-gray-300 text-gray-600 cursor-not-allowed
                    @else
                        bg-red-600 hover:bg-red-700 text-white hover:shadow-xl hover:-translate-y-1
                    @endif"

                    {{ (!$clockedIn || $clockedOut) ? 'disabled' : '' }}
                >

                    @if($clockedOut)
                        ✅ Clocked Out
                    @else
                        ⏱️ Clock Out
                    @endif

                </button>

            </form>

        </div>

    </div>

</div>

<!-- ================= LATE REASON MODAL ================= -->
<div id="lateModal"
     class="fixed inset-0 hidden flex items-center justify-center bg-black bg-opacity-60 backdrop-blur-sm z-50">

    <div id="modalBox"
         class="bg-white w-full max-w-md p-6 rounded-3xl shadow-2xl transform scale-90 opacity-0 transition duration-300">

        <div class="text-center mb-4">

            <div class="text-5xl mb-2">
                ⏰
            </div>

            <h3 class="text-2xl font-bold text-gray-800">
                Late Arrival Notice
            </h3>

            <p class="text-gray-600 mt-2">
                Please provide a reason for arriving late today.
            </p>

        </div>

        <form id="lateReasonForm">
            @csrf

            <textarea
                name="reason"
                rows="4"
                class="w-full border border-gray-300 rounded-xl p-3 mb-4 focus:ring-2 focus:ring-green-500 focus:outline-none"
                placeholder="Type your reason here..."
                required></textarea>

            <div class="flex justify-end gap-3">

                <button
                    type="button"
                    onclick="closeModal()"
                    class="px-5 py-2 bg-gray-200 rounded-xl hover:bg-gray-300 transition">

                    Cancel

                </button>

                <button
                    type="submit"
                    class="px-5 py-2 bg-green-600 text-white rounded-xl hover:bg-green-700 transition">

                    Submit Reason

                </button>

            </div>

        </form>

    </div>
</div>
          
       <!-- #region -->

       <!-- Loan Management -->
<div class="bg-white rounded-3xl shadow-xl border border-gray-100 p-6 space-y-4">
    <h3 class="text-lg font-semibold flex items-center gap-2">💰 Loan Management</h3>
    <p class="text-sm text-gray-600">
        Apply for a loan and track your loan requests and status.
    </p>

    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">

        <!-- Total Loans -->
        <a href="{{ route('staff.loans.total') }}" class="block bg-white rounded-2xl shadow-md border border-gray-100 hover:shadow-xl transition duration-300 p-5 text-center hover:shadow-md transition">
            <p class="text-sm font-bold text-gray-700">Total Loans</p>
            <p class="text-5xl font-bold text-indigo-600">
                {{ $totalLoans ?? 0 }}
            </p>
        </a>

        <!-- Approved Loans -->
        <a href="{{ route('staff.loans.active') }}?filter=approved" class="block bg-white rounded-2xl shadow-md border border-gray-100 hover:shadow-xl transition duration-300 p-5 text-center hover:shadow-md transition">
            <p class="text-sm font-bold text-gray-700">Approved Loans</p>
            <p class="text-5xl font-bold text-emerald-600">
                {{ $approvedLoans ?? 0 }}
            </p>
        </a>

        <!-- Pending Loans -->
        <a href="{{ route('staff.loans.pending') }}" class="block bg-white rounded-2xl shadow-md border border-gray-100 hover:shadow-xl transition duration-300 p-5 text-center hover:shadow-md transition">
            <p class="text-sm font-bold text-gray-700">Pending Loans</p>
            <p class="text-5xl font-bold text-amber-600">
                {{ $pendingLoans ?? 0 }}
            </p>
        </a>

    </div>
     <button class="mt-2 w-full border py-2 rounded hover:bg-gray-100">
                    <a href="{{ route('staff.loans.index') }}" class="block bg-green-600 text-white p-4 rounded shadow hover:bg-green-700">
                         💰Apply for Loan</a>
                </button>  
  
</div>






            <!-- Off Days Management -->
            <div class="bg-white rounded-3xl shadow-xl border border-gray-100 p-6 space-y-4">
                <h3 class="text-lg font-semibold flex items-center gap-2">📆 Off Days Management</h3>
                <p class="text-sm text-gray-600">Manage your leave applications and view your leave balance.</p>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">

                    <!-- Total Off Days -->
                    <a href="{{ route('offdays.index') }}" class="block bg-white rounded-2xl shadow-md border border-gray-100 hover:shadow-xl transition duration-300 p-5 text-center hover:shadow-md transition">
                        <p class="text-sm font-bold text-gray-700">Total Off Days</p>
                        <p class="text-5xl font-bold text-indigo-600">{{ $totalOffDays }}</p>
                    </a>

                    <!-- Off Days Taken -->
                    <a href="{{ route('offdays.index', ['filter' => 'approved']) }}" class="block bg-white rounded-2xl shadow-md border border-gray-100 hover:shadow-xl transition duration-300 p-5 text-center hover:shadow-md transition">
                        <p class="text-sm font-bold text-gray-700">Off Days Taken</p>
                        <p class="text-5xl font-bold text-emerald-600">{{ $approvedOffDays }}</p>
                    </a>

                    <!-- Pending Requests -->
                    <a href="{{ route('offdays.index', ['filter' => 'pending']) }}" class="block bg-white rounded-2xl shadow-md border border-gray-100 hover:shadow-xl transition duration-300 p-5 text-center hover:shadow-md transition">
                        <p class="text-sm font-bold text-gray-700">Pending Requests</p>
                        <p class="text-5xl font-bold text-amber-600">{{ $pendingRequests }}</p>
                    </a>

                </div>

               <!-- Apply Off Day Button / Modal -->
<div x-data="{ openOffDay: false }" class="mt-4">

    <!-- Trigger Button -->
    <button
        @click="openOffDay = true"
        class="w-full bg-green-600 hover:bg-green-700 text-white font-semibold py-3 rounded-xl shadow-md transition hover:bg-green-700"
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
            class="bg-white rounded-3xl shadow-2xl border border-gray-100 w-full max-w-md p-6"
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
<div class="bg-white rounded-3xl shadow-xl border border-gray-100 p-6 space-y-4">
    <h3 class="text-lg font-semibold flex items-center gap-2">🏥 Sick Leave Management</h3>
    <p class="text-sm text-gray-600">
        Track your sick leave requests, approvals, and remaining balance.
    </p>

    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">

        <!-- Total Sick Requests -->
        <a href="{{ route('sick-requests.index') }}"
           class="block bg-white rounded-2xl shadow-md border border-gray-100 p-5 text-center hover:shadow-xl transition duration-300">

            <p class="text-sm font-bold text-gray-700">Total Sick Requests</p>
            <p class="text-5xl font-bold text-indigo-600">
                {{ $totalSickRequests }}
            </p>
        </a>

        <!-- Approved Sick Leave -->
        <a href="{{ route('sick-requests.index', ['filter' => 'approved']) }}"
           class="block bg-white rounded-2xl shadow-md border border-gray-100 p-5 text-center hover:shadow-xl transition duration-300">

            <p class="text-sm font-bold text-gray-700">Approved Sick Leave</p>
            <p class="text-5xl font-bold text-emerald-600">
                {{ $approvedSickRequests }}
            </p>
        </a>

        <!-- Pending Sick Requests -->
        <a href="{{ route('sick-requests.index', ['filter' => 'pending']) }}"
           class="block bg-white rounded-2xl shadow-md border border-gray-100 hover:shadow-xl transition duration-300 p-5 text-center hover:shadow-md transition">

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
        class="w-full bg-red-600 hover:bg-red-700 text-white font-semibold py-3 rounded-xl shadow-md transition hover:scale-105 transform transition"
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
            class="bg-white rounded-3xl shadow-2xl border border-gray-100 w-full max-w-md p-6"
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
            <div class="bg-white rounded-3xl shadow-xl border border-gray-100 p-6">
                <h3 class="font-semibold mb-4">💰 Payslip</h3>

                <p>View and Download your payslip details below simply by clicking the buttons below.</p>

                 <button class="mt-2 w-full border py-2 rounded hover:bg-gray-100">
                    <a href="{{ route('staff.payroll.index') }}" class="block bg-green-600 text-white p-4 rounded shadow hover:bg-green-700">View 💰Payroll History</a>
                </button>        

                <button class="mt-4 w-full bg-green-600 hover:bg-green-700 text-white font-semibold py-3 rounded-xl shadow-md transition hover:bg-green-700">
                    Download Payslip
                </button>

               
            </div>

            <!-- Assigned Items -->
<div class="bg-white rounded-3xl shadow-xl border border-gray-100 p-6 space-y-4">
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
<div class="bg-white rounded-3xl shadow-xl border border-gray-100 p-6 mb-6">
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





