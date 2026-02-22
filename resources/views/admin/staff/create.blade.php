<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800">Add New Staff</h2>
    </x-slot>

    <div class="py-6">
        <div class="mx-auto max-w-3xl sm:px-6 lg:px-8">

            @if(session('success'))
                <div class="mb-4 p-4 bg-green-100 text-green-800 rounded">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white shadow rounded p-6">
                <form action="{{ route('admin.staff.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <!-- Full Name -->
                    <div class="mb-4">
                        <label class="block text-gray-700">Full Name</label>
                        <input 
                            type="text" 
                            name="name" 
                            value="{{ old('name') }}" 
                            class="w-full border rounded p-2"
                        >
                        @error('name') 
                            <p class="text-red-500 text-sm">{{ $message }}</p> 
                        @enderror
                    </div>

                    <!-- Unit -->
                    <div class="mb-4">
                        <label class="block text-gray-700">Unit</label>
                        <select name="unit_id" class="w-full border rounded px-3 py-2 mt-1">
                            <option value="">Select a unit</option>
                            @foreach($units as $unit)
                                <option value="{{ $unit->id }}">{{ $unit->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Department -->
                    <div class="mb-4">
                        <label class="block text-gray-700 font-medium">Department</label>
                        <select name="department_id" class="w-full border rounded px-3 py-2 mt-1">
                            <option value="">Select Department</option>
                            @foreach($departments as $department)
                                <option value="{{ $department->id }}">
                                    {{ $department->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Email -->
                    <div class="mb-4">
                        <label class="block text-gray-700">Email</label>
                        <input 
                            type="email" 
                            name="email" 
                            value="{{ old('email') }}" 
                            class="w-full border rounded p-2"
                        >
                        @error('email') 
                            <p class="text-red-500 text-sm">{{ $message }}</p> 
                        @enderror
                    </div>

                    <!-- Phone -->
                    <div class="mb-4">
                        <label class="block text-gray-700">Phone</label>
                        <input 
                            type="text" 
                            name="phone" 
                            value="{{ old('phone') }}" 
                            class="w-full border rounded p-2"
                        >
                        @error('phone') 
                            <p class="text-red-500 text-sm">{{ $message }}</p> 
                        @enderror
                    </div>

                    <!-- Password -->
                    <div class="mb-4">
                        <label class="block text-gray-700">Password</label>
                        <input 
                            type="password" 
                            name="password" 
                            class="w-full border rounded p-2"
                        >
                        @error('password') 
                            <p class="text-red-500 text-sm">{{ $message }}</p> 
                        @enderror
                    </div>

                    <!-- Confirm Password -->
                    <div class="mb-4">
                        <label class="block text-gray-700">Confirm Password</label>
                        <input 
                            type="password" 
                            name="password_confirmation" 
                            class="w-full border rounded p-2"
                        >
                    </div>

                    <!-- Avatar -->
                    <div class="mb-4">
                        <label class="block text-gray-700">Avatar (Optional)</label>
                        <input 
                            type="file" 
                            name="avatar" 
                            class="w-full border rounded p-2"
                        >
                        @error('avatar') 
                            <p class="text-red-500 text-sm">{{ $message }}</p> 
                        @enderror
                    </div>

                    <!-- Submit Button -->
                    <button 
                        type="submit" 
                        class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700"
                    >
                        Add Staff
                    </button>
                </form>
            </div>

        </div>
    </div>

    <!-- ================= FOOTER ================= -->
<footer class="text-center text-gray-500 text-sm py-6 border-t border-gray-800">
    © {{ date('Y') }} {{ config('app.name') }} — Staff Management System
</footer>


</x-app-layout>
