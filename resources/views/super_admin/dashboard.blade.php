<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-2xl font-bold text-gray-800">
                Super Admin Dashboard
            </h2>

            <span class="text-sm text-gray-500">
                System Overview
            </span>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- TOP STATS --}}
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">

                <div class="bg-white rounded-xl shadow p-6 border-l-4 border-indigo-500">
                    <p class="text-sm text-gray-500">Total Admins</p>
                    <p class="text-3xl font-bold text-gray-800">
                        {{ $admins->count() }}
                    </p>
                </div>

                <div class="bg-white rounded-xl shadow p-6 border-l-4 border-green-500">
                    <p class="text-sm text-gray-500">Total Companies</p>
                    <p class="text-3xl font-bold text-gray-800">
                        {{ $admins->sum(fn($a) => $a->units->count()) }}
                    </p>
                </div>

                <div class="bg-white rounded-xl shadow p-6 border-l-4 border-blue-500">
                    <p class="text-sm text-gray-500">Total Staff</p>
                    <p class="text-3xl font-bold text-gray-800">
                        {{ $admins->sum(fn($a) => $a->units->sum(fn($u) => $u->staff->count())) }}
                    </p>
                </div>

                <div class="bg-white rounded-xl shadow p-6 border-l-4 border-orange-500">
                    <p class="text-sm text-gray-500">
                                    Active Users (Now)
                    </p>
                        
                    <p class="text-3xl font-bold text-gray-800">
                            
                        {{ $activeUsers->count() }}
                    
                    </p>
                                   
                </div>

            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6">

    {{-- Today's Logins --}}
    <div class="bg-white rounded-2xl shadow-sm hover:shadow-md transition-all border border-gray-100 p-6">

        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500">
                    Today's Logins
                </p>

                <h3 class="text-3xl font-bold text-gray-900 mt-2">
                    {{ $todayLogins }}
                </h3>
            </div>

            <span class="px-3 py-1 text-xs rounded-full bg-green-100 text-green-700">
                +12%
            </span>
        </div>

        <canvas id="todaySparkline" height="50"></canvas>

    </div>

    {{-- Week --}}
    <div class="bg-white rounded-2xl shadow-sm hover:shadow-md transition-all border border-gray-100 p-6">

        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500">
                    Weekly Logins
                </p>

                <h3 class="text-3xl font-bold text-gray-900 mt-2">
                    {{ $weekLogins }}
                </h3>
            </div>

            <span class="px-3 py-1 text-xs rounded-full bg-blue-100 text-blue-700">
                Weekly
            </span>
        </div>

        <canvas id="weekSparkline" height="50"></canvas>

    </div>

    {{-- Month --}}
    <div class="bg-white rounded-2xl shadow-sm hover:shadow-md transition-all border border-gray-100 p-6">

        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500">
                    Monthly Logins
                </p>

                <h3 class="text-3xl font-bold text-gray-900 mt-2">
                    {{ $monthLogins }}
                </h3>
            </div>

            <span class="px-3 py-1 text-xs rounded-full bg-purple-100 text-purple-700">
                Monthly
            </span>
        </div>

        <canvas id="monthSparkline" height="50"></canvas>

    </div>

    {{-- Total Users --}}
    <div class="bg-white rounded-2xl shadow-sm hover:shadow-md transition-all border border-gray-100 p-6">

        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500">
                    Total Users
                </p>

                <h3 class="text-3xl font-bold text-gray-900 mt-2">
                    {{ $totalUsers }}
                </h3>
            </div>

            <span class="px-3 py-1 text-xs rounded-full bg-orange-100 text-orange-700">
                System
            </span>
        </div>

        <canvas id="userSparkline" height="50"></canvas>

    </div>

</div>

<div class="grid grid-cols-1 xl:grid-cols-3 gap-6 mt-6">

    {{-- Main Chart --}}
    <div class="xl:col-span-2 bg-white rounded-2xl shadow-sm hover:shadow-md transition-all border border-gray-100 p-6">

        <div class="flex justify-between items-center mb-6">

        

            <div>
                <h3 class="text-xl font-bold text-gray-900">
                    Login Activity Analytics
                </h3>

                <p class="text-sm text-gray-500">
                    User logins over the last 7 days
                </p>
            </div>

            <select class="border rounded-lg px-3 py-2 text-sm">
                <option>Last 7 Days</option>
                <option>Last 30 Days</option>
                <option>Last 90 Days</option>
            </select>


            <div class="flex items-center gap-3 mb-4">

    <div class="w-3 h-3 bg-indigo-500 rounded-full"></div>

    <span class="text-sm text-gray-500">
        Real-time login activity
    </span>

</div>

        </div>

        <canvas id="loginActivityChart" height="120"></canvas>

    </div>

    {{-- Side Analytics --}}
    <div class="bg-white rounded-2xl shadow-sm hover:shadow-md transition-all border border-gray-100 p-6">

        <h3 class="text-lg font-bold mb-5">
            Top Active Users
        </h3>

        @foreach($topUsers as $user)

        <div class="flex items-center justify-between py-3 border-b">

            <div>
                <p class="font-medium">
                    {{ $user->user->name }}
                </p>

                <p class="text-xs text-gray-500">
                    {{ ucfirst($user->user->role) }}
                </p>
            </div>

            <span class="font-bold text-indigo-600">
                {{ $user->total }}
            </span>

        </div>

        @endforeach

    </div>

</div>

            

            <div class="flex justify-between items-center">            
            <a href="{{ route('super_admin.admins.create') }}"
               class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700">
                + Add Admin
            </a>
        </div>

        <div class="bg-white rounded-xl shadow p-6">

    <div class="flex justify-between items-center mb-4">
        <h3 class="text-xl font-semibold text-gray-800">
            Active Users (Currently Online)
        </h3>

        <span class="bg-green-100 text-green-700 px-2 py-1 rounded-full text-xs">
            {{ $activeUsers->count() }} online
        </span>
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-full text-sm">

            <thead>
                <tr class="border-b">
                    <th class="text-left py-3">User</th>
                    <th class="text-left py-3">Role</th>
                    <th class="text-left py-3">Phone</th>
                    <th class="text-left py-3">IP Address</th>
                    <th class="text-left py-3">Last Active</th>
                </tr>
            </thead>

            <tbody>

                @forelse($activeUsers as $user)

                    <tr class="border-b hover:bg-gray-50">

                        <td class="py-3">

                            <div class="font-semibold">
                                {{ $user->name }}
                            </div>

                            <div class="text-gray-500 text-xs">
                                {{ $user->email }}
                            </div>

                            <div class="text-gray-400 text-xs truncate max-w-md">
                                {{ $user->user_agent }}
                            </div>

                        </td>

                        <td>
                            {{ ucfirst($user->role) }}
                        </td>

                        <td>
                            {{ $user->phone ?? 'N/A' }}
                        </td>

                        <td>
                            {{ $user->ip_address }}
                        </td>

                        <td>
                            {{ Carbon\Carbon::createFromTimestamp($user->last_activity)->diffForHumans() }}
                        </td>

                        <td>
                            <button
                                class="bg-indigo-600 text-white px-3 py-1 rounded"
                                    onclick="viewSession('{{ $user->session_id }}')">
                                        View
                            </button>
                        </td>

                    </tr>

                    @if($user->id !== auth()->id())
                        <!-- <td>
                            <form action="{{ route('super_admin.session.terminate', $user->session_id) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="bg-red-500 text-white px-3 py-1 rounded" onclick="return confirm('Are you sure you want to terminate this session?')">
                                    Terminate
                                </button>
                            </form>
                        </td>
                    @endif -->

                @empty

                    <tr>
                        <td colspan="5" class="text-center py-5 text-gray-500">
                            No active users found
                        </td>
                    </tr>

                @endforelse

            </tbody>

        </table>
    </div>

</div>


<div class="bg-white rounded-xl shadow p-6">

    <h3 class="text-xl font-semibold mb-4">
        Login History
    </h3>
    <form method="GET"
      action="{{ route('super_admin.dashboard') }}"
      class="mb-4">

    <div class="grid grid-cols-1 md:grid-cols-4 gap-3">

        <input
            type="text"
            name="search"
            value="{{ request('search') }}"
            placeholder="Search name, email or phone..."
            class="border rounded-lg px-3 py-2"
        >

        <input
            type="date"
            name="from"
            value="{{ request('from') }}"
            class="border rounded-lg px-3 py-2"
        >

        <input
            type="date"
            name="to"
            value="{{ request('to') }}"
            class="border rounded-lg px-3 py-2"
        >

        <button
            type="submit"
            class="bg-indigo-600 text-white rounded-lg px-4 py-2">
            Filter
        </button>

    </div>

</form>

    <div class="overflow-x-auto">

        <table class="min-w-full text-sm">

            <thead>
<tr class="border-b">
    <th class="py-3 text-left">User</th>
    <th class="py-3 text-left">Login Time</th>
    <th class="py-3 text-left">Duration</th>
    <th class="py-3 text-left">Status</th>
</tr>
</thead>

            <tbody>

@foreach($loginLogs as $log)

<tr class="border-b hover:bg-gray-50">

    <td class="py-3">
        <div class="font-medium">
            {{ $log->user->name }}
        </div>

        <div class="text-xs text-gray-500">
            {{ $log->user->email }}
        </div>
    </td>

    <td>
        {{ $log->login_at->format('d M Y H:i') }}
    </td>

    <td>
        @if($log->logout_at)
            {{ $log->login_at->diffForHumans($log->logout_at, true) }}
        @else
            Active Session
        @endif
    </td>

    <td>
        @if($log->logout_at)

            <span class="bg-red-100 text-red-700 px-2 py-1 rounded-full text-xs">
                Offline
            </span>

        @else

            <span class="bg-green-100 text-green-700 px-2 py-1 rounded-full text-xs">
                Online
            </span>

        @endif
    </td>

</tr>

@endforeach

</tbody>
            

        </table>

    </div>

    <div class="mt-4">
    {{ $loginLogs->links() }}
</div>

</div>

            {{-- SECTION TITLE --}}
            <div class="flex items-center justify-between mt-6">
                <h3 class="text-xl font-semibold text-gray-800">
                    Admin Overview
                </h3>

                <div class="text-sm text-gray-500">
                    Click an admin to manage details
                </div>
            </div>

            {{-- ADMIN CARDS --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

                @foreach($admins as $admin)
                    <div class="bg-white rounded-xl shadow hover:shadow-lg transition p-5 border border-gray-100">

                        {{-- Header --}}
                        <div class="flex items-center justify-between mb-3">
                            <div>
                                <h4 class="text-lg font-bold text-gray-800">
                                    {{ $admin->name }}
                                </h4>
                                <p class="text-sm text-gray-500">
                                    {{ $admin->email }}
                                </p>
                            </div>

                            <span class="px-2 py-1 text-xs rounded-full bg-indigo-100 text-indigo-600">
                                Admin
                            </span>
                        </div>

                        {{-- Stats --}}
                        <div class="space-y-2 text-sm text-gray-600">
                            <div class="flex justify-between">
                                <span>Companies</span>
                                <span class="font-semibold text-gray-800">
                                    {{ $admin->units->count() }}
                                </span>
                            </div>

                            <div class="flex justify-between">
                                <span>Staff</span>
                                <span class="font-semibold text-gray-800">
                                    {{ $admin->units->sum(fn($u) => $u->staff->count()) }}
                                </span>
                            </div>

                            <div class="flex justify-between">
                                <span>is_active</span>
                                <span class="font-semibold text-gray-800">
                                    {{ $admin->is_active ? 'Yes' : 'No' }}
                                </span>
                            </div>
                        </div>

                        {{-- Actions --}}
<div class="mt-4 grid grid-cols-2 gap-2">

    {{-- VIEW --}}
    <a href="{{ route('super_admin.admins.show', $admin->id) }}"
       class="text-center px-3 py-2 text-sm rounded-lg bg-indigo-600 text-white hover:bg-indigo-700 transition">
        View Admin
    </a>

    {{-- EDIT --}}
    <a href="{{ route('super_admin.admins.index', ['admin_id' => $admin->id]) }}"
       class="text-center px-3 py-2 text-sm rounded-lg border border-gray-300 hover:bg-gray-50 transition">
        Manage
    </a>

    {{-- DEACTIVATE / ACTIVATE --}}
    <form method="POST" action="{{ route('super_admin.admins.deactivate', $admin->id) }}">
        @csrf
        <button class="w-full px-3 py-2 text-sm rounded-lg bg-yellow-500 text-white hover:bg-yellow-600 transition">
            Deactivate
        </button>
    </form>

    <form method="POST" action="{{ route('super_admin.admins.activate', $admin->id) }}">
        @csrf
        <button class="w-full px-3 py-2 text-sm rounded-lg bg-green-500 text-white hover:bg-green-600 transition">
            Activate
        </button>
    </form>

    {{-- DELETE --}}
    <form method="POST" action="{{ route('super_admin.admins.destroy', $admin->id) }}"
          onsubmit="return confirm('Are you sure you want to delete this admin?')">
        @csrf
        @method('DELETE')
        <button class="w-full px-3 py-2 text-sm rounded-lg bg-red-600 text-white hover:bg-red-700 transition">
            Delete
        </button>
    </form>

</div>

                    </div>
                @endforeach


                <div class="bg-white rounded-xl shadow hover:shadow-lg transition border-2 border-dashed border-indigo-200">

    <a href="{{ route('super_admin.admins.create') }}"
       class="flex flex-col items-center justify-center h-full min-h-[250px] text-center p-6 group">

        <div class="w-14 h-14 rounded-full bg-indigo-100 flex items-center justify-center mb-4 group-hover:bg-indigo-200 transition">

            <svg xmlns="http://www.w3.org/2000/svg"
                 class="h-8 w-8 text-indigo-600"
                 fill="none"
                 viewBox="0 0 24 24"
                 stroke="currentColor">

                <path stroke-linecap="round"
                      stroke-linejoin="round"
                      stroke-width="2"
                      d="M12 4v16m8-8H4" />

            </svg>

        </div>

        <h3 class="text-lg font-semibold text-indigo-600">
            Add Admin
        </h3>

        <p class="text-sm text-gray-500 mt-1">
            Create a new Admin account to manage companies and staff.
        </p>

    </a>

</div>

            </div>

        </div>


        



    </div>
</x-app-layout>


@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>

const loginChart = new Chart(
    document.getElementById('loginActivityChart'),
    {
        type: 'line',
        data: {
            labels: @json($dailyLogins->pluck('day')),
            datasets: [{
                label: 'Logins',
                data: @json($dailyLogins->pluck('total')),
                tension: 0.4,
                fill: true,
                borderWidth: 3
            }]
        },
        options: {
            responsive: true,

            plugins: {
                legend: {
                    display: false
                }
            },

            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    }
);

</script>
@endpush