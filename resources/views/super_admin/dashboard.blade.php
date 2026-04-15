<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-2xl font-bold text-gray-800">
                Super Admin Dashboard
            </h2>

            <span class="text-sm text-gray-500">
                System Overview
            </span>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- TOP STATS --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

                <div class="bg-white rounded-xl shadow p-6 border-l-4 border-indigo-500">
                    <p class="text-sm text-gray-500">Total Admins</p>
                    <p class="text-3xl font-bold text-gray-800">
                        {{ $admins->count() }}
                    </p>
                </div>

                <div class="bg-white rounded-xl shadow p-6 border-l-4 border-green-500">
                    <p class="text-sm text-gray-500">Total Companies</p>
                    <p class="text-3xl font-bold text-gray-800">
                        {{ $admins->sum(fn($a) => $a->units->count()) }}
                    </p>
                </div>

                <div class="bg-white rounded-xl shadow p-6 border-l-4 border-blue-500">
                    <p class="text-sm text-gray-500">Total Staff</p>
                    <p class="text-3xl font-bold text-gray-800">
                        {{ $admins->sum(fn($a) => $a->units->sum(fn($u) => $u->staff->count())) }}
                    </p>
                </div>

            </div>

            <div class="flex justify-between items-center">            
            <a href="{{ route('super_admin.admins.create') }}"
               class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700">
                + Add Admin
            </a>
        </div>

            {{-- SECTION TITLE --}}
            <div class="flex items-center justify-between mt-6">
                <h3 class="text-xl font-semibold text-gray-800">
                    Admin Overview
                </h3>

                <div class="text-sm text-gray-500">
                    Click an admin to manage details
                </div>
            </div>

            {{-- ADMIN CARDS --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

                @foreach($admins as $admin)
                    <div class="bg-white rounded-xl shadow hover:shadow-lg transition p-5 border border-gray-100">

                        {{-- Header --}}
                        <div class="flex items-center justify-between mb-3">
                            <div>
                                <h4 class="text-lg font-bold text-gray-800">
                                    {{ $admin->name }}
                                </h4>
                                <p class="text-sm text-gray-500">
                                    {{ $admin->email }}
                                </p>
                            </div>

                            <span class="px-2 py-1 text-xs rounded-full bg-indigo-100 text-indigo-600">
                                Admin
                            </span>
                        </div>

                        {{-- Stats --}}
                        <div class="space-y-2 text-sm text-gray-600">
                            <div class="flex justify-between">
                                <span>Companies</span>
                                <span class="font-semibold text-gray-800">
                                    {{ $admin->units->count() }}
                                </span>
                            </div>

                            <div class="flex justify-between">
                                <span>Staff</span>
                                <span class="font-semibold text-gray-800">
                                    {{ $admin->units->sum(fn($u) => $u->staff->count()) }}
                                </span>
                            </div>

                            <div class="flex justify-between">
                                <span>is_active</span>
                                <span class="font-semibold text-gray-800">
                                    {{ $admin->is_active ? 'Yes' : 'No' }}
                                </span>
                            </div>
                        </div>

                        {{-- Actions --}}
<div class="mt-4 grid grid-cols-2 gap-2">

    {{-- VIEW --}}
    <a href="{{ route('super_admin.admins.show', $admin->id) }}"
       class="text-center px-3 py-2 text-sm rounded-lg bg-indigo-600 text-white hover:bg-indigo-700 transition">
        View Admin
    </a>

    {{-- EDIT --}}
    <a href="{{ route('super_admin.admins.index', $admin->id) }}"
       class="text-center px-3 py-2 text-sm rounded-lg border border-gray-300 hover:bg-gray-50 transition">
        Manage
    </a>

    {{-- DEACTIVATE / ACTIVATE --}}
    <form method="POST" action="{{ route('super_admin.admins.deactivate', $admin->id) }}">
        @csrf
        <button class="w-full px-3 py-2 text-sm rounded-lg bg-yellow-500 text-white hover:bg-yellow-600 transition">
            Deactivate
        </button>
    </form>

    <form method="POST" action="{{ route('super_admin.admins.activate', $admin->id) }}">
        @csrf
        <button class="w-full px-3 py-2 text-sm rounded-lg bg-green-500 text-white hover:bg-green-600 transition">
            Activate
        </button>
    </form>

    {{-- DELETE --}}
    <form method="POST" action="{{ route('super_admin.admins.destroy', $admin->id) }}"
          onsubmit="return confirm('Are you sure you want to delete this admin?')">
        @csrf
        @method('DELETE')
        <button class="w-full px-3 py-2 text-sm rounded-lg bg-red-600 text-white hover:bg-red-700 transition">
            Delete
        </button>
    </form>

</div>

                    </div>
                @endforeach

            </div>

        </div>
    </div>
</x-app-layout>