
<x-app-layout>

    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-xl font-bold text-gray-900">
                    Departments
                </h2>
                <p class="mt-1 text-sm text-gray-500">
                    Manage departments and monitor your workforce.
                </p>
            </div>
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto space-y-8">

        {{-- SUCCESS MESSAGE --}}
        @if(session('success'))
            <div class="rounded-xl border border-green-200 bg-green-50 p-4 text-green-800">
                {{ session('success') }}
            </div>
        @endif


{{-- OPERATION ERROR MESSAGE --}}
@if(session('error'))
    <div
        role="alert"
        class="mb-5 flex items-start gap-3
               rounded-xl border border-red-200
               bg-red-50 p-4 text-red-800">

        <span class="text-xl" aria-hidden="true">⚠</span>

        <div>
            <h4 class="text-sm font-bold">
                Action could not be completed
            </h4>

            <p class="mt-1 text-sm">
                {{ session('error') }}
            </p>
        </div>

    </div>
@endif


        {{-- VALIDATION ERRORS --}}
        @if($errors->any())
            <div class="rounded-xl border border-red-200 bg-red-50 p-4 text-red-700">
                <p class="font-semibold mb-2">Please correct the following:</p>
                <ul class="list-disc list-inside text-sm">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- PAGE TITLE AND ADD BUTTON --}}
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">

            <div>
                <h3 class="text-2xl font-bold text-gray-900">
                    Department Overview
                </h3>

                <p class="text-sm text-gray-500 mt-1">
                    Monitor and organize your departments in one place.
                </p>
            </div>

            <button
                type="button"
                onclick="openAddModal()"
                class="inline-flex items-center justify-center gap-2
                       bg-purple-600 text-white font-medium px-5 py-3
                       rounded-xl shadow-sm hover:bg-purple-700
                       transition duration-200">

                <span class="text-xl leading-none">+</span>
                Add Department

            </button>
        </div>

        {{-- OVERVIEW KPI CARDS --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-5">

            {{-- TOTAL DEPARTMENTS --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <div class="flex items-center justify-between mb-4">
                    <span class="text-sm font-medium text-gray-500">
                        Total Departments
                    </span>

                    <span class="p-3 rounded-xl bg-purple-100 text-purple-700">
                        🏢
                    </span>
                </div>

                <h3 class="text-3xl font-bold text-gray-900">
                    {{ $totalDepartments }}
                </h3>

                <p class="text-xs text-gray-500 mt-2">
                    Departments under your management
                </p>
            </div>

            {{-- TOTAL STAFF --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <div class="flex items-center justify-between mb-4">
                    <span class="text-sm font-medium text-gray-500">
                        Total Employees
                    </span>

                    <span class="p-3 rounded-xl bg-blue-100 text-blue-700">
                        👥
                    </span>
                </div>

                <h3 class="text-3xl font-bold text-gray-900">
                    {{ $totalStaff }}
                </h3>

                <p class="text-xs text-gray-500 mt-2">
                    Assigned to your departments
                </p>
            </div>

            {{-- ACTIVE STAFF --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <div class="flex items-center justify-between mb-4">
                    <span class="text-sm font-medium text-gray-500">
                        Active Employees
                    </span>

                    <span class="p-3 rounded-xl bg-green-100 text-green-700">
                        ✓
                    </span>
                </div>

                <h3 class="text-3xl font-bold text-gray-900">
                    {{ $activeStaff }}
                </h3>

                <p class="text-xs text-gray-500 mt-2">
                    Currently active employees
                </p>
            </div>

            {{-- INACTIVE STAFF --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <div class="flex items-center justify-between mb-4">
                    <span class="text-sm font-medium text-gray-500">
                        Inactive Employees
                    </span>

                    <span class="p-3 rounded-xl bg-orange-100 text-orange-700">
                        ◷
                    </span>
                </div>

                <h3 class="text-3xl font-bold text-gray-900">
                    {{ $inactiveStaff }}
                </h3>

                <p class="text-xs text-gray-500 mt-2">
                    Currently inactive employees
                </p>
            </div>

        </div>

        {{-- DEPARTMENT DIRECTORY --}}
        <section class="space-y-5">

            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">

                <div>
                    <h3 class="text-xl font-bold text-gray-900">
                        All Departments
                    </h3>

                    <p class="text-sm text-gray-500 mt-1">
                        Browse and manage individual departments.
                    </p>
                </div>

                {{-- SEARCH --}}
                <div class="relative w-full md:w-80">

                    <input
                        type="search"
                        id="departmentSearch"
                        placeholder="Search departments..."
                        aria-label="Search departments"
                        class="w-full rounded-xl border-gray-300
                               pl-4 pr-4 py-3 text-sm
                               focus:border-purple-500
                               focus:ring-purple-500">

                </div>
            </div>

            {{-- DEPARTMENT CARDS --}}
            <div
                id="departmentsGrid"
                class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">

                @foreach($departments as $department)

                    <div
                        class="department-card bg-white rounded-2xl border
                               border-gray-100 shadow-sm p-6
                               hover:shadow-lg hover:-translate-y-1
                               transition-all duration-300"
                        data-name="{{ $department->name }}">

                        {{-- DEPARTMENT HEADER --}}
                        <div class="flex items-start justify-between gap-3">

                            <div class="flex items-center gap-3 min-w-0">

                                <div class="w-12 h-12 rounded-xl
                                            bg-purple-100 text-purple-700
                                            flex items-center justify-center
                                            text-2xl shrink-0">
                                    🏢
                                </div>

                                <div class="min-w-0">
                                    <h4 class="text-lg font-bold text-gray-900 break-words">
                                        {{ $department->name }}
                                    </h4>

                                    <p class="text-xs text-gray-500">
                                        Department #{{ $department->id }}
                                    </p>
                                </div>
                            </div>

                        </div>

                        {{-- DEPARTMENT STATS --}}
                        <div class="mt-6 grid grid-cols-2 gap-3">

                            <div class="bg-gray-50 rounded-xl p-4">

                                <p class="text-xs text-gray-500">
                                    Total Staff
                                </p>

                                <h5 class="text-2xl font-bold text-gray-900 mt-1">
                                    {{ $department->staff_count }}
                                </h5>

                            </div>

                            <div class="bg-green-50 rounded-xl p-4">

                                <p class="text-xs text-green-700">
                                    Active Staff
                                </p>

                                <h5 class="text-2xl font-bold text-green-700 mt-1">
                                    {{ $department->active_staff_count }}
                                </h5>

                            </div>

                        </div>

                        {{-- DEPARTMENT CREATION DATE --}}
                        <div class="mt-5 text-xs text-gray-500">
                            Created:
                            {{ $department->created_at->format('d M Y') }}
                        </div>

                        {{-- ACTION BUTTONS --}}
                        <div class="mt-5 pt-4 border-t border-gray-100
                                    flex flex-wrap items-center gap-2">

                            
{{-- VIEW DEPARTMENT --}}
<a href="{{ route('admin.departments.show', $department->id) }}"
   class="inline-flex items-center justify-center
          px-4 py-2 rounded-lg
          bg-purple-600 text-white
          text-sm font-medium
          hover:bg-purple-700
          transition duration-200">
    View
</a>


                            {{-- EDIT --}}
                            <button
                                type="button"
                                data-id="{{ $department->id }}"
                                data-name="{{ $department->name }}"
                                onclick="openEditModal(this)"
                                class="px-4 py-2 rounded-lg bg-blue-600
                                       text-white text-sm font-medium
                                       hover:bg-blue-700 transition">
                                Edit
                            </button>

                            {{-- DELETE --}}
                            <form
                                method="POST"
                                action="{{ route('admin.departments.destroy', $department->id) }}"
                                onsubmit="return confirm('Are you sure you want to delete this department?')">

                                @csrf
                                @method('DELETE')

                                <button
                                    type="submit"
                                    class="px-4 py-2 rounded-lg bg-red-600
                                           text-white text-sm font-medium
                                           hover:bg-red-700 transition">
                                    Delete
                                </button>
                            </form>

                        </div>

                    </div>

                @endforeach

            </div>

            {{-- EMPTY DATABASE STATE --}}
            @if($departments->isEmpty())

                <div class="bg-white border border-dashed
                            border-gray-300 rounded-2xl p-12 text-center">

                    <div class="text-5xl mb-4">🏢</div>

                    <h3 class="text-lg font-bold text-gray-900">
                        No departments yet
                    </h3>

                    <p class="text-gray-500 text-sm mt-2">
                        Create your first department to begin organizing staff.
                    </p>

                    <button
                        type="button"
                        onclick="openAddModal()"
                        class="mt-5 px-5 py-3 bg-purple-600
                               text-white rounded-xl hover:bg-purple-700">
                        Add Department
                    </button>

                </div>

            @endif

            {{-- SEARCH EMPTY STATE --}}
            <div
                id="noSearchResults"
                class="hidden bg-white rounded-xl border
                       border-gray-200 p-10 text-center">

                <h3 class="font-semibold text-gray-800">
                    No matching departments
                </h3>

                <p class="text-sm text-gray-500 mt-2">
                    Try searching with another department name.
                </p>

            </div>

        </section>

    </div>

    {{-- ADD DEPARTMENT MODAL --}}
    <div
        id="addModal"
        role="dialog"
        aria-modal="true"
        aria-labelledby="addModalTitle"
        class="hidden fixed inset-0 z-50
               items-center justify-center
               bg-black bg-opacity-50 p-4">

        <div class="bg-white w-full max-w-md rounded-2xl shadow-xl p-6">

            <div class="flex items-center justify-between mb-5">
                <h3 id="addModalTitle" class="text-xl font-bold text-gray-900">
                    Add New Department
                </h3>

                <button
                    type="button"
                    onclick="closeAddModal()"
                    aria-label="Close add department dialog"
                    class="text-gray-500 hover:text-gray-900 text-2xl">
                    &times;
                </button>
            </div>

            <form
                method="POST"
                action="{{ route('admin.departments.store') }}">

                @csrf

                <label
                    for="departmentName"
                    class="block text-sm font-medium text-gray-700 mb-2">
                    Department Name
                </label>

                <input
                    type="text"
                    id="departmentName"
                    name="name"
                    maxlength="255"
                    value="{{ old('name') }}"
                    required
                    class="w-full rounded-xl border-gray-300
                           focus:border-purple-500
                           focus:ring-purple-500"
                    placeholder="e.g. Human Resources">

                <div class="flex justify-end gap-3 mt-6">

                    <button
                        type="button"
                        onclick="closeAddModal()"
                        class="px-5 py-2 rounded-xl bg-gray-100
                               hover:bg-gray-200 text-gray-700">
                        Cancel
                    </button>

                    <button
                        type="submit"
                        class="px-5 py-2 rounded-xl
                               bg-purple-600 hover:bg-purple-700
                               text-white">
                        Save Department
                    </button>

                </div>

            </form>

        </div>

    </div>

    {{-- EDIT DEPARTMENT MODAL --}}
    <div
        id="editModal"
        role="dialog"
        aria-modal="true"
        aria-labelledby="editModalTitle"
        class="hidden fixed inset-0 z-50
               items-center justify-center
               bg-black bg-opacity-50 p-4">

        <div class="bg-white w-full max-w-md rounded-2xl shadow-xl p-6">

            <div class="flex items-center justify-between mb-5">

                <h3 id="editModalTitle" class="text-xl font-bold text-gray-900">
                    Edit Department
                </h3>

                <button
                    type="button"
                    onclick="closeEditModal()"
                    aria-label="Close edit department dialog"
                    class="text-gray-500 hover:text-gray-900 text-2xl">
                    &times;
                </button>

            </div>

            <form id="editForm" method="POST">

                @csrf
                @method('PUT')

                <label
                    for="editName"
                    class="block text-sm font-medium text-gray-700 mb-2">
                    Department Name
                </label>

                <input
                    type="text"
                    name="name"
                    id="editName"
                    maxlength="255"
                    required
                    class="w-full rounded-xl border-gray-300
                           focus:border-blue-500
                           focus:ring-blue-500">

                <div class="flex justify-end gap-3 mt-6">

                    <button
                        type="button"
                        onclick="closeEditModal()"
                        class="px-5 py-2 rounded-xl bg-gray-100
                               hover:bg-gray-200 text-gray-700">
                        Cancel
                    </button>

                    <button
                        type="submit"
                        class="px-5 py-2 rounded-xl
                               bg-blue-600 hover:bg-blue-700
                               text-white">
                        Update Department
                    </button>

                </div>

            </form>

        </div>

    </div>

    {{-- PAGE JAVASCRIPT --}}
    <script>

        function showDepartmentModal(modalId) {
            const modal = document.getElementById(modalId);

            modal.classList.remove('hidden');
            modal.classList.add('flex');

            document.body.style.overflow = 'hidden';

            const input = modal.querySelector('input[name="name"]');

            if (input) {
                input.focus();
            }
        }

        function hideDepartmentModal(modalId) {
            const modal = document.getElementById(modalId);

            modal.classList.add('hidden');
            modal.classList.remove('flex');

            document.body.style.overflow = '';
        }

        function openAddModal() {
            showDepartmentModal('addModal');
        }

        function closeAddModal() {
            hideDepartmentModal('addModal');
        }

        function openEditModal(button) {
            const id = button.dataset.id;
            const name = button.dataset.name;

            document.getElementById('editName').value = name;

            const baseUrl = @json(url('/admin/departments'));

            document.getElementById('editForm').action =
                baseUrl + '/' + encodeURIComponent(id);

            showDepartmentModal('editModal');
        }

        function closeEditModal() {
            hideDepartmentModal('editModal');
        }

        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                closeAddModal();
                closeEditModal();
            }
        });

        // SEARCH DEPARTMENTS
        const searchInput = document.getElementById('departmentSearch');

        searchInput.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase().trim();

            const cards = document.querySelectorAll('.department-card');

            let visibleCount = 0;

            cards.forEach(function(card) {
                const name = card.dataset.name.toLowerCase();

                const matches = name.includes(searchTerm);

                card.classList.toggle('hidden', !matches);

                if (matches) {
                    visibleCount++;
                }
            });

            const noResults = document.getElementById('noSearchResults');

            noResults.classList.toggle(
                'hidden',
                visibleCount > 0 || cards.length === 0
            );
        });

    </script>

</x-app-layout>
