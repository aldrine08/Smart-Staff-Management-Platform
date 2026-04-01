<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800">
            Sick Requests
        </h2>
    </x-slot>

    <div class="py-6 max-w-7xl mx-auto grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

        @foreach($requests as $request)
        <div class="bg-white shadow rounded-lg p-4">

            <h3 class="font-bold">{{ $request->user->name }}</h3>

            <p><strong>Start:</strong> {{ $request->start_date }}</p>
            <p><strong>End:</strong> {{ $request->end_date }}</p>
            <p><strong>Reason:</strong> {{ $request->reason }}</p>

            <p class="mt-2">
                <strong>Status:</strong>
                <span class="{{ $request->status == 'approved' ? 'text-green-600' : ($request->status == 'pending' ? 'text-yellow-600' : 'text-red-600') }}">
                    {{ ucfirst($request->status) }}
                </span>
            </p>

            {{-- IMAGE --}}
            @if($request->medical_proof)
                <img src="{{ asset('storage/'.$request->medical_proof) }}" class="w-full h-40 object-cover mt-2 rounded">

                <a href="{{ asset('storage/'.$request->medical_proof) }}" download class="text-blue-600 underline">
                    Download Proof
                </a>
            @endif

            <td>
    @if($request->sick_note)
        <a href="{{ asset('storage/' . $request->sick_note) }}"
           target="_blank"
           class="text-blue-600 underline">
            View Sick Note
        </a>
    @else
        <span class="text-gray-400">No file</span>
    @endif
</td>

           @if($request->status === 'pending')

    <!-- APPROVE -->
    <form action="{{ route('sick-requests.approve', $request->id) }}" method="POST" class="inline">
        @csrf
        <button class="bg-green-500 text-white px-3 py-1 rounded">
            Approve
        </button>
    </form>

    <!-- DECLINE -->
    <form action="{{ route('sick-requests.decline', $request->id) }}" method="POST" class="inline">
        @csrf
        <button class="bg-red-500 text-white px-3 py-1 rounded">
            Decline
        </button>
    </form>

@else

    <!-- STATUS LABEL (NO BUTTONS ANYMORE) -->
    <span class="
        px-3 py-1 rounded text-white text-sm
        {{ $request->status === 'approved' ? 'bg-green-600' : 'bg-red-600' }}
    ">
        {{ ucfirst($request->status) }}
    </span>

@endif

        </div>
        @endforeach

    </div>
</x-app-layout>