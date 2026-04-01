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

    <!-- ALL STAFF -->
    <a href="{{ route('admin.staff.all') }}"
       class="bg-white rounded-lg shadow p-6 hover:shadow-xl transition duration-300 block">
        <div class="flex items-center justify-between">
            <div>
                <h3 class="text-lg font-semibold text-gray-800">All Staff</h3>
                <p class="text-sm text-gray-500">View all staff members</p>
            </div>
            <div class="text-blue-600 text-3xl font-bold">
                {{ $staffCount }}
            </div>
        </div>
    </a>

    <!-- CLOCK IN SETTINGS -->
    <div x-data="{ clockInSettingsOpen: false }">
        <div x-show="clockInSettingsOpen"
             class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center">

            <div @click.away="clockInSettingsOpen = false"
                 class="bg-white rounded-lg p-6 w-full max-w-md">

                <h3 class="text-lg font-semibold mb-4">Clock-In Settings</h3>

                <form action="{{ route('admin.clockin-settings.update') }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-4">
                        <label class="block font-medium mb-1">Clock-In Start Time</label>
                        <input type="time" name="start_time"
                               value="{{ $setting->start_time ?? '08:10' }}"
                               class="border p-2 rounded w-full">
                    </div>

                    <div class="mb-4">
                        <label class="block font-medium mb-1">Working Days</label>
                        <select name="working_days[]" multiple class="border p-2 rounded w-full">
                            @foreach(['Mon','Tue','Wed','Thu','Fri','Sat','Sun'] as $day)
                                <option value="{{ $day }}"
                                    {{ in_array($day, $setting->working_days ?? []) ? 'selected' : '' }}>
                                    {{ $day }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <button class="bg-green-600 text-white py-2 px-4 rounded">
                        Save
                    </button>
                </form>

            </div>
        </div>
    </div>

    <!-- ADD DEPARTMENT -->
    <div x-data="{ addDepartmentOpen: false }">
        <div x-show="addDepartmentOpen"
             class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">

            <div @click.away="addDepartmentOpen = false"
                 class="bg-white rounded-lg shadow-lg w-full max-w-sm p-6">

                <h3 class="text-lg font-semibold mb-4">Add New Department</h3>

                <form method="POST" action="{{ route('admin.departments.store') }}">
                    @csrf
                    <input type="text" name="name"
                           class="w-full border rounded px-3 py-2"
                           placeholder="Department name" required>

                    <div class="flex justify-end mt-4 space-x-2">
                        <button type="button"
                                @click="addDepartmentOpen = false"
                                class="bg-gray-300 px-4 py-2 rounded">
                            Cancel
                        </button>

                        <button class="bg-purple-600 text-white px-4 py-2 rounded">
                            Save
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>

    <!-- ADD UNIT + MAP + SEARCH -->
    <div x-data="{
        addUnitOpen: false,
        mapOpen: false,
        lat: '',
        lng: ''
    }">

        <div class="py-6">
            <button @click="addUnitOpen = true"
                    class="bg-blue-600 text-white px-4 py-2 rounded">
                Add New Unit
            </button>

            <!-- UNIT MODAL -->
            <div x-show="addUnitOpen"
                 class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">

                <div @click.away="addUnitOpen = false"
                     class="bg-white rounded-lg shadow-lg w-full max-w-sm p-6">

                    <h3 class="text-lg font-semibold mb-4">Add New Unit</h3>

                    <form method="POST" action="{{ route('admin.units.store') }}">
                        @csrf

                        <div class="mb-4">
                            <label class="block text-gray-700">Unit Name</label>
                            <input type="text" name="name"
                                   class="w-full border rounded px-3 py-2"
                                   required>
                        </div>

                        <!-- SET LOCATION -->
                        <button type="button"
                                @click="mapOpen = true"
                                class="bg-green-600 text-white px-3 py-1 rounded">
                            Set Location
                        </button>

                        <input type="hidden" name="latitude" x-model="lat">
                        <input type="hidden" name="longitude" x-model="lng">

                        <div class="flex justify-end mt-4 space-x-2">
                            <button type="button"
                                    @click="addUnitOpen = false"
                                    class="bg-gray-300 px-4 py-2 rounded">
                                Cancel
                            </button>

                            <button class="bg-blue-600 text-white px-4 py-2 rounded">
                                Save
                            </button>
                        </div>
                    </form>

                </div>
            </div>

            <!-- MAP MODAL WITH SEARCH -->
            <div x-show="mapOpen"
                 class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">

                <div class="bg-white p-4 rounded w-full max-w-2xl">

                    <h2 class="text-lg font-bold mb-2">Select Unit Location</h2>

                    <!-- SEARCH INPUT -->
                    <input type="text"
                           id="locationSearch"
                           placeholder="Search location..."
                           class="w-full border p-2 rounded mb-2">

                    <!-- SUGGESTIONS -->
                    <div id="suggestions"
                         class="border rounded max-h-40 overflow-y-auto text-sm mb-2"></div>

                    <!-- MAP -->
                    <div id="map" style="height: 400px;"></div>

                    <div class="flex justify-end mt-3">
                        <button @click="mapOpen = false"
                                class="bg-gray-500 text-white px-3 py-1 rounded">
                            Close
                        </button>
                    </div>

                </div>
            </div>

            <!-- STATS -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-6">
                <div class="p-4 bg-white rounded-lg shadow">
                    <p>Total Staff</p>
                    <p class="text-2xl font-bold">{{ $totalStaff }}</p>
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
        </div>
    </div>

    <!-- UNITS -->
    <h3 class="text-lg font-semibold mb-4">Units</h3>

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

    <!-- LEAFLET -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

    <script>
        document.addEventListener("DOMContentLoaded", function () {

            let map;
            let marker;

            function initMap() {
                if (map) return;

                map = L.map('map').setView([-1.2921, 36.8219], 12);

                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '© OpenStreetMap'
                }).addTo(map);

                map.on('click', function (e) {
                    setMarker(e.latlng.lat, e.latlng.lng);
                });
            }

            function setMarker(lat, lng) {
                if (marker) map.removeLayer(marker);

                marker = L.marker([lat, lng]).addTo(map);

                document.querySelector('[x-model="lat"]').value = lat;
                document.querySelector('[x-model="lng"]').value = lng;

                map.setView([lat, lng], 15);
            }

            window.addEventListener('click', function () {
                setTimeout(() => {
                    if (document.getElementById('map')) {
                        initMap();
                    }
                }, 300);
            });

            // SEARCH + SUGGESTIONS (OPENSTREETMAP NOMINATIM)
            document.addEventListener('input', function (e) {

                if (e.target && e.target.id === 'locationSearch') {

                    let query = e.target.value;

                    if (query.length < 3) return;

                    fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${query}`)
                        .then(res => res.json())
                        .then(data => {

                            let box = document.getElementById('suggestions');
                            box.innerHTML = '';

                            data.slice(0, 5).forEach(place => {

                                let div = document.createElement('div');

                                div.className = "p-2 hover:bg-gray-100 cursor-pointer";
                                div.innerHTML = place.display_name;

                                div.onclick = function () {

                                    setMarker(place.lat, place.lon);

                                    document.getElementById('locationSearch').value = place.display_name;
                                    box.innerHTML = '';
                                };

                                box.appendChild(div);
                            });

                        });

                }

            });

        });
    </script>

    <script src="//unpkg.com/alpinejs" defer></script>
</x-app-layout>