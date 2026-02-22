<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ config('app.name', 'Laravel') }}</title>

    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif

    <style>
        /* Animated gradient background */
        .animated-bg {
            background: linear-gradient(-45deg, #0f172a, #020617, #111827, #030712);
            background-size: 400% 400%;
            animation: gradientMove 15s ease infinite;
        }

        @keyframes gradientMove {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }
    </style>
</head>

<body class="animated-bg text-gray-100 min-h-screen flex flex-col">

<!-- ================= NAV ================= -->
<header class="w-full px-6 py-5">
    @if (Route::has('login'))
        <nav class="flex items-center justify-between max-w-7xl mx-auto">

            <!-- Logo + System Name -->
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-blue-600 rounded-xl flex items-center justify-center font-bold text-lg">
                    SA
                </div>
                <div>
                    <div class="font-semibold text-lg">Smart Staff </div>
                    <div class="text-xs text-gray-400">Management System</div>
                </div>
            </div>

            <!-- ⚠️ LOGIN + REGISTER — NOT MODIFIED -->
            <div class="flex items-center gap-4">
                @auth
                    <a href="{{ url('/dashboard') }}"
                       class="inline-block px-5 py-1.5 border rounded-sm text-sm leading-normal">
                        Dashboard
                    </a>
                @else
                    <a href="{{ route('login') }}"
                       class="inline-block px-5 py-1.5 border rounded-sm text-sm leading-normal">
                        Log in
                    </a>

                    <!-- @if (Route::has('register'))
                        <a href="{{ route('register') }}"
                           class="inline-block px-5 py-1.5 border rounded-sm text-sm leading-normal">
                            Register
                        </a>
                    @endif -->
                @endauth
            </div>

        </nav>
    @endif
</header>

<!-- ================= HERO ================= -->
<main class="flex-1 flex items-center justify-center px-6">
    <div class="max-w-7xl w-full grid lg:grid-cols-2 gap-14 items-center">

        <!-- LEFT SIDE -->
        <div class="space-y-8">

            <h1 class="text-5xl lg:text-6xl font-bold leading-tight">
                Smart Staff<br>
                Management Platform
            </h1>

            <p class="text-gray-400 text-lg max-w-xl">
                Manage your Staff, departments, units, and attendance with a powerful,
                automated dashboard built for modern organizations.
            </p>

            <!-- Live Clock -->
            <div class="bg-gray-800/60 border border-gray-700 rounded-xl p-5 w-fit">
                <div class="text-sm text-gray-400">Current Time</div>
                <div id="liveClock" class="text-2xl font-semibold tracking-widest"></div>
            </div>

            <!-- Quick Tags -->
            <div class="flex flex-wrap gap-3">
                <span class="px-4 py-2 bg-blue-600/20 border border-blue-500/30 rounded-lg text-sm">
                    Real-time Tracking
                </span>
                <span class="px-4 py-2 bg-emerald-600/20 border border-emerald-500/30 rounded-lg text-sm">
                    Admin Controls
                </span>
                <span class="px-4 py-2 bg-purple-600/20 border border-purple-500/30 rounded-lg text-sm">
                    Email Alerts
                </span>
            </div>

        </div>

        <!-- RIGHT SIDE -->
        <!-- <div class="space-y-6">

            
            <div class="grid grid-cols-3 gap-4">

                <div class="bg-gray-800/60 border border-gray-700 rounded-2xl p-6 text-center">
                    <div class="text-3xl font-bold" data-count="120">0</div>
                    <div class="text-sm text-gray-400 mt-1">Staff</div>
                </div>

                <div class="bg-gray-800/60 border border-gray-700 rounded-2xl p-6 text-center">
                    <div class="text-3xl font-bold" data-count="12">0</div>
                    <div class="text-sm text-gray-400 mt-1">Departments</div>
                </div>

                <div class="bg-gray-800/60 border border-gray-700 rounded-2xl p-6 text-center">
                    <div class="text-3xl font-bold" data-count="8">0</div>
                    <div class="text-sm text-gray-400 mt-1">Units</div>
                </div>

            </div> -->

            <!-- Feature Cards -->
            <div class="grid sm:grid-cols-2 gap-5">

                <div class="bg-gray-800/60 border border-gray-700 rounded-xl p-6">
                    <h3 class="font-semibold mb-2">Clock In / Out</h3>
                    <p class="text-sm text-gray-400">
                        Accurate time logging with automated email notifications.
                    </p>
                </div>

                <div class="bg-gray-800/60 border border-gray-700 rounded-xl p-6">
                    <h3 class="font-semibold mb-2">Staff Assignment</h3>
                    <p class="text-sm text-gray-400">
                        Auto-assign units and departments during creation.
                    </p>
                </div>

                <div class="bg-gray-800/60 border border-gray-700 rounded-xl p-6">
                    <h3 class="font-semibold mb-2">Reports</h3>
                    <p class="text-sm text-gray-400">
                        Generate attendance and performance reports instantly.
                    </p>
                </div>

                <div class="bg-gray-800/60 border border-gray-700 rounded-xl p-6">
                    <h3 class="font-semibold mb-2">Admin Tools</h3>
                    <p class="text-sm text-gray-400">
                        Full control panel for approvals and monitoring.
                    </p>
                </div>

            </div>

        </div>

    </div>
</main>

<!-- ================= FOOTER ================= -->
<footer class="text-center text-gray-500 text-sm py-6 border-t border-gray-800">
    © {{ date('Y') }} {{ config('app.name') }} — Staff Management System
</footer>


<!-- ================= SCRIPTS ================= -->
<script>
/* Live Clock */
function updateClock() {
    const now = new Date();
    document.getElementById('liveClock').innerText =
        now.toLocaleTimeString();
}
setInterval(updateClock, 1000);
updateClock();

/* Counter Animation */
document.querySelectorAll('[data-count]').forEach(el => {
    const target = +el.getAttribute('data-count');
    let count = 0;
    const step = target / 60;

    const update = () => {
        count += step;
        if (count < target) {
            el.innerText = Math.floor(count);
            requestAnimationFrame(update);
        } else {
            el.innerText = target;
        }
    };

    update();
});
</script>

</body>
</html>
