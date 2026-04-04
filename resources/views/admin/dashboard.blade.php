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

    <div class="mb-6 text-gray-700 text-lg font-medium">
        {{ $greeting }}, {{ $adminName }}!
    </div>

    <!-- ANALYTICS -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
        <div class="bg-white p-4 rounded-lg shadow">
            <h4 class="text-sm text-gray-500 mb-2">Number of Employees</h4>
            <canvas id="employeesChart"></canvas>
            <div class="text-sm mt-2">
                <p class="text-green-600">Active: {{ $activeEmployees ?? 0 }}</p>
                <p class="text-red-500">Inactive: {{ $inactiveEmployees ?? 0 }}</p>
            </div>
        </div>

        <div class="bg-white p-4 rounded-lg shadow">
            <h4 class="text-sm text-gray-500 mb-2">Gender</h4>
            <canvas id="genderChart"></canvas>
            <div class="text-sm mt-2">
                <p>Male: {{ $maleCount ?? 0 }}</p>
                <p>Female: {{ $femaleCount ?? 0 }}</p>
            </div>
        </div>

        <div class="bg-white p-4 rounded-lg shadow">
            <h4 class="text-sm text-gray-500 mb-2">Age Groups</h4>
            <canvas id="ageChart"></canvas>
        </div>

        <div class="bg-white p-4 rounded-lg shadow">
            <h4 class="text-sm text-gray-500 mb-2">Years of Service</h4>
            <canvas id="serviceChart"></canvas>
        </div>
    </div>

    <!-- TODAY ATTENDANCE + ALL STAFF SIDE BY SIDE -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">

        <!-- LEFT: TODAY ATTENDANCE -->
        <div class="bg-white p-6 rounded-lg shadow flex flex-col justify-between">
            <div>
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold">Today's Attendance</h3>
                    <span class="text-sm text-gray-500">Live</span>
                </div>

                <div class="flex items-center justify-between">
                    <div class="w-1/2">
                        <canvas id="attendanceChart"></canvas>
                    </div>

                    <div class="text-sm space-y-2">
                        <p>🟢 Present: {{ $presentCount }}</p>
                        <p>🔴 Absent: {{ $absentCount }}</p>
                        <p>🟡 Late: {{ $lateCount }}</p>
                        <p>⚫ Missed Clock-Out: {{ $missedClockOut }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- RIGHT: ALL STAFF -->
        <a href="{{ route('admin.staff.all') }}"
           class="bg-white rounded-lg shadow p-6 hover:shadow-xl transition duration-300 flex items-center justify-between">
            
            <div>
                <h3 class="text-lg font-semibold text-gray-800">All Staff</h3>
                <p class="text-sm text-gray-500">
                      Access all employee records, manage roles, and monitor staff engagement across units.
                </p>
            </div>

            <div class="text-blue-600 text-4xl font-bold">
                {{ $staffCount }}
            </div>

        </a>

    </div>

    <!-- STATS -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-6">
        <div class="p-4 bg-white rounded-lg shadow">
            <p>Total Staff</p>
            <p class="text-2xl font-bold counter" data-target="{{ $totalStaff }}">0</p>
        </div>

        <div class="p-4 bg-white rounded-lg shadow">
            <p>Clocked In</p>
            <p class="text-2xl font-bold">{{ $clockedInUsers->count() }}</p>
        </div>

        <div class="p-4 bg-white rounded-lg shadow">
            <p>Clocked Out</p>
            <p class="text-2xl font-bold">{{ $clockedOutUsers->count() }}</p>
        </div>
    </div>

    <!-- UNITS -->
    <h3 class="text-lg font-semibold mb-4 mt-6">Units</h3>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        @foreach($units as $unit)
            <a href="{{ route('admin.dashboard.unit', $unit) }}"
               class="block bg-white rounded-lg shadow p-4 border-l-4 border-blue-600">
                <h4 class="text-lg font-semibold">{{ $unit->name }}</h4>
                <p class="text-sm text-gray-500">{{ $unit->staff_count }} Staff</p>
            </a>
        @endforeach
    </div>

    <!-- FOOTER -->
    <footer class="text-center text-gray-500 text-sm py-6 border-t">
        © {{ date('Y') }} {{ config('app.name') }}
    </footer>

    <!-- SCRIPTS -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        document.addEventListener("DOMContentLoaded", function () {

            new Chart(document.getElementById('attendanceChart'), {
                type: 'doughnut',
                data: {
                    labels: ['Present', 'Absent', 'Late', 'Missed Clock-Out'],
                    datasets: [{
                        data: [
                            {{ $presentCount ?? 0 }},
                            {{ $absentCount ?? 0 }},
                            {{ $lateCount ?? 0 }},
                            {{ $missedClockOut ?? 0 }}
                        ],
                    }]
                }
            });

            new Chart(document.getElementById('employeesChart'), {
                type: 'doughnut',
                data: {
                    labels: ['Active', 'Inactive'],
                    datasets: [{
                        data: [{{ $activeEmployees ?? 0 }}, {{ $inactiveEmployees ?? 0 }}],
                    }]
                }
            });

            new Chart(document.getElementById('genderChart'), {
                type: 'doughnut',
                data: {
                    labels: ['Male', 'Female'],
                    datasets: [{
                        data: [{{ $maleCount ?? 0 }}, {{ $femaleCount ?? 0 }}],
                    }]
                }
            });

            new Chart(document.getElementById('ageChart'), {
                type: 'bar',
                data: {
                    labels: {!! json_encode(array_keys($ageGroups ?? [])) !!},
                    datasets: [{
                        label: 'Employees',
                        data: {!! json_encode(array_values($ageGroups ?? [])) !!}
                    }]
                }
            });

            new Chart(document.getElementById('serviceChart'), {
                type: 'bar',
                data: {
                    labels: {!! json_encode(array_keys($serviceGroups ?? [])) !!},
                    datasets: [{
                        label: 'Employees',
                        data: {!! json_encode(array_values($serviceGroups ?? [])) !!}
                    }]
                }
            });

        });

        document.querySelectorAll('.counter').forEach(counter => {
            let updateCount = () => {
                let target = +counter.getAttribute('data-target');
                let count = +counter.innerText;

                let increment = target / 50;

                if (count < target) {
                    counter.innerText = Math.ceil(count + increment);
                    setTimeout(updateCount, 20);
                } else {
                    counter.innerText = target;
                }
            };

            updateCount();
        });
    </script>

    <script src="//unpkg.com/alpinejs" defer></script>

</x-app-layout>