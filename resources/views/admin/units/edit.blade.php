<x-app-layout>
    <x-slot name="header">
        <h2 class="text-2xl font-bold text-gray-800">
            ✏️ Edit Unit
        </h2>
    </x-slot>

```
<div class="py-8">
    <div class="max-w-2xl mx-auto">
        <div class="bg-white shadow-xl rounded-2xl p-8">

            <!-- Success Message -->
            @if(session('success'))
                <div class="mb-6 p-4 bg-green-100 text-green-700 rounded-lg">
                    {{ session('success') }}
                </div>
            @endif

            <form method="POST" action="{{ route('admin.units.update', $unit->id) }}">
                @csrf
                @method('PUT')

                <!-- Unit Name -->
                <div class="mb-5">
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Unit Name
                    </label>
                    <input type="text" name="name"
                           value="{{ old('name', $unit->name) }}"
                           class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-green-500 focus:border-green-500 p-2.5"
                           placeholder="Enter unit name">
                </div>

                <!-- Latitude -->
                <div class="mb-5">
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Latitude
                    </label>
                    <input type="text" name="latitude"
                           value="{{ old('latitude', $unit->latitude) }}"
                           class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-green-500 focus:border-green-500 p-2.5"
                           placeholder="e.g. -1.2921">
                </div>

                <!-- Longitude -->
                <div class="mb-5">
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Longitude
                    </label>
                    <input type="text" name="longitude"
                           value="{{ old('longitude', $unit->longitude) }}"
                           class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-green-500 focus:border-green-500 p-2.5"
                           placeholder="e.g. 36.8219">
                </div>

                <!-- Radius -->
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Radius (meters)
                    </label>
                    <input type="number" name="radius"
                           value="{{ old('radius', $unit->radius) }}"
                           class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-green-500 focus:border-green-500 p-2.5"
                           placeholder="Enter allowed radius"
                           step="0.01" min="0">
                </div>

                <!-- Buttons -->
                <div class="flex items-center justify-between">
                    <a href="{{ route('admin.units.index') }}"
                       class="text-gray-600 hover:text-gray-800 text-sm">
                        ← Back to Units
                    </a>

                    <button type="submit"
                            class="bg-green-600 hover:bg-green-700 text-white px-6 py-2.5 rounded-lg shadow-md transition duration-200">
                        💾 Update Unit
                    </button>
                </div>
            </form>

        </div>
    </div>
</div>
```

</x-app-layout>
