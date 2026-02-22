<!-- <a href="{{ route('attendance.export') }}"
   class="bg-green-600 text-white px-4 py-2 rounded">
    Export to Excel
</a> -->


{{-- Simple version --}}
<div class="bg-white rounded-lg shadow p-6">
    <div class="flex justify-between items-center mb-4">
        <h2 class="text-xl font-semibold text-gray-800">Reports</h2>
        <a href="{{ route('attendance.export') }}"
           class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg transition duration-200">
            Export to Excel
        </a>
    </div>
    
    {{-- Your report content here --}}
    <div class="mt-4">
        <!-- Report data goes here -->
    </div>
</div>