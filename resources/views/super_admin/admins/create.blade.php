<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-bold">Create Admin</h2>
    </x-slot>

    <div class="p-6 max-w-2xl mx-auto">

        <form method="POST" action="{{ route('super_admin.admins.store') }}"
              class="bg-white p-6 rounded shadow space-y-4">

            @csrf

            <div>
                <label class="block text-sm font-medium">Name</label>
                <input type="text" name="name"
                       class="w-full border rounded p-2" required>
            </div>

            <div>
                <label class="block text-sm font-medium">Email</label>
                <input type="email" name="email"
                       class="w-full border rounded p-2" required>
            </div>

            <div>
                <label class="block text-sm font-medium">Password</label>
                <input type="password" name="password"
                       class="w-full border rounded p-2" required>
            </div>

            <button class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700">
                Create Admin
            </button>

        </form>

    </div>
</x-app-layout>