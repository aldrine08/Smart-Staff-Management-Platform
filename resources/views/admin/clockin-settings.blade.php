<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-blue-800 leading-tight">Clock-In Settings</h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8 bg-white p-6 rounded shadow">

            <form action="{{ route('admin.clockin-settings.update') }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-4">
                    <label class="block font-medium mb-1">Clock-In Start Time</label>
                    <input type="time" name="start_time" value="{{ $setting->start_time }}" class="border p-2 rounded w-full">
                </div>

                <div class="mb-4">
                    <label class="block font-medium mb-1">Working Days</label>
                    <select name="working_days[]" multiple class="border p-2 rounded w-full">
                        @foreach(['Mon','Tue','Wed','Thu','Fri','Sat','Sun'] as $day)
                            <option value="{{ $day }}"
                                {{ in_array($day, $setting->working_days ?? []) ? 'selected' : '' }}>
                                {{ $day }}
                            </option>
                        @endforeach
                    </select>
                    <p class="text-sm text-gray-500 mt-1">Hold Ctrl/Cmd to select multiple days.</p>
                </div>

                <button type="submit" class="bg-green-600 text-white py-2 px-4 rounded">
                    Save Settings
                </button>
            </form>
        </div>
    </div>

    <!-- ================= FOOTER ================= -->
<footer class="text-center text-gray-500 text-sm py-6 border-t border-gray-800">
    © {{ date('Y') }} {{ config('app.name') }} — Staff Management System
</footer>

</x-app-layout>
