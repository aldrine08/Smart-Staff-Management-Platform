<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800">
            Off Day Requests
        </h2>
    </x-slot>

    <div class="py-6 max-w-7xl mx-auto sm:px-6 lg:px-8">

        <!-- Filters -->
        <div class="mb-6 bg-white shadow rounded p-4 flex flex-col md:flex-row md:items-end gap-4">
            <form method="GET" action="{{ route('admin.offdays.index') }}" class="flex flex-col md:flex-row gap-4 w-full">

                <div class="flex-1">
                    <label class="block text-sm font-medium text-gray-700">Staff Name</label>
                    <input type="text" name="staff_name" value="{{ request('staff_name') }}" 
                        class="mt-1 block w-full border border-gray-300 rounded px-3 py-2 shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm" 
                        placeholder="Search by staff name">
                </div>

                <div class="flex-1">
                    <label class="block text-sm font-medium text-gray-700">Unit</label>
                    <select name="unit_id" class="mt-1 block w-full border border-gray-300 rounded px-3 py-2 shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                        <option value="">All Units</option>
                        @foreach($units as $unit)
    <option value="{{ $unit->id }}" {{ request('unit_id') == $unit->id ? 'selected' : '' }}>
        {{ $unit->name }}
    </option>
@endforeach
                    </select>
                </div>

                <div class="flex-1">
                    <label class="block text-sm font-medium text-gray-700">Department</label>
                    <select name="department_id" class="mt-1 block w-full border border-gray-300 rounded px-3 py-2 shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                        <option value="">All Departments</option>
                        @foreach($departments as $dept)
    <option value="{{ $dept->id }}" {{ request('department_id') == $dept->id ? 'selected' : '' }}>
        {{ $dept->name }}
    </option>
@endforeach
                    </select>
                </div>

                <div>
                    <button type="submit" class="w-full md:w-auto bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                        Filter
                    </button>
                </div>
            </form>
        </div>

        <!-- Requests Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($offDayRequests as $request)
                <div class="bg-white shadow rounded-lg p-4 flex flex-col justify-between">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-800 mb-2">{{ optional($request->user)->name }}</h3>
                        <p class="text-sm text-gray-500"><strong>Unit:</strong> {{ optional($request->user)->unit->name ?? 'N/A' }}</p>
                        <p class="text-sm text-gray-500"><strong>Department:</strong> {{ optional($request->user)->department->name ?? 'N/A' }}</p>
                        <p class="mt-2 text-sm"><strong>Start:</strong> {{ $request->start_date }}</p>
                        <p class="text-sm"><strong>End:</strong> {{ $request->end_date }}</p>
                        <p class="mt-2 text-sm"><strong>Reason:</strong> {{ $request->reason }}</p>
                        <p class="mt-2 text-sm"><strong>Status:</strong> 
                            <span class="font-semibold 
                                {{ $request->status == 'pending' ? 'text-yellow-600' : ($request->status == 'approved' ? 'text-green-600' : 'text-red-600') }}">
                                {{ ucfirst($request->status) }}
                            </span>
                        </p>
                    </div>

                    <!-- Actions -->
                    @if($request->status == 'pending')
                        <div class="mt-4 flex gap-2">
                            <form action="{{ route('admin.offdays.approve', $request->id) }}" method="POST" class="flex-1">
                                @csrf
                                @method('PUT')
                                <button class="w-full bg-green-600 text-white py-2 rounded hover:bg-green-700">Approve</button>
                            </form>
                            <form action="{{ route('admin.offdays.decline', $request->id) }}" method="POST" class="flex-1">
                                @csrf
                                @method('PUT')
                                <button class="w-full bg-red-600 text-white py-2 rounded hover:bg-red-700">Decline</button>
                            </form>
                        </div>
                    @endif
                </div>
            @empty
                <p class="col-span-full text-center text-gray-500">No off day requests found.</p>
            @endforelse
        </div>

    </div>

    <!-- ================= FOOTER ================= -->
<footer class="text-center text-gray-500 text-sm py-6 border-t border-gray-800">
    © {{ date('Y') }} {{ config('app.name') }} — Staff Management System
</footer>


</x-app-layout>
