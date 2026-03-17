<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800">
            Staff Profile
        </h2>
    </x-slot>

    <div class="max-w-5xl mx-auto py-6">

        <div class="bg-white shadow rounded-lg p-6">

            <!-- Profile Header -->
            <div class="flex items-center space-x-6 mb-6">
                <img class="w-24 h-24 rounded-full object-cover border"
                     src="{{ $staff->avatar ? asset('storage/'.$staff->avatar) : 'https://ui-avatars.com/api/?name='.$staff->name }}">

                <div>
                    <h2 class="text-2xl font-bold">{{ $staff->name }}</h2>
                    <p class="text-gray-600">{{ $staff->email }}</p>
                </div>
            </div>

            <!-- Basic Info -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">

                <p><strong>Phone:</strong> {{ $staff->phone ?? '—' }}</p>
                <p><strong>Unit:</strong> {{ $staff->unit->name ?? '—' }}</p>
                <p><strong>Department:</strong> {{ $staff->department->name ?? '—' }}</p>
                <p><strong>Start Date:</strong> {{ $staff->start_date ?? '—' }}</p>

            </div>

            <hr class="my-6">

            <!-- Personal Details -->
            <h3 class="text-lg font-semibold mb-3">Personal Details</h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                <p><strong>Marital Status:</strong> {{ $staff->marital_status ?? '—' }}</p>
                <p><strong>Spouse Name:</strong> {{ $staff->spouse_name ?? '—' }}</p>
                <p><strong>ID Number:</strong> {{ $staff->id_number ?? '—' }}</p>
                <p><strong>Date of Birth:</strong> {{ $staff->dob ?? '—' }}</p>

                <p><strong>District:</strong> {{ $staff->district ?? '—' }}</p>
                <p><strong>Division:</strong> {{ $staff->division ?? '—' }}</p>
                <p><strong>Ethnicity:</strong> {{ $staff->ethnicity ?? '—' }}</p>

            </div>

            <hr class="my-6">

            <!-- Next of Kin -->
            <h3 class="text-lg font-semibold mb-3">Next of Kin</h3>

            <p><strong>Name:</strong> {{ $staff->next_of_kin ?? '—' }}</p>
            <p><strong>Contact:</strong> {{ $staff->next_of_kin_contact ?? '—' }}</p>

            <hr class="my-6">

            <!-- Academic -->
            <h3 class="text-lg font-semibold mb-3">Academic & Medical</h3>

            <p><strong>Qualifications:</strong> {{ $staff->academic_qualifications ?? '—' }}</p>
            <p><strong>Disability:</strong> {{ $staff->physical_disability ?? '—' }}</p>

            <hr class="my-6">

            <!-- Address -->
            <h3 class="text-lg font-semibold mb-3">Address</h3>

            <p><strong>Physical Address:</strong> {{ $staff->physical_address ?? '—' }}</p>

            <hr class="my-6">

            <!-- Document -->
            <h3 class="text-lg font-semibold mb-3">Documents</h3>

            @if($staff->document)
                <a href="{{ asset('storage/'.$staff->document) }}"
                   target="_blank"
                   class="text-blue-600 underline">
                    View Uploaded Document
                </a>
            @else
                <p>No document uploaded</p>
            @endif

        </div>

    </div>
</x-app-layout>