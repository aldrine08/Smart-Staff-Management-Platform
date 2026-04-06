<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800">
            Edit Off Day Request
        </h2>
    </x-slot>

    <div class="py-6 max-w-3xl mx-auto sm:px-6 lg:px-8">

        @if(session('error'))
            <div class="bg-red-100 text-red-800 p-4 rounded mb-4">
                {{ session('error') }}
            </div>
        @endif

        <div class="bg-white shadow rounded-lg p-6">
            <form action="{{ route('offdays.update', $offDay->id) }}" method="POST">
                @csrf
                @method('PUT')

                <!-- Start Date -->
                <div class="mb-4">
                    <label class="block font-medium mb-1">Start Date</label>
                    <input type="date" name="start_date"
                           value="{{ $offDay->start_date }}"
                           class="w-full border px-3 py-2 rounded" required>
                </div>

                <!-- End Date -->
                <div class="mb-4">
                    <label class="block font-medium mb-1">End Date</label>
                    <input type="date" name="end_date"
                           value="{{ $offDay->end_date }}"
                           class="w-full border px-3 py-2 rounded" required>
                </div>

                <!-- Reason -->
                <div class="mb-4">
                    <label class="block font-medium mb-1">Reason</label>
                    <textarea name="reason" rows="4"
                              class="w-full border px-3 py-2 rounded"
                              required>{{ $offDay->reason }}</textarea>
                </div>

                <!-- Buttons -->
                <div class="flex justify-end gap-2">
                    <a href="{{ route('offdays.index') }}"
                       class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400">
                        Cancel
                    </a>

                    <button type="submit"
                            class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                        Update
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>