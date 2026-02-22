<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">
            Items & Assignments
        </h2>
    </x-slot>

    <div class="py-6 max-w-7xl mx-auto">

        {{-- Search --}}
        <form method="GET" class="mb-6">
            <input
                type="text"
                name="search"
                value="{{ request('search') }}"
                placeholder="Search staff, unit, department or item..."
                class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring"
            >
        </form>

        {{-- Table --}}
        <div class="overflow-x-auto bg-white shadow rounded-lg">
            <table class="min-w-full border-collapse">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-4 py-2 text-left">Staff</th>
                        <th class="px-4 py-2 text-left">Unit</th>
                        <th class="px-4 py-2 text-left">Department</th>
                        <th class="px-4 py-2 text-left">Item</th>
                        <th class="px-4 py-2 text-left">Serial</th>
                        <th class="px-4 py-2 text-left">Status</th>
                        <th class="px-4 py-2 text-left">Assigned On</th>
                        <th class="px-4 py-2 text-left">Returned On</th>
                        <th class="px-4 py-2 text-left">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($assignments as $row)
                        <tr class="border-t">
                            <td class="px-4 py-2">{{ $row->staff_name }}</td>
                            <td class="px-4 py-2">{{ $row->unit_name ?? '—' }}</td>
                            <td class="px-4 py-2">{{ $row->department_name ?? '—' }}</td>
                            <td class="px-4 py-2 font-medium">{{ $row->item_name }}</td>
                            <td class="px-4 py-2">{{ $row->serial_number ?? '—' }}</td>
                            <td class="px-4 py-2">
                                <span class="px-2 py-1 rounded text-white text-sm
                                    {{ $row->status === 'assigned' ? 'bg-green-600' : 'bg-gray-500' }}">
                                    {{ ucfirst($row->status) }}
                                </span>
                            </td>
                            <td class="px-4 py-2">
                                {{ \Carbon\Carbon::parse($row->assigned_at)->format('d M Y') }}
                            </td>
                            <td class="px-4 py-2">
                                @if ($row->status === 'assigned')
                                    <form method="POST" action="{{ route('admin.items.deassign', $row->assignment_id) }}">
                                        @csrf
                                        <button
                                            class="text-red-600 hover:underline"
                                            onclick="return confirm('De-assign this item?')"
                                        >
                                            De-assign
                                        </button>
                                    </form>
                                @else
                                    —
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-6 text-gray-500">
                                No item assignments found
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>
