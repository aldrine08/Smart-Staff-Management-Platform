<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">
            Assign Item to Staff
        </h2>
    </x-slot>

    <div class="py-6 max-w-3xl mx-auto">
        <div class="bg-white shadow rounded-lg p-6">

            <form method="POST" action="{{ route('admin.items.assign') }}">
                @csrf

                <!-- Select Item -->
                <div class="mb-4">
                    <label class="block mb-1 font-semibold">Select Item</label>
                    <select name="item_id" required
                        class="select-search w-full border px-3 py-2 rounded">
                        <option value="">-- Choose Item --</option>
                        @foreach($items as $item)
                            <option value="{{ $item->id }}">
                                {{ $item->name }}
                                {{ $item->serial_number ? '(' . $item->serial_number . ')' : '' }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Select Unit -->
                <div class="mb-4">
                    <label class="block mb-1 font-semibold">Select Unit</label>
                    <select name="unit_id" required
                        class="select-search w-full border px-3 py-2 rounded">
                        <option value="">-- Choose Unit --</option>
                        @foreach($units as $unit)
                            <option value="{{ $unit->id }}">
                                {{ $unit->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Select Staff -->
                <div class="mb-4">
                    <label class="block mb-1 font-semibold">Select Staff</label>
                    <select name="user_id" required
                        class="select-search w-full border px-3 py-2 rounded">
                        <option value="">-- Choose Staff --</option>
                        @foreach($staff as $user)
                            <option value="{{ $user->id }}">
                                {{ $user->name }} ({{ $user->unit?->name ?? 'No Unit' }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Condition / Notes -->
                <div class="mb-4">
                    <label class="block mb-1 font-semibold">Condition / Notes</label>
                    <textarea name="condition_notes" rows="3"
                        class="w-full border px-3 py-2 rounded"
                        placeholder="Optional notes about item condition..."></textarea>
                </div>

                <!-- Submit -->
                <button type="submit"
                    class="bg-green-600 text-white px-6 py-2 rounded hover:bg-green-700">
                    Assign Item
                </button>
            </form>

        </div>
    </div>

    <!-- Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    <!-- jQuery -->
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js"></script>

    <!-- Select2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <!-- Initialize Select2 -->
    <script>
        $(document).ready(function () {
            $('.select-search').select2({
                placeholder: 'Start typing to search...',
                allowClear: true,
                width: '100%'
            });
        });
    </script>
</x-app-layout>
