<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            My Off Days
        </h2>
    </x-slot>

    <div class="py-6 max-w-7xl mx-auto sm:px-6 lg:px-8">

        @if(session('success'))
            <div class="bg-green-100 text-green-800 p-4 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        @if($offDays->isEmpty())
            <p class="p-4 text-gray-500 text-center">You have no off day requests yet.</p>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($offDays as $offDay)
                    <div class="bg-white shadow rounded-lg p-4 flex flex-col justify-between">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-800 mb-2">
                                Off Day Request
                            </h3>

                            <p class="text-sm text-gray-500"><strong>Start Date:</strong> {{ $offDay->start_date }}</p>
                            <p class="text-sm text-gray-500"><strong>End Date:</strong> {{ $offDay->end_date }}</p>
                            <p class="mt-2 text-sm"><strong>Reason:</strong> {{ $offDay->reason }}</p>
                            <p class="mt-2 text-sm">
                                <strong>Status:</strong> 
                                <span class="font-semibold 
                                    {{ $offDay->status == 'pending' ? 'text-yellow-600' : ($offDay->status == 'approved' ? 'text-green-600' : 'text-red-600') }}">
                                    {{ ucfirst($offDay->status) }}
                                </span>
                            </p>

                            @if($offDay->status === 'pending')
    <div class="mt-4">
        <a href="{{ route('offdays.edit', $offDay->id) }}"
           class="inline-block bg-blue-600 text-white px-3 py-1 rounded hover:bg-blue-700 transition">
            ✏️ Edit
        </a>
    </div>
@endif
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

    </div>

    <!-- ================= FOOTER ================= -->
<footer class="text-center text-gray-500 text-sm py-6 border-t border-gray-800">
    © {{ date('Y') }} {{ config('app.name') }} — Staff Management System
</footer>
</x-app-layout>
