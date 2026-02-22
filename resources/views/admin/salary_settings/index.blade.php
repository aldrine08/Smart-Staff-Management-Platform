<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800">Salary Settings</h2>
    </x-slot>

    <div class="py-6 max-w-7xl mx-auto sm:px-6 lg:px-8">

        <!-- Add Button -->
        <div class="mb-4 flex justify-between items-center">
            <a href="{{ route('salary_settings.create') }}" 
               class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
               Add Salary Setting
            </a>

            <!-- Filter Form -->
            <form action="{{ route('salary_settings.index') }}" method="GET" class="bg-white rounded shadow p-4 mb-6 grid grid-cols-1 md:grid-cols-4 gap-4">
                <select name="unit_id" class="border rounded px-2 py-1">
                    <option value="">All Units</option>
                    @foreach($units as $unit)
                        <option value="{{ $unit->id }}" {{ request('unit_id') == $unit->id ? 'selected' : '' }}>
                            {{ $unit->name }}
                        </option>
                    @endforeach
                </select>

                <select name="department_id" class="border rounded px-2 py-1">
                    <option value="">All Departments</option>
                    @foreach($departments as $department)
                        <option value="{{ $department->id }}" {{ request('department_id') == $department->id ? 'selected' : '' }}>
                            {{ $department->name }}
                        </option>
                    @endforeach
                </select>

                <button type="submit" class="bg-gray-600 text-white px-3 py-1 rounded hover:bg-gray-700">
                    Filter
                </button>
                <a href="{{ route('salary_settings.index') }}" class="bg-gray-300 text-gray-800 px-3 py-1 rounded hover:bg-gray-400">
                    Reset
                </a>
            </form>
        </div>

        <!-- Success Message -->
        @if(session('success'))
            <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-2 rounded">
                {{ session('success') }}
            </div>
        @endif

        <!-- Salary Settings Table -->
        <div class="overflow-x-auto bg-white shadow rounded-lg">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Unit</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Department</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Daily Salary</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($salarySettings as $setting)
                    <tr>
                        <td class="px-6 py-4">{{ $setting->unit->name ?? 'N/A' }}</td>
                        <td class="px-6 py-4">{{ $setting->department->name ?? 'N/A' }}</td>
                        <td class="px-6 py-4">{{ number_format($setting->daily_salary, 2) }}</td>
                        <td class="px-6 py-4 flex gap-2">
                            <a href="{{ route('salary_settings.edit', $setting->id) }}" 
                               class="bg-yellow-500 text-white px-2 py-1 rounded hover:bg-yellow-600 text-sm">Edit</a>
                            <form action="{{ route('salary_settings.destroy', $setting->id) }}" method="POST" 
                                  onsubmit="return confirm('Delete this salary setting?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="bg-red-600 text-white px-2 py-1 rounded hover:bg-red-700 text-sm">
                                    Delete
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-6 py-4 text-center text-gray-500">No salary settings found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>
