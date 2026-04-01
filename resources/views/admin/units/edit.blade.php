<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800">
            Edit Unit
        </h2>
    </x-slot>

    <div class="max-w-xl mx-auto bg-white p-6 rounded shadow">

        <form method="POST" action="{{ route('admin.units.update', $unit->id) }}">
            @csrf
            @method('PUT')

            <div class="mb-4">
                <label class="block">Unit Name</label>
                <input type="text" name="name"
                       value="{{ $unit->name }}"
                       class="w-full border p-2 rounded">
            </div>

            <div class="mb-4">
                <label class="block">Latitude</label>
                <input type="text" name="latitude"
                       value="{{ $unit->latitude }}"
                       class="w-full border p-2 rounded">
            </div>

            <div class="mb-4">
                <label class="block">Longitude</label>
                <input type="text" name="longitude"
                       value="{{ $unit->longitude }}"
                       class="w-full border p-2 rounded">
            </div>

            <button class="bg-green-600 text-white px-4 py-2 rounded">
                Update Unit
            </button>
        </form>

    </div>
</x-app-layout>