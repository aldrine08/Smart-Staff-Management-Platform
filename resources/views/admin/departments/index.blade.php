<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800">Departments</h2>
    </x-slot>

    <div class="py-6 px-4 max-w-7xl mx-auto">
        <!-- Success Message -->
        @if(session('success'))
            <div class="bg-green-100 text-green-800 p-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        <!-- Add Department Button -->
        <div x-data="{ addDepartmentOpen: false }" class="mb-4">
            <button
                @click="addDepartmentOpen = true"
                class="bg-purple-600 text-white px-4 py-2 rounded hover:bg-purple-700">
                Add Department
            </button>

            <!-- Modal -->
            <div x-show="addDepartmentOpen" x-transition
                 class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
                <div @click.away="addDepartmentOpen = false"
                     class="bg-white rounded-lg shadow-lg w-full max-w-sm p-6">
                    <h3 class="text-lg font-semibold mb-4 text-gray-800">Add New Department</h3>
                    <form method="POST" action="{{ route('admin.departments.store') }}">
                        @csrf
                        <div class="mb-4">
                            <label class="block text-gray-700 mb-1">Department Name</label>
                            <input type="text" name="name"
                                   class="w-full border rounded px-3 py-2"
                                   placeholder="e.g. Human Resource" required>
                        </div>
                        <div class="flex justify-end space-x-2">
                            <button type="button" @click="addDepartmentOpen = false"
                                    class="px-4 py-2 rounded bg-gray-300 hover:bg-gray-400">
                                Cancel
                            </button>
                            <button type="submit"
                                    class="px-4 py-2 rounded bg-purple-600 text-white hover:bg-purple-700">
                                Save
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Departments Table -->
        <div class="mt-6 bg-white shadow rounded-lg overflow-hidden">
            <table class="w-full table-auto">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-4 py-2 text-left">#</th>
                        <th class="px-4 py-2 text-left">Department Name</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($departments as $department)
                        <tr class="border-b">
                            <td class="px-4 py-2">{{ $loop->iteration }}</td>
                            <td class="px-4 py-2">{{ $department->name }}</td>
                        </tr>
                    @endforeach
                    @if($departments->isEmpty())
                        <tr>
                            <td colspan="2" class="px-4 py-2 text-center text-gray-500">
                                No departments found.
                            </td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>

    <!-- ================= FOOTER ================= -->
<footer class="text-center text-gray-500 text-sm py-6 border-t border-gray-800">
    © {{ date('Y') }} {{ config('app.name') }} — Staff Management System
</footer>

</x-app-layout>
