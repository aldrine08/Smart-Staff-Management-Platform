<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800">Edit Bonus</h2>
    </x-slot>

    <div class="py-6 max-w-3xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white shadow rounded-lg p-6">
            <form action="{{ route('bonuses.update', $bonus->id) }}" method="POST" class="space-y-4">
                @csrf
                @method('PUT')

                <div>
                    <label class="block mb-1 font-medium text-gray-700">Unit</label>
                    <select name="unit_id" class="border px-3 py-2 w-full rounded">
                        @foreach($units as $unit)
                            <option value="{{ $unit->id }}" {{ $bonus->unit_id == $unit->id ? 'selected' : '' }}>
                                {{ $unit->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('unit_id')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block mb-1 font-medium text-gray-700">Department</label>
                    <select name="department_id" class="border px-3 py-2 w-full rounded">
                        @foreach($departments as $dept)
                            <option value="{{ $dept->id }}" {{ $bonus->department_id == $dept->id ? 'selected' : '' }}>
                                {{ $dept->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('department_id')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block mb-1 font-medium text-gray-700">Bonus Name</label>
                    <input type="text" name="name" value="{{ $bonus->name }}" 
                           class="border px-3 py-2 w-full rounded">
                    @error('name')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block mb-1 font-medium text-gray-700">Amount</label>
                    <input type="number" step="0.01" name="amount" value="{{ $bonus->amount }}" 
                           class="border px-3 py-2 w-full rounded">
                    @error('amount')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex gap-2">
                    <button type="submit" class="bg-yellow-500 text-white px-4 py-2 rounded hover:bg-yellow-600">
                        Update
                    </button>
                    <a href="{{ route('bonuses.index') }}" 
                       class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
