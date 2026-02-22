<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800">
            {{ $unit->name }} – Staff Members
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <a href="{{ route('admin.dashboard') }}"
               class="inline-block mb-4 text-blue-600 hover:underline">
                ← Back to Dashboard
            </a>

            <div class="bg-white rounded-lg shadow p-4">
                @if($staff->isEmpty())
                    <p class="text-gray-500">No staff assigned to this unit.</p>
                @else
                    <ul class="divide-y divide-gray-200">
                        @foreach($staff as $user)
                            <li class="flex items-center py-3">
                                <img
                                    src="{{ $user->avatar 
                                        ? asset('storage/'.$user->avatar) 
                                        : 'https://ui-avatars.com/api/?name='.urlencode($user->name) }}"
                                    class="w-8 h-8 rounded-full mr-3"
                                >

                                <div>
                                    <p class="font-medium text-gray-800">
                                        {{ $user->name }}
                                    </p>
                                    <p class="text-sm text-gray-500">
                                        {{ $user->email }}
                                    </p>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
