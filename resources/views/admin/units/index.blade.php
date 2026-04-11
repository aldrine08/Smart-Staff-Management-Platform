<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="text-2xl font-bold text-gray-800">
                🏢 Units Management
            </h2>

    </div>
</x-slot>

<div class="py-8">
    <div class="max-w-6xl mx-auto">
        <div class="bg-white shadow-xl rounded-2xl overflow-hidden">

            <!-- Success Message -->
            @if(session('success'))
                <div class="p-4 bg-green-100 text-green-700">
                    {{ session('success') }}
                </div>
            @endif

            <table class="w-full text-sm text-left">
                <thead class="bg-gray-100 text-gray-700 uppercase text-xs">
                    <tr>
                        <th class="p-4">Name</th>
                        <th class="p-4">Latitude</th>
                        <th class="p-4">Longitude</th>
                        <th class="p-4">Radius (m)</th>
                        <th class="p-4 text-right">Actions</th>
                    </tr>
                </thead>

                <tbody class="divide-y">
                    @forelse($units as $unit)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="p-4 font-medium text-gray-800">
                                {{ $unit->name }}
                            </td>

                            <td class="p-4 text-gray-600">
                                {{ $unit->latitude }}
                            </td>

                            <td class="p-4 text-gray-600">
                                {{ $unit->longitude }}
                            </td>

                            <td class="p-4 text-gray-600">
                                {{ $unit->radius ?? '—' }}
                            </td>

                            <td class="p-4 text-right space-x-2">

                                <!-- Edit -->
                                <a href="{{ route('admin.units.edit', $unit->id) }}"
                                   class="inline-block bg-blue-500 hover:bg-blue-600 text-white px-3 py-1.5 rounded-md text-xs">
                                    Edit
                                </a>

                                <!-- Delete -->
                                <form action="{{ route('admin.units.destroy', $unit->id) }}"
                                      method="POST"
                                      class="inline-block"
                                      onsubmit="return confirm('Are you sure you want to delete this unit?')">
                                    @csrf
                                    @method('DELETE')

                                    <button type="submit"
                                            class="bg-red-500 hover:bg-red-600 text-white px-3 py-1.5 rounded-md text-xs">
                                        Delete
                                    </button>
                                </form>

                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="p-6 text-center text-gray-500">
                                No units found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>

            </table>

        </div>
    </div>
</div>
```

</x-app-layout>
