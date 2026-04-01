<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800">
            {{ $unit->name }} Unit
        </h2>
    </x-slot>

    <div class="max-w-7xl mx-auto" x-data="{ search: '' }">

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

        <div class="flex gap-2 mb-4">

    <!-- EDIT -->
    <a href="{{ route('admin.units.edit', $unit->id) }}"
       class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
        Edit Unit
    </a>

    <!-- DELETE -->
    <form method="POST"
          action="{{ route('admin.units.destroy', $unit->id) }}"
          onsubmit="return confirm('Are you sure you want to delete this unit?');">
        @csrf
        @method('DELETE')

        <button type="submit"
                class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700">
            Delete Unit
        </button>
    </form>

</div>

        <!-- Staff Cards Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($staff as $member)
                <div
                    class="bg-white rounded-xl shadow hover:shadow-lg transition p-5"
                    x-show="
                        !search ||
                        '{{ \Illuminate\Support\Str::of($member->name)->lower() }}'.includes(search.toLowerCase()) ||
                        '{{ \Illuminate\Support\Str::of($member->phone)->lower() }}'.includes(search.toLowerCase()) ||
                        '{{ \Illuminate\Support\Str::of($member->email)->lower() }}'.includes(search.toLowerCase()) ||
                        '{{ \Illuminate\Support\Str::of($member->unit->name ?? '')->lower() }}'.includes(search.toLowerCase()) ||
                        '{{ \Illuminate\Support\Str::of($member->department->name ?? '')->lower() }}'.includes(search.toLowerCase())
                    "
                >
                    <!-- Avatar -->
                    <div class="flex items-center space-x-4 mb-4">
                        <img
                            class="w-14 h-14 rounded-full object-cover border"
                            src="{{ $member->avatar ? asset("storage/{$member->avatar}") : 'https://ui-avatars.com/api/?name=' . urlencode($member->name) }}"
                            alt="{{ $member->name }}"
                        >
                        <div>
                            <h3 class="text-lg font-semibold text-gray-800">
                                {{ $member->name }}
                            </h3>

                            <p class="text-xs mt-1">
    Status:
    @if($member->is_active)
        <span class="text-green-600 font-semibold">Active</span>
    @else
        <span class="text-red-600 font-semibold">Inactive</span>
    @endif
</p>
                            <p class="text-sm text-gray-500">
                                {{ $member->email }}
                            </p>
                        </div>
                    </div>

                    <!-- Details -->
                    <div class="space-y-2 text-sm text-gray-600">
                        <p>📞 <strong>Phone:</strong> {{ $member->phone ?? '—' }}</p>
                        <p>🏢 <strong>Unit:</strong> {{ $member->unit->name ?? '—' }}</p>
                        <p>📂 <strong>Department:</strong> {{ $member->department->name ?? '—' }}</p>
                    </div>

                    <!-- Actions -->
                   <div class="flex flex-wrap gap-2 mt-5">

    <!-- Edit -->
    <a href="{{ route('admin.staff.edit', $member->id) }}"
       class="px-3 py-2 bg-gray-200 rounded hover:bg-gray-300 text-sm">
        Edit
    </a>

    <!-- View -->
    <a href="{{ route('admin.staff.show', $member->id) }}"
       class="px-3 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 text-sm">
        View Profile
    </a>

    <!-- Activate / Deactivate -->
    <form method="POST" action="{{ route('admin.staff.toggle', $member->id) }}"
          onsubmit="return confirm('Are you sure you want to change this staff status?');">
        @csrf
        <button type="submit"
            class="px-3 py-2 text-white rounded text-sm
            {{ $member->is_active ? 'bg-yellow-500 hover:bg-yellow-600' : 'bg-green-600 hover:bg-green-700' }}">
            
            {{ $member->is_active ? 'Deactivate' : 'Activate' }}
        </button>
    </form>

    <!-- Delete -->
    <form method="POST" action="{{ route('admin.staff.delete', $member->id) }}"
          onsubmit="return confirm('Are you sure you want to delete this staff member?');">
        @csrf
        @method('DELETE')

        <button type="submit"
            class="px-3 py-2 bg-red-600 text-white rounded hover:bg-red-700 text-sm">
            Delete
        </button>
    </form>

</div>
                </div>
            @empty
                <div class="col-span-3 text-center text-gray-500">
                    No staff records found.
                </div>
            @endforelse
        </div>

        <!-- Footer -->
        <footer class="text-center text-gray-500 text-sm py-6 border-t border-gray-800">
            © {{ date('Y') }} {{ config('app.name') }} — Staff Management System
        </footer>

    </div>
</x-app-layout>
