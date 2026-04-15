<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-bold">Edit Admin</h2>
    </x-slot>

    <div class="p-6 max-w-2xl mx-auto">

        <form method="POST"
              action="{{ route('super_admin.admins.update', $admin->id) }}"
              class="bg-white p-6 rounded-xl shadow space-y-4">

            @csrf
            @method('PUT')

            {{-- NAME --}}
            <div>
                <label class="block text-sm font-medium">Name</label>
                <input type="text"
                       name="name"
                       value="{{ $admin->name }}"
                       class="w-full border rounded p-2"
                       required>
            </div>

            {{-- EMAIL --}}
            <div>
                <label class="block text-sm font-medium">Email</label>
                <input type="email"
                       name="email"
                       value="{{ $admin->email }}"
                       class="w-full border rounded p-2"
                       required>
            </div>

            {{-- STATUS --}}
            <div class="flex items-center gap-2">
                <label class="text-sm font-medium">Active Status:</label>

                <select name="is_active" class="border rounded p-2">
                    <option value="1" {{ $admin->is_active ? 'selected' : '' }}>
                        Active
                    </option>
                    <option value="0" {{ !$admin->is_active ? 'selected' : '' }}>
                        Inactive
                    </option>
                </select>
            </div>

            {{-- BUTTON --}}
            <button class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700">
                Update Admin
            </button>

        </form>

    </div>
</x-app-layout>