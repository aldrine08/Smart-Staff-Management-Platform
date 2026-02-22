<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800">
            {{ $unit->name }} Unit
        </h2>
    </x-slot>

    <div 
        class="max-w-7xl mx-auto"
        x-data="{ search: '' }"
    >

        <!-- Search Filter -->
        <div class="mb-4">
            <input
                type="text"
                x-model="search"
                placeholder="Search by name, phone, email or department..."
                class="w-full px-4 py-2 border rounded-lg focus:ring focus:ring-blue-200"
            >
        </div>

        <!-- Unit Summary -->
        <div class="bg-white rounded shadow p-4 mb-6">
            <p class="text-gray-600">
                Total Staff: <strong>{{ $staff->count() }}</strong>
            </p>
        </div>

        <!-- Staff List -->
        <div class="bg-white rounded shadow">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-4 py-2 text-left">Name</th>
                        <th class="px-4 py-2 text-left">Phone</th>
                        <th class="px-4 py-2 text-left">Email</th>
                        <th class="px-4 py-2 text-left">Department</th>
                        <th class="px-4 py-2 text-left">Clocked In</th>
                        <th class="px-4 py-2 text-left">Clocked Out</th>
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
                                '{{ $member->department->name ?? '' }}'.toLowerCase().includes(search.toLowerCase())
                            "
                            x-transition
                        >
                            <td class="px-4 py-2">{{ $member->name }}</td>
                            <td class="px-4 py-2">{{ $member->phone }}</td>
                            <td class="px-4 py-2">{{ $member->email }}</td>
                            <td class="px-4 py-2">
                                {{ $member->department->name ?? '—' }}
                            </td>

                            <!-- Clock In -->
                            <td class="px-4 py-2">
                                @if($member->todayAttendance?->clock_in)
                                    {{ \Carbon\Carbon::parse($member->todayAttendance->clock_in)->format('d M Y, H:i') }}
                                @else
                                    <span class="text-gray-400">Not clocked in</span>
                                @endif
                            </td>

                            <!-- Clock Out -->
                            <td class="px-4 py-2">
                                @if($member->todayAttendance?->clock_out)
                                    {{ \Carbon\Carbon::parse($member->todayAttendance->clock_out)->format('d M Y, H:i') }}
                                @else
                                    <span class="text-gray-400">—</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-4 text-gray-500">
                                No staff in this unit.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>

    <!-- ================= FOOTER ================= -->
<footer class="text-center text-gray-500 text-sm py-6 border-t border-gray-800">
    © {{ date('Y') }} {{ config('app.name') }} — Staff Management System
</footer>

</x-app-layout>
