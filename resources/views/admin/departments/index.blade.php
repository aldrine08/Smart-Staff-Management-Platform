<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800 text-center">Departments</h2>
    </x-slot>

    <div class="py-6 px-4 max-w-5xl mx-auto">

        <!-- Success Message -->
        @if(session('success'))
            <div class="bg-green-100 text-green-800 p-3 rounded mb-4 text-center">
                {{ session('success') }}
            </div>
        @endif

        <!-- Add Department -->
        <div x-data="{ addDepartmentOpen: false }" class="mb-4 text-center">
            <button @click="addDepartmentOpen = true"
                class="bg-purple-600 text-white px-6 py-2 rounded-lg hover:bg-purple-700 shadow transition duration-300">
                Add Department
            </button>

            <!-- Add Modal -->
            <div x-show="addDepartmentOpen"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 scale-90"
                 x-transition:enter-end="opacity-100 scale-100"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100 scale-100"
                 x-transition:leave-end="opacity-0 scale-90"
                 class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 overflow-y-auto">

                <div @click.away="addDepartmentOpen = false"
                     class="bg-white rounded-2xl shadow-xl w-full max-w-md p-6 transform transition-all">

                    <h3 class="text-lg font-semibold mb-4 text-center">Add New Department</h3>

                    <form method="POST" action="{{ route('admin.departments.store') }}">
                        @csrf
                        <input type="text" name="name"
                               class="w-full border rounded px-3 py-2 mb-4 focus:ring focus:ring-purple-200"
                               placeholder="Department name" required>

                        <div class="flex justify-center space-x-3">
                            <button type="button" @click="addDepartmentOpen = false"
                                    class="bg-gray-300 px-4 py-2 rounded-lg hover:bg-gray-400 transition duration-200">
                                Cancel
                            </button>
                            <button type="submit"
                                    class="bg-purple-600 text-white px-4 py-2 rounded-lg hover:bg-purple-700 transition duration-200">
                                Save
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Search -->
        <div class="mb-4 flex justify-end">
            <input type="text" id="search" placeholder="Search Department..."
                   class="border rounded px-3 py-2 w-full max-w-xs focus:ring focus:ring-purple-200">
        </div>

        <!-- Table -->
        <div class="bg-white shadow rounded-xl overflow-hidden">
            <table class="w-full text-center" id="departmentsTable">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-4 py-2">#</th>
                        <th class="px-4 py-2">Department Name</th>
                        <th class="px-4 py-2">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($departments as $department)
                    <tr class="border-b">
                        <td class="px-4 py-2">{{ $loop->iteration }}</td>
                        <td class="px-4 py-2">{{ $department->name }}</td>
                        <td class="px-4 py-2 space-x-2">
                            <button onclick="openEditModal({{ $department->id }}, '{{ $department->name }}')"
                                    class="bg-blue-500 text-white px-3 py-1 rounded hover:bg-blue-600 transition duration-200">
                                Edit
                            </button>
                            <form method="POST" action="{{ route('admin.departments.destroy', $department->id) }}" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                        onclick="return confirm('Delete this department?')"
                                        class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600 transition duration-200">
                                    Delete
                                </button>
                            </form>
                        </td>
                    </tr>
                    @endforeach

                    @if($departments->isEmpty())
                    <tr>
                        <td colspan="3" class="py-4 text-gray-500">No departments found.</td>
                    </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>

    <!-- EDIT MODAL -->
    <div id="editModal" class="fixed inset-0 hidden z-50 flex items-center justify-center bg-black bg-opacity-50 overflow-y-auto">
        <div class="bg-white p-6 rounded-2xl shadow-xl w-full max-w-md transform transition-all">
            <h3 class="text-lg font-semibold mb-4 text-center">Edit Department</h3>

            <form id="editForm" method="POST">
                @csrf
                @method('PUT')
                <input type="text" name="name" id="editName"
                       class="w-full border px-3 py-2 rounded mb-4 focus:ring focus:ring-blue-200" required>

                <div class="flex justify-center space-x-3">
                    <button type="button" onclick="closeEditModal()"
                            class="bg-gray-300 px-4 py-2 rounded-lg hover:bg-gray-400 transition duration-200">
                        Cancel
                    </button>
                    <button type="submit"
                            class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition duration-200">
                        Update
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- JS -->
    <script>
        function openEditModal(id, name) {
            document.getElementById('editModal').classList.remove('hidden');
            document.getElementById('editName').value = name;
            document.getElementById('editForm').action = `/admin/departments/${id}`;
        }
        function closeEditModal() {
            document.getElementById('editModal').classList.add('hidden');
        }

        // Simple search filter
        const searchInput = document.getElementById('search');
        searchInput.addEventListener('input', function() {
            const filter = this.value.toLowerCase();
            const rows = document.querySelectorAll('#departmentsTable tbody tr');
            rows.forEach(row => {
                const name = row.cells[1].textContent.toLowerCase();
                row.style.display = name.includes(filter) ? '' : 'none';
            });
        });
    </script>

    <!-- FOOTER -->
    <footer class="text-center text-gray-500 text-sm py-6 border-t">
        © {{ date('Y') }} {{ config('app.name') }} — Staff Management System
    </footer>
</x-app-layout>