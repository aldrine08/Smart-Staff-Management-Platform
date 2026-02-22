<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800">
            All Staff Members
        </h2>
    </x-slot>

    <!-- Alpine.js state -->
    <div class="max-w-7xl mx-auto" x-data="{ search: '' }">

        <!-- Search Input -->
        <div class="mb-4">
            <input
                type="text"
                x-model="search"
                placeholder="Search by name, phone, email, unit or department..."
                class="w-full px-4 py-2 border rounded-lg focus:ring focus:ring-blue-200"
            >
        </div>

        <!-- All Staff Card -->
        <div class="bg-white rounded shadow p-4">

            <div class="mb-4">
                <p class="text-gray-600">
                    Total Staff: <strong>{{ $staff->count() }}</strong>
                </p>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="px-4 py-2 text-left">Name</th>
                            <th class="px-4 py-2 text-left">Phone</th>
                            <th class="px-4 py-2 text-left">Email</th>
                            <th class="px-4 py-2 text-left">Unit</th>
                            <th class="px-4 py-2 text-left">Department</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y">
                        @forelse($staff as $member)
                            <tr
                                x-show="
                                    !search ||
                                    '{{ $member->name }}'.toLowerCase().includes(search.toLowerCase()) ||
                                    '{{ $member->phone }}'.toLowerCase().includes(search.toLowerCase()) ||
                                    '{{ $member->email }}'.toLowerCase().includes(search.toLowerCase()) ||
                                    '{{ $member->unit->name ?? '' }}'.toLowerCase().includes(search.toLowerCase()) ||
                                    '{{ $member->department->name ?? '' }}'.toLowerCase().includes(search.toLowerCase())
                                "
                                x-transition
                            >
                                <td class="px-4 py-2">{{ $member->name }}</td>
                                <td class="px-4 py-2">{{ $member->phone }}</td>
                                <td class="px-4 py-2">{{ $member->email }}</td>
                                <td class="px-4 py-2">{{ $member->unit->name ?? '—' }}</td>
                                <td class="px-4 py-2">{{ $member->department->name ?? '—' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-4 text-gray-500">
                                    No staff records found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        </div>
    </div>

    <!-- ================= FOOTER ================= -->
<footer class="text-center text-gray-500 text-sm py-6 border-t border-gray-800">
    © {{ date('Y') }} {{ config('app.name') }} — Staff Management System
</footer>


</x-app-layout>
