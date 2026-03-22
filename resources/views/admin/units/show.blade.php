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

       <!-- Staff Cards Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

        @forelse($staff as $member)

        <div
            class="bg-white rounded-xl shadow hover:shadow-lg transition p-5"
            x-show="
                !search ||
                '{{ $member->name }}'.toLowerCase().includes(search.toLowerCase()) ||
                '{{ $member->phone }}'.toLowerCase().includes(search.toLowerCase()) ||
                '{{ $member->email }}'.toLowerCase().includes(search.toLowerCase()) ||
                '{{ $member->unit->name ?? '' }}'.toLowerCase().includes(search.toLowerCase()) ||
                '{{ $member->department->name ?? '' }}'.toLowerCase().includes(search.toLowerCase())
            "
        >

            <!-- Avatar -->
            <div class="flex items-center space-x-4 mb-4">

                <img
                    class="w-14 h-14 rounded-full object-cover border"
                    src="{{ $member->avatar ? asset('storage/'.$member->avatar) : 'https://ui-avatars.com/api/?name='.$member->name }}"
                >

                <div>
                    <h3 class="text-lg font-semibold text-gray-800">
                        {{ $member->name }}
                    </h3>

                    <p class="text-sm text-gray-500">
                        {{ $member->email }}
                    </p>
                </div>

            </div>

            <!-- Details -->
            <div class="space-y-2 text-sm text-gray-600">

                <p>
                    📞 <strong>Phone:</strong>
                    {{ $member->phone ?? '—' }}
                </p>

                <p>
                    🏢 <strong>Unit:</strong>
                    {{ $member->unit->name ?? '—' }}
                </p>

                <p>
                    📂 <strong>Department:</strong>
                    {{ $member->department->name ?? '—' }}
                </p>

            </div>

            <!-- Actions -->
            <div class="flex justify-between mt-5">

                <a href="{{ route('admin.staff.edit', $member->id) }}"
   class="px-3 py-2 bg-gray-200 rounded hover:bg-gray-300 text-sm">
    Edit
</a>

                <a href="{{ route('admin.staff.show', $member->id) }}"
   class="px-3 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 text-sm">
    View Profile
</a>

            </div>

        </div>

        @empty

        <div class="col-span-3 text-center text-gray-500">
            No staff records found.
        </div>

        @endforelse

    </div>
    <!-- ================= FOOTER ================= -->
<footer class="text-center text-gray-500 text-sm py-6 border-t border-gray-800">
    © {{ date('Y') }} {{ config('app.name') }} — Staff Management System
</footer>

</x-app-layout>
