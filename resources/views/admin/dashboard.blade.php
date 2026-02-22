<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            Admin Dashboard
        </h2>
    </x-slot>

    @php
        $hour = now()->format('H');
        $greeting = $hour < 12 ? 'Good Morning' : ($hour < 18 ? 'Good Afternoon' : 'Good Evening');
        $adminName = auth()->user()->name;
    @endphp

    <!-- Greeting -->
    <div class="mb-6 text-gray-700 text-lg font-medium">
        {{ $greeting }}, {{ $adminName }}!
    </div>

    <!-- Add New Staff Button -->
    <!-- <div class="flex justify-start mb-4">
        <a href="{{ route('admin.staff.create') }}" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
            Add New Staff
        </a>
    </div> -->

    <a href="{{ route('admin.staff.all') }}"
       class="bg-white rounded-lg shadow p-6 hover:shadow-xl transition duration-300 block">

        <div class="flex items-center justify-between">
            <div>
                <h3 class="text-lg font-semibold text-gray-800">
                    All Staff
                </h3>
                <p class="text-sm text-gray-500">
                    View all staff members
                </p>
            </div>

            <div class="text-blue-600 text-3xl font-bold">
                {{ $staffCount }}
            </div>
        </div>
    </a>

    <!-- Clock-In Settings -->
    <div x-data="{ clockInSettingsOpen: false }">
        <!-- <button 
            @click="clockInSettingsOpen = true"
            class="bg-yellow-600 text-white px-4 py-2 rounded hover:bg-yellow-700">
            Clock-In Settings
        </button> -->

        <div x-show="clockInSettingsOpen" x-transition class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center">
            <div @click.away="clockInSettingsOpen = false" class="bg-white rounded-lg p-6 w-full max-w-md">
                <h3 class="text-lg font-semibold mb-4">Clock-In Settings</h3>
                <form action="{{ route('admin.clockin-settings.update') }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="mb-4">
                        <label class="block font-medium mb-1">Clock-In Start Time</label>
                        <input type="time" name="start_time" value="{{ $setting->start_time ?? '08:10' }}" class="border p-2 rounded w-full">
                    </div>
                    <div class="mb-4">
                        <label class="block font-medium mb-1">Working Days</label>
                        <select name="working_days[]" multiple class="border p-2 rounded w-full">
                            @foreach(['Mon','Tue','Wed','Thu','Fri','Sat','Sun'] as $day)
                                <option value="{{ $day }}" {{ in_array($day, $setting->working_days ?? []) ? 'selected' : '' }}>
                                    {{ $day }}
                                </option>
                            @endforeach
                        </select>
                        <p class="text-sm text-gray-500 mt-1">Hold Ctrl/Cmd to select multiple days.</p>
                    </div>
                    <button type="submit" class="bg-green-600 text-white py-2 px-4 rounded">Save</button>
                </form>
            </div>
        </div>
    </div>

    <!-- Add Department -->
    <div x-data="{ addDepartmentOpen: false }">
        <!-- <button
            @click="addDepartmentOpen = true"
            class="bg-purple-600 text-white px-4 py-2 rounded hover:bg-purple-700">
            Add Department
        </button> -->

        <div x-show="addDepartmentOpen" x-transition class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
            <div @click.away="addDepartmentOpen = false" class="bg-white rounded-lg shadow-lg w-full max-w-sm p-6">
                <h3 class="text-lg font-semibold mb-4 text-gray-800">Add New Department</h3>
                <form method="POST" action="{{ route('admin.departments.store') }}">
                    @csrf
                    <div class="mb-4">
                        <label class="block text-gray-700 mb-1">Department Name</label>
                        <input type="text" name="name" class="w-full border rounded px-3 py-2" placeholder="e.g. Human Resource" required>
                    </div>
                    <div class="flex justify-end space-x-2">
                        <button type="button" @click="addDepartmentOpen = false" class="px-4 py-2 rounded bg-gray-300 hover:bg-gray-400">Cancel</button>
                        <button type="submit" class="px-4 py-2 rounded bg-purple-600 text-white hover:bg-purple-700">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Add Unit -->
    <div x-data="{ addUnitOpen: false }" class="py-6">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8 space-y-6">
            <div class="flex space-x-2 mb-4">
                <button 
                    @click="addUnitOpen = true"
                    class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                    Add New Unit
                </button>
            </div>

            <div x-show="addUnitOpen" x-transition class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
                <div @click.away="addUnitOpen = false" class="bg-white rounded-lg shadow-lg w-full max-w-sm p-6">
                    <h3 class="text-lg font-semibold mb-4">Add New Unit</h3>
                    <form method="POST" action="{{ route('admin.units.store') }}">
                        @csrf
                        <div class="mb-4">
                            <label class="block text-gray-700">Unit Name</label>
                            <input type="text" name="name" class="w-full border rounded px-3 py-2 mt-1" placeholder="e.g. Kenya" required>
                        </div>
                        <div class="flex justify-end space-x-2">
                            <button type="button" @click="addUnitOpen = false" class="px-4 py-2 rounded bg-gray-300 hover:bg-gray-400">Cancel</button>
                            <button type="submit" class="px-4 py-2 rounded bg-blue-600 text-white hover:bg-blue-700">Save</button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="p-4 bg-white rounded-lg shadow hover:shadow-xl transition duration-300">
                    <p class="text-gray-500">Total Staff</p>
                    <p class="text-2xl font-bold">{{ $totalStaff }}</p>
                </div>
                <div class="p-4 bg-white rounded-lg shadow hover:shadow-xl transition duration-300">
                    <p class="text-gray-500">Clocked In Today</p>
                    <p class="text-2xl font-bold">{{ $clockedInUsers->count() }}</p>
                </div>
                <div class="p-4 bg-white rounded-lg shadow hover:shadow-xl transition duration-300">
                    <p class="text-gray-500">Clocked Out Today</p>
                    <p class="text-2xl font-bold">{{ $clockedOutUsers->count() }}</p>
                </div>
            </div>

            <!-- Clocked In Users -->
            <!-- <div class="p-4 bg-white rounded-lg shadow">
                <h3 class="mb-2 text-lg font-semibold text-gray-700">Staff Clocked In Today</h3>
                @if($clockedInUsers->isEmpty())
                    <p class="text-gray-500">No staff have clocked in today.</p>
                @else
                    <ul class="divide-y divide-gray-200">
                        @foreach($clockedInUsers as $attendance)
                            @if($attendance->user)
                                <li class="flex items-center py-2">
                                    <img 
                                        src="{{ $attendance->user->avatar ? asset('storage/'.$attendance->user->avatar) : 'https://ui-avatars.com/api/?name='.urlencode($attendance->user->name) }}" 
                                        class="w-8 h-8 rounded-full object-cover mr-3"
                                        alt="{{ $attendance->user->name }}"
                                    >
                                    <div class="flex-1">
                                        <p class="font-medium text-gray-800">{{ $attendance->user->name }}</p>
                                        <p class="text-sm text-gray-500">Clock In: {{ \Carbon\Carbon::parse($attendance->clock_in)->format('H:i:s') }}</p>
                                    </div>
                                </li>
                            @endif
                        @endforeach
                    </ul>
                @endif
            </div> -->
        </div>
    </div>

    <h3 class="text-lg font-semibold mb-4">Units</h3>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        @foreach($units as $unit)
            <a href="{{ route('admin.dashboard.unit', $unit) }}"
               class="block bg-white rounded-lg shadow hover:shadow-xl transition duration-300 p-4 border-l-4 border-blue-600">

                <h4 class="text-lg font-semibold text-gray-800">
                    {{ $unit->name }}
                </h4>

                <p class="text-sm text-gray-500 mt-1">
                    {{ $unit->staff_count }} Staff
                </p>
            </a>
        @endforeach
    </div>

    <!-- ================= FOOTER ================= -->
<footer class="text-center text-gray-500 text-sm py-6 border-t border-gray-800">
    © {{ date('Y') }} {{ config('app.name') }} — Staff Management System
</footer>

    <script src="//unpkg.com/alpinejs" defer></script>
</x-app-layout>
