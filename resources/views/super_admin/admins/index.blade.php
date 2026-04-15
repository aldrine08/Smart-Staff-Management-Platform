<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="text-xl font-bold">Admins</h2>

            <a href="{{ route('super_admin.admins.create') }}"
               class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700">
                + Add Admin
            </a>
        </div>
    </x-slot>

    <div class="p-6">

        @if(session('success'))
            <div class="mb-4 p-3 bg-green-100 text-green-700 rounded">
                {{ session('success') }}
            </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

            @foreach($admins as $admin)
    <div class="bg-white p-4 rounded-xl shadow border">

        <div class="flex justify-between items-start">
            <div>
                <h3 class="font-bold text-lg">{{ $admin->name }}</h3>
                <p class="text-sm text-gray-500">{{ $admin->email }}</p>

                <span class="text-xs px-2 py-1 rounded-full 
                    {{ $admin->is_active ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                    {{ $admin->is_active ? 'Active' : 'Inactive' }}
                </span>
            </div>
        </div>

        <div class="mt-3 text-sm text-gray-600">
            Created: {{ $admin->created_at->format('Y-m-d') }}
        </div>

       {{-- ACTIONS --}}
<div class="mt-4 grid grid-cols-2 gap-2">

    {{-- EDIT --}}
    <a href="{{ route('super_admin.admins.edit', $admin->id) }}"
       class="text-center px-3 py-2 text-sm rounded bg-indigo-600 text-white hover:bg-indigo-700">
        Edit
    </a>

    {{-- PROTECTION LOGIC --}}
    @if($admin->role === 'super_admin')

        <span class="col-span-2 text-center text-green-600 font-bold">
            Protected Super Admin
        </span>

    @else

        {{-- ACTIVATE / DEACTIVATE --}}
        @if($admin->is_active)
            <form method="POST" action="{{ route('super_admin.admins.deactivate', $admin->id) }}">
                @csrf
                <button class="w-full px-3 py-2 text-sm rounded bg-yellow-500 text-white hover:bg-yellow-600">
                    Deactivate
                </button>
            </form>
        @else
            <form method="POST" action="{{ route('super_admin.admins.activate', $admin->id) }}">
                @csrf
                <button class="w-full px-3 py-2 text-sm rounded bg-green-500 text-white hover:bg-green-600">
                    Activate
                </button>
            </form>
        @endif

        {{-- DELETE --}}
        <form action="{{ route('super_admin.admins.destroy', $admin->id) }}" method="POST">
            @csrf
            @method('DELETE')

            <button class="w-full px-3 py-2 text-sm rounded bg-red-500 text-white hover:bg-red-600">
                Delete
            </button>
        </form>

    @endif

</div>

    </div>
@endforeach

        </div>
    </div>
</x-app-layout>