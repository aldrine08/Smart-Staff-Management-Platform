<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold">Units</h2>
    </x-slot>

    <div class="p-4">
        <table class="w-full border">
            <thead>
                <tr class="bg-gray-100">
                    <th class="p-2">Name</th>
                    <th class="p-2">Latitude</th>
                    <th class="p-2">Longitude</th>
                </tr>
            </thead>
            <tbody>
                @foreach($units as $unit)
                    <tr>
                        <td class="p-2">{{ $unit->name }}</td>
                        <td class="p-2">{{ $unit->latitude }}</td>
                        <td class="p-2">{{ $unit->longitude }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</x-app-layout>