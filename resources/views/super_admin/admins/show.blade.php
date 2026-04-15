<x-app-layout>
    <x-slot name="header">
        <h2 class="text-2xl font-bold text-gray-800">
            Admin Details
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-5xl mx-auto space-y-6">

            {{-- ADMIN INFO --}}
            <div class="bg-white p-6 rounded-xl shadow">
                <h3 class="text-xl font-bold text-gray-800 mb-2">
                    {{ $admin->name }}
                </h3>
                <p class="text-gray-500">{{ $admin->email }}</p>

                <div class="mt-4 grid grid-cols-3 gap-4 text-center">
                    <div>
                        <p class="text-sm text-gray-500">Companies</p>
                        <p class="text-xl font-bold">
                            {{ $admin->units->count() }}
                        </p>
                    </div>

                    <div>
                        <p class="text-sm text-gray-500">Staff</p>
                        <p class="text-xl font-bold">
                            {{ $admin->units->sum(fn($u) => $u->staff->count()) }}
                        </p>
                    </div>

                    <div>
                        <p class="text-sm text-gray-500">Departments</p>
                        <p class="text-xl font-bold">
                            {{ $admin->units->count() }}
                        </p>
                    </div>
                </div>
            </div>

            {{-- COMPANIES --}}
            <div class="bg-white p-6 rounded-xl shadow">
                <h3 class="text-lg font-semibold mb-4">Companies</h3>

                @forelse($admin->units as $unit)
                    <div class="border-b py-2">
                        <p class="font-semibold">{{ $unit->name }}</p>
                        <p class="text-sm text-gray-500">
                            Staff: {{ $unit->staff->count() }}
                        </p>
                    </div>
                @empty
                    <p class="text-gray-500">No companies found.</p>
                @endforelse
            </div>

        </div>
    </div>
</x-app-layout>