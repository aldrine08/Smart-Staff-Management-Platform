<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800">Edit Staff</h2>
    </x-slot>

<div class="py-6">
    <div class="mx-auto max-w-3xl sm:px-6 lg:px-8">
        <div class="bg-white shadow rounded p-6">
            <form action="{{ route('admin.staff.update', $staff->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <!-- Employment Number -->
                <div class="mb-4">
                    <label class="block font-medium text-gray-700">Employment Number</label>
                    <input type="text" name="employment_number"
                           value="{{ old('employment_number', $staff->employment_number) }}"
                           class="w-full border rounded p-2">
                </div>

                <!-- Full Name -->
                <div class="mb-4">
                    <label class="block font-medium text-gray-700">Employee Names</label>
                    <input type="text" name="name"
                           value="{{ old('name', $staff->name) }}"
                           class="w-full border rounded p-2">
                </div>

                <!-- Unit & Department -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block font-medium text-gray-700">Unit</label>
                        <select name="unit_id" class="w-full border rounded p-2">
                            <option value="">Select a unit</option>
                            @foreach($units as $unit)
                                <option value="{{ $unit->id }}" {{ $staff->unit_id == $unit->id ? 'selected' : '' }}>
                                    {{ $unit->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block font-medium text-gray-700">Department</label>
                        <select name="department_id" class="w-full border rounded p-2">
                            @foreach($departments as $department)
                                <option value="{{ $department->id }}" {{ $staff->department_id == $department->id ? 'selected' : '' }}>
                                    {{ $department->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- Marital Info -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <input type="text" name="marital_status"
                           value="{{ old('marital_status', $staff->marital_status) }}"
                           placeholder="Marital Status"
                           class="w-full border rounded p-2">
                    <input type="text" name="spouse_name"
                           value="{{ old('spouse_name', $staff->spouse_name) }}"
                           placeholder="Spouse Name"
                           class="w-full border rounded p-2">
                </div>

                <!-- Children -->
                @php $children = $staff->children ?? []; @endphp
                <div class="mb-6 p-4 border rounded bg-gray-50">
                    <label class="block font-bold mb-2">Children Details</label>
                    <div class="grid grid-cols-2 gap-4">
                        @for ($i = 0; $i < 3; $i++)
                            <input type="text" name="child_name[]"
                                   value="{{ $children[$i]['name'] ?? '' }}"
                                   placeholder="Child {{ $i+1 }} Name"
                                   class="border rounded p-2 text-sm">
                            <input type="date" name="child_dob[]"
                                   value="{{ $children[$i]['dob'] ?? '' }}"
                                   class="border rounded p-2 text-sm">
                        @endfor
                    </div>
                </div>

                <!-- Next of Kin -->
                <div class="mb-6">
                    <input type="text" name="next_of_kin"
                           value="{{ old('next_of_kin', $staff->next_of_kin) }}"
                           placeholder="Next of Kin"
                           class="w-full border rounded p-2 mb-2">

                    <input type="text" name="next_of_kin_contact"
                           value="{{ old('next_of_kin_contact', $staff->next_of_kin_contact) }}"
                           placeholder="Next of Kin Contact"
                           class="w-full border rounded p-2">
                </div>

                <!-- Academic Qualifications -->
                <div class="mb-4">
                    <label class="block font-medium text-gray-700">Academic Qualifications</label>
                    <textarea name="academic_qualifications"
                              class="w-full border rounded p-2"
                              placeholder="Enter academic background">{{ old('academic_qualifications', $staff->academic_qualifications) }}</textarea>
                </div>

                <!-- Physical Disability -->
                <div class="mb-4">
                    <label class="block font-medium text-gray-700">Physical Disability</label>
                    <input type="text" name="physical_disability"
                           value="{{ old('physical_disability', $staff->physical_disability) }}"
                           placeholder="Specify if any"
                           class="w-full border rounded p-2">
                </div>

                <!-- ID Number & DOB -->
                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block font-medium text-gray-700">ID Number</label>
                        <input type="text" name="id_number"
                               value="{{ old('id_number', $staff->id_number) }}"
                               class="w-full border rounded p-2">
                    </div>
                    <div>
                        <label class="block font-medium text-gray-700">Date of Birth</label>
                        <input type="date" name="dob"
                               value="{{ old('dob', $staff->dob) }}"
                               class="w-full border rounded p-2">
                    </div>
                </div>

                <!-- DOB, Gender & Start Date -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">

    <div>
        <label class="block font-medium text-gray-700">Date of Birth</label>
        <input type="date" name="dob"
               value="{{ old('dob', $staff->dob) }}"
               class="w-full border rounded p-2">
    </div>

    <div>
        <label class="block font-medium text-gray-700">Gender</label>
        <select name="gender" class="w-full border rounded p-2">
            <option value="">Select Gender</option>
            <option value="male" {{ $staff->gender == 'male' ? 'selected' : '' }}>Male</option>
            <option value="female" {{ $staff->gender == 'female' ? 'selected' : '' }}>Female</option>
        </select>
    </div>

    <div>
        <label class="block font-medium text-gray-700">Start Date</label>
        <input type="date" name="start_date"
               value="{{ old('start_date', $staff->start_date) }}"
               class="w-full border rounded p-2">
    </div>

</div>

                <!-- Location -->
                <div class="grid grid-cols-3 gap-4 mb-4">
                    <div>
                        <label class="block font-medium text-gray-700">District</label>
                        <input type="text" name="district"
                               value="{{ old('district', $staff->district) }}"
                               class="w-full border rounded p-2">
                    </div>
                    <div>
                        <label class="block font-medium text-gray-700">Division</label>
                        <input type="text" name="division"
                               value="{{ old('division', $staff->division) }}"
                               class="w-full border rounded p-2">
                    </div>
                    <div>
                        <label class="block font-medium text-gray-700">Ethnicity</label>
                        <input type="text" name="ethnicity"
                               value="{{ old('ethnicity', $staff->ethnicity) }}"
                               class="w-full border rounded p-2">
                    </div>
                </div>

                <!-- Contact Info -->
                <div class="mb-4">
                    <label class="block font-medium text-gray-700">Email</label>
                    <input type="email" name="email"
                           value="{{ old('email', $staff->email) }}"
                           class="w-full border rounded p-2">
                </div>

                <div class="mb-4">
                    <label class="block font-medium text-gray-700">Phone</label>
                    <input type="text" name="phone"
                           value="{{ old('phone', $staff->phone) }}"
                           class="w-full border rounded p-2">
                </div>

                <div class="mb-4">
                    <label class="block font-medium text-gray-700">Physical Address</label>
                    <input type="text" name="physical_address"
                           value="{{ old('physical_address', $staff->physical_address) }}"
                           class="w-full border rounded p-2">
                </div>

                <!-- Files -->
                <div class="mb-4">
                    <label class="block font-medium text-gray-700">Profile Picture</label>
                    <input type="file" name="avatar" class="w-full border rounded p-2">
                </div>

                <div class="mb-6">
                    <label class="block font-medium text-gray-700">Supporting Document</label>
                    <input type="file" name="document" class="w-full border rounded p-2">
                </div>

                <!-- Submit -->
                <button type="submit"
                        class="w-full bg-blue-600 text-white py-3 rounded">
                    Update Staff
                </button>

            </form>
        </div>
    </div>
</div>
</x-app-layout>