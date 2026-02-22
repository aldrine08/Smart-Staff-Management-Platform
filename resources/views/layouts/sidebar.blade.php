@if(auth()->user()->role === 'admin')
<div>
    <!-- Sidebar background for desktop -->
    <div class="fixed inset-0 z-40 lg:hidden" id="mobile-sidebar-backdrop" onclick="toggleMobileSidebar()"></div>

    <!-- Sidebar -->
    <div
        class="fixed inset-y-0 left-0 z-50 w-64 bg-gray-900 text-gray-100 transform -translate-x-full lg:translate-x-0 transition-transform duration-200 ease-in-out"
        id="sidebar">
        <!-- Logo / Brand -->
        <div class="flex items-center justify-center h-16 border-b border-gray-700">
            <span class="text-lg font-bold">HRMS</span>
        </div>

        <!-- Navigation -->
        <nav class="px-4 py-6 flex-1 overflow-y-auto">
            <!-- Dashboard Section -->
            <div class="mb-4">
                <button class="w-full flex items-center justify-between py-2 px-3 text-gray-200 hover:bg-gray-800 rounded"
                    onclick="toggleMenu('dashboard-menu')">
                    <span class="flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 3h18v18H3V3z" />
                        </svg>
                        Dashboard
                    </span>
                    <svg id="dashboard-arrow" xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 transition-transform"
                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 9l-7 7-7-7" />
                    </svg>
                </button>
                <div id="dashboard-menu" class="ml-6 mt-2 space-y-1 hidden">
                    <a href="{{ route('admin.staff.create') }}"
                        class="block py-1 px-2 rounded hover:bg-gray-800">Add New Staff</a>
                    <a href="{{ route('admin.departments.store') }}"
                        class="block py-1 px-2 rounded hover:bg-gray-800">Add Departments</a>                    
                    <a href="{{ route('admin.clockin-settings.update') }}"
                        class="block py-1 px-2 rounded hover:bg-gray-800">Clock In Settings</a>
                </div>
            </div>

            <!-- Attendance -->
            <div class="mb-4">
                <a href="{{ route('admin.attendance') }}" class="items-center justify-between py-2 px-3 text-gray-200 hover:bg-gray-800 rounded flex gap-2">
                    <span> 📆Attendance</span>
                </a>
            </div>

            <div class="mb-4">
            <a href="{{ route('admin.offdays.index') }}"
   class="items-center justify-between py-2 px-3 text-gray-200 hover:bg-gray-800 rounded flex gap-2">
    📅 Off Day Requests
</a>
</div>

<!-- <div>
<li>
    <a href="{{ route('payroll.index') }}" class="block px-4 py-2 hover:bg-gray-700">Payroll Process</a>
</li>
</div> -->

<!-- Payroll Settings -->
<div class="mb-4">
    <button class="w-full flex items-center justify-between py-2 px-3 text-gray-200 hover:bg-gray-800 rounded"
        onclick="toggleMenu('payroll-settings-menu')">
        <span class="flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M12 8v4l3 3" />
            </svg>
            Payroll Settings
        </span>
        <svg id="payroll-settings-menu-arrow" xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 transition-transform"
            fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M19 9l-7 7-7-7" />
        </svg>
    </button>
    <div id="payroll-settings-menu" class="ml-6 mt-2 space-y-1 hidden">
        <a href="{{ route('salary_settings.index') }}" class="block py-1 px-2 rounded hover:bg-gray-800">Salary Settings</a>
        <a href="{{ route('deductions.index') }}" class="block py-1 px-2 rounded hover:bg-gray-800">Deductions</a>
        <a href="{{ route('bonuses.index') }}" class="block py-1 px-2 rounded hover:bg-gray-800">Bonuses</a>
        <a href="{{ route('payroll.index') }}" class="block py-1 px-2 rounded hover:bg-gray-800">Payroll Process</a>
        <a href="{{ route('payroll.processed') }}" class="block py-1 px-2 rounded hover:bg-gray-800">Processed Payrolls</a>


    </div>
</div>

<div class="mb-4">
    <button class="w-full flex items-center justify-between py-2 px-3 text-gray-200 hover:bg-gray-800 rounded"
        onclick="toggleMenu('items-menu')">
        <span class="flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M12 8v4l3 3" />
            </svg>
            items features
        </span>
        <svg id="items-menu-arrow" xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 transition-transform"
            fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M19 9l-7 7-7-7" />
        </svg>
    </button>
    <div id="items-menu" class="ml-6 mt-2 space-y-1 hidden">
        <a href="{{ route('admin.items.index') }}" class="block py-1 px-2 rounded hover:bg-gray-800">Items List</a>
        <a href="{{ route('admin.items.create') }}" class="block py-1 px-2 rounded hover:bg-gray-800">Add New Item</a>
        <a href="{{ route('admin.items.assign.form') }}" class="block py-1 px-2 rounded hover:bg-gray-800">Assign Items</a>

    </div>
</div>




        </nav>
    </div>

    <!-- Hamburger Menu for Mobile -->
    <div class="lg:hidden fixed top-4 left-4 z-50">
        <button onclick="toggleMobileSidebar()"
            class="p-2 rounded bg-gray-800 text-gray-100 focus:outline-none">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M4 6h16M4 12h16M4 18h16" />
            </svg>
        </button>
    </div>
</div>
@endif



<!-- Toggle Scripts -->
<script>
    function toggleMenu(id) {
        const menu = document.getElementById(id);
        const arrow = document.getElementById(id + '-arrow');
        menu.classList.toggle('hidden');
        arrow.classList.toggle('rotate-180');
    }

    function toggleMobileSidebar() {
        const sidebar = document.getElementById('sidebar');
        const backdrop = document.getElementById('mobile-sidebar-backdrop');
        sidebar.classList.toggle('-translate-x-full');
        backdrop.classList.toggle('hidden');
    }
</script>
