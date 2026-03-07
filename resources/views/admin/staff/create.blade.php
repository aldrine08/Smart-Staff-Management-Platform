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

                    <!-- 1. Employment Number -->
                    <div class="mb-4">
                        <label class="block text-gray-700 font-medium">1. Employment Number</label>
                        <input type="text" name="employment_number" value="{{ old('employment_number') }}" class="w-full border rounded p-2">
                    </div>

                    <!-- 2. Full Name -->
                    <div class="mb-4">
                        <label class="block text-gray-700 font-medium">2. Employee Names</label>
                        <input type="text" name="name" value="{{ old('name') }}" class="w-full border rounded p-2">
                        @error('name') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
                    </div>

                    <!-- 3. Unit & Department -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block text-gray-700 font-medium">Unit</label>
                            <select name="unit_id" class="w-full border rounded px-3 py-2 mt-1">
                                <option value="">Select a unit</option>
                                @foreach($units as $unit)
                                    <option value="{{ $unit->id }}">{{ $unit->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-gray-700 font-medium">3. Department</label>
                            <select name="department_id" class="w-full border rounded px-3 py-2 mt-1">
                                <option value="">Select Department</option>
                                @foreach($departments as $department)
                                    <option value="{{ $department->id }}">{{ $department->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- 4 & 5. Marital Status & Spouse -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block text-gray-700 font-medium">4. Marital Status</label>
                            <input type="text" name="marital_status" class="w-full border rounded p-2">
                        </div>
                        <div>
                            <label class="block text-gray-700 font-medium">5. Name of Spouse</label>
                            <input type="text" name="spouse_name" class="w-full border rounded p-2">
                        </div>
                    </div>

                    <!-- 6. Children -->
                    <div class="mb-6 p-4 border rounded bg-gray-50">
                        <label class="block text-gray-700 font-bold mb-2">6. Children Details</label>
                        <div class="grid grid-cols-2 gap-4">
                            <span class="text-sm font-semibold">Names</span>
                            <span class="text-sm font-semibold">Date of Birth</span>
                            @for ($i = 1; $i <= 3; $i++)
                                <input type="text" name="child_name[]" placeholder="Child {{ $i }}" class="border rounded p-2 text-sm">
                                <input type="date" name="child_dob[]" class="border rounded p-2 text-sm">
                            @endfor
                        </div>
                    </div>

                    <!-- 7. Next of Kin -->
                    <div class="mb-6">
                        <label class="block text-gray-700 font-bold">7. Next of Kin & Relationship</label>
                        <input type="text" name="next_of_kin" placeholder="Name/Relationship" class="w-full border rounded p-2 mb-2">
                        <input type="text" name="next_of_kin_contact" placeholder="Address/Telephone" class="w-full border rounded p-2">
                    </div>

                    <!-- 8. Academic & Medical -->
                    <div class="mb-4">
                        <label class="block text-gray-700 font-medium">8. Academic Qualifications</label>
                        <textarea name="academic_qualifications" class="w-full border rounded p-2" rows="2"></textarea>
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 font-medium">9. Any Physical Disability</label>
                        <input type="text" name="physical_disability" class="w-full border rounded p-2">
                    </div>

                    <!-- Identification & Location -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block text-gray-700 font-medium">12(a). ID Card Number</label>
                            <input type="text" name="id_number" class="w-full border rounded p-2">
                        </div>
                        <div>
                            <label class="block text-gray-700 font-medium">13. Date of Birth</label>
                            <input type="date" name="dob" class="w-full border rounded p-2">
                        </div>
                    </div>

                    <div class="grid grid-cols-2 md:grid-cols-3 gap-4 mb-4">
                        <input type="text" name="district" placeholder="District" class="border rounded p-2">
                        <input type="text" name="division" placeholder="Division" class="border rounded p-2">
                        <input type="text" name="ethnicity" placeholder="Ethnicity" class="border rounded p-2">
                    </div>

                    <!-- Contact Info (Original Fields) -->
                    <div class="mb-4">
                        <label class="block text-gray-700 font-medium">Email</label>
                        <input type="email" name="email" value="{{ old('email') }}" class="w-full border rounded p-2">
                        @error('email') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 font-medium">Phone</label>
                        <input type="text" name="phone" value="{{ old('phone') }}" class="w-full border rounded p-2">
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 font-medium">14. Current Physical Address</label>
                        <input type="text" name="physical_address" class="w-full border rounded p-2">
                    </div>

                    <!-- Security & Files -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block text-gray-700">Password</label>
                            <input type="password" name="password" class="w-full border rounded p-2">
                        </div>
                        <div>
                            <label class="block text-gray-700">Confirm Password</label>
                            <input type="password" name="password_confirmation" class="w-full border rounded p-2">
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700">Avatar (Optional)</label>
                        <input type="file" name="avatar" class="w-full border rounded p-2">
                    </div>

                    <div class="form-group mb-6">
                        <label class="block text-gray-700 font-bold">15. Upload Staff Documents (PDF)</label>
                        <p class="text-xs text-gray-500 mb-2">(ID Copies, NSSF, NHIF, PIN, Academic Certificates)</p>
                        <input type="file" name="document" class="w-full border rounded p-2" accept="application/pdf">
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" class="w-full bg-blue-600 text-white font-bold px-4 py-3 rounded hover:bg-blue-700 transition">
                        Add Staff Member
                    </button>
                </form>
            </div>
        </div>
    </div>

    <footer class="text-center text-gray-500 text-sm py-6 border-t border-gray-800">
        © {{ date('Y') }} {{ config('app.name') }} — Staff Management System
    </footer>
</x-app-layout>
