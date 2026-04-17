<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="text-xl font-bold text-gray-800 tracking-tight">Admins</h2>

            <a href="{{ route('super_admin.admins.create') }}"
               class="inline-flex items-center gap-2 bg-indigo-600 text-white px-4 py-2 rounded-lg shadow-sm hover:bg-indigo-700 transition-all duration-200">
                + Add Admin
            </a>
        </div>
    </x-slot>

    <div class="p-6 space-y-6">

        @if(session('success'))
            <div class="mb-4 p-3 bg-green-50 border border-green-200 text-green-700 rounded-lg shadow-sm">
                {{ session('success') }}
            </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($admins as $admin)
                <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100 hover:shadow-md transition-all duration-200">

                    <!-- Admin Info -->
                    <div class="flex justify-between items-start">
                        <div class="space-y-1">
                            <h3 class="font-semibold text-lg text-gray-900">{{ $admin->name }}</h3>

                            <p class="text-sm text-gray-500">
                                {{ $admin->email }}
                            </p>

                            <span class="inline-flex items-center text-xs px-2 py-1 rounded-full font-medium
                                {{ $admin->is_active ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                {{ $admin->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </div>
                    </div>

                    <!-- Created Date -->
                    <div class="mt-4 text-xs text-gray-500">
                        Created:
                        <span class="font-medium text-gray-600">
                            {{ $admin->created_at->format('Y-m-d') }}
                        </span>
                    </div>

                    <!-- Actions -->
                    <div class="mt-5 grid grid-cols-2 gap-2">

                        <a href="{{ route('super_admin.admins.edit', $admin->id) }}"
                           class="inline-flex justify-center items-center px-3 py-2 text-sm rounded-lg bg-indigo-600 text-white hover:bg-indigo-700 transition">
                            Edit
                        </a>

                        @if($admin->role === 'super_admin')
                            <span class="col-span-2 text-center text-xs font-semibold text-emerald-600 bg-emerald-50 py-2 rounded-lg border border-emerald-100">
                                Protected Super Admin
                            </span>
                        @else

                            @if($admin->is_active)
                                <form method="POST" action="{{ route('super_admin.admins.deactivate', $admin->id) }}">
                                    @csrf
                                    <button class="w-full px-3 py-2 text-sm rounded-lg bg-yellow-500 text-white hover:bg-yellow-600 transition">
                                        Deactivate
                                    </button>
                                </form>
                            @else
                                <form method="POST" action="{{ route('super_admin.admins.activate', $admin->id) }}">
                                    @csrf
                                    <button class="w-full px-3 py-2 text-sm rounded-lg bg-green-500 text-white hover:bg-green-600 transition">
                                        Activate
                                    </button>
                                </form>
                            @endif

                            <form action="{{ route('super_admin.admins.destroy', $admin->id) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button class="w-full px-3 py-2 text-sm rounded-lg bg-red-500 text-white hover:bg-red-600 transition">
                                    Delete
                                </button>
                            </form>

                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </div>


    {{-- LIVE ACTIVE USERS --}}
    <div class="mb-6 bg-white p-5 rounded-2xl shadow-sm border border-gray-100">

        <h2 class="text-lg font-semibold text-gray-800 mb-4 flex items-center gap-2">
            🔵 Active System Users
            <span class="text-xs text-gray-500 font-normal">(Last 5 Minutes)</span>
        </h2>

        @if($activeUsers->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">

                @foreach($activeUsers as $user)
                    <div class="p-4 rounded-xl border border-green-100 bg-green-50 hover:bg-green-100 transition">

                        <div class="font-semibold text-gray-900">
                            {{ $user->name }}
                        </div>

                        <div class="text-sm text-gray-600">
                            {{ $user->email }}
                        </div>

                        <div class="text-xs text-gray-500 mt-1">
                            IP: <span class="font-medium">{{ $user->ip_address }}</span>
                        </div>

                        <div class="text-xs text-gray-500 mt-1">
                            User Agent: <span class="font-medium">{{ $user->user_agent }}</span>
                        </div>

                        <div class="text-xs text-gray-500">
                            Last active:
                            <span class="font-medium text-gray-600">
                                {{ \Carbon\Carbon::createFromTimestamp($user->last_activity)->diffForHumans() }}
                            </span>
                        </div>

                    </div>
                @endforeach

            </div>
        @else
            <p class="text-sm text-gray-500 italic">
                No active users currently online.
            </p>
        @endif

    </div>

</x-app-layout>