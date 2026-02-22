<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">
            Create Item
        </h2>
    </x-slot>

    <div class="py-6 max-w-3xl mx-auto">
        <div class="bg-white shadow rounded-lg p-6">

            <form method="POST" action="{{ route('admin.items.store') }}">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                    <div>
                        <label class="block mb-1">Item Name</label>
                        <input type="text" name="name" required class="w-full border px-3 py-2 rounded">
                    </div>

                    <div>
                        <label class="block mb-1">Category</label>
                        <input type="text" name="category" class="w-full border px-3 py-2 rounded">
                    </div>

                    <div>
                        <label class="block mb-1">Serial Number</label>
                        <input type="text" name="serial_number" class="w-full border px-3 py-2 rounded">
                    </div>

                    <div>
                        <label class="block mb-1">Quantity</label>
                        <input type="number" name="quantity" value="1" min="1" class="w-full border px-3 py-2 rounded">
                    </div>

                    <div>
                        <label class="block mb-1">Unit</label>
                        <select name="unit_id" class="w-full border px-3 py-2 rounded">
                            <option value="">-- Optional --</option>
                            @foreach($units as $unit)
                                <option value="{{ $unit->id }}">{{ $unit->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block mb-1">Department</label>
                        <select name="department_id" class="w-full border px-3 py-2 rounded">
                            <option value="">-- Optional --</option>
                            @foreach($departments as $department)
                                <option value="{{ $department->id }}">{{ $department->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="mt-4">
                    <label class="block mb-1">Notes</label>
                    <textarea name="notes" rows="3" class="w-full border px-3 py-2 rounded"></textarea>
                </div>

                <div class="mt-6">
                    <button class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700">
                        Save Item
                    </button>
                </div>

            </form>

        </div>
    </div>
</x-app-layout>
