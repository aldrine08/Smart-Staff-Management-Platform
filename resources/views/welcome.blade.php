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
    html{
        scroll-behavior:smooth;
    }

    body{
        overflow-x:hidden;
    }

    .animated-bg{
        background:
        radial-gradient(circle at top left,#5b21b620 0%,transparent 35%),
        radial-gradient(circle at bottom right,#d4a01715 0%,transparent 35%),
        linear-gradient(135deg,#09090b,#111827,#1f2937,#0f172a);

        background-size:400% 400%;
        animation:gradientMove 18s ease infinite;
    }

    @keyframes gradientMove{

        0%{
            background-position:0% 50%;
        }

        50%{
            background-position:100% 50%;
        }

        100%{
            background-position:0% 50%;
        }

    }

    .glass{

        backdrop-filter:blur(18px);

        background:rgba(255,255,255,.05);

        border:1px solid rgba(255,255,255,.08);

        box-shadow:0 15px 40px rgba(0,0,0,.30);

    }

    .hero-title{

        background:linear-gradient(90deg,#ffffff,#d4a017);

        -webkit-background-clip:text;

        -webkit-text-fill-color:transparent;

    }

    .pulse-dot{

        width:10px;

        height:10px;

        border-radius:999px;

        background:#22c55e;

        animation:pulse 1.5s infinite;

    }

    @keyframes pulse{

        0%{

            transform:scale(1);

            opacity:1;

        }

        100%{

            transform:scale(2);

            opacity:0;

        }

    }

    .feature-card{

        transition:.35s;

    }

    .feature-card:hover{

        transform:translateY(-8px);

        border-color:#d4a017;

    }

</style>
</head>

<body class="animated-bg text-gray-100 min-h-screen flex flex-col">

<!-- ================= NAV ================= -->
<header class="sticky top-0 z-50 border-b border-white/10 glass">

@if(Route::has('login'))

<nav class="max-w-7xl mx-auto px-8 py-5 flex items-center justify-between">

<div class="flex items-center gap-4">

<div class="w-14 h-14 rounded-2xl bg-gradient-to-r from-purple-700 to-yellow-500 flex items-center justify-center text-white text-xl font-bold shadow-lg">

SR

</div>

<div class="fixed inset-0 overflow-hidden -z-10">

    <div class="absolute w-96 h-96 bg-purple-700/20 blur-[120px] rounded-full -left-24 top-10"></div>

    <div class="absolute w-[500px] h-[500px] bg-yellow-500/10 blur-[150px] rounded-full right-0 bottom-0"></div>

    <div class="absolute w-72 h-72 bg-blue-700/10 blur-[120px] rounded-full left-1/2 top-1/3"></div>

</div>

<div>

<h2 class="text-2xl font-bold tracking-wide">

SMART ROYAL

</h2>

<p class="text-sm text-gray-300">

Human Resource Management Platform

</p>

<p class="text-xs text-yellow-400">

Powered by WRLD SOLUTIONS

</p>

<div class="mt-2 inline-flex items-center gap-2 text-xs">

    <span class="w-2 h-2 rounded-full bg-green-400 animate-pulse"></span>

    Enterprise Edition

</div>

</div>

</div>

<!-- LOGIN BUTTON (UNCHANGED) -->

<div class="flex items-center gap-4">

@auth

<a href="{{ url('/dashboard') }}"

class="px-6 py-3 rounded-xl bg-gradient-to-r from-purple-700 to-yellow-500 text-white font-semibold transition duration-300 hover:scale-105 shadow-lg">

Dashboard

</a>

@else

<a href="{{ route('login') }}"

class="px-6 py-3 rounded-xl bg-gradient-to-r from-purple-700 to-yellow-500 text-white font-semibold transition duration-300 hover:scale-105 shadow-lg">

Log in

</a>

@endauth

</div>

</nav>

@endif

</header>

<!-- ================= HERO ================= -->
<main class="flex-1 flex items-center">

<div class="max-w-7xl mx-auto px-8 py-20 grid lg:grid-cols-2 gap-16 items-center">

<!-- LEFT -->

<div>

<div class="inline-flex items-center gap-3 bg-purple-600/20 border border-purple-400/30 rounded-full px-5 py-2 mb-8">

<div class="pulse-dot"></div>

<span class="text-sm">

Trusted by Smart Royal Career Consultant

</span>

</div>

<h1 class="hero-title text-6xl font-black leading-tight">

Human Resource

<br>

Management Platform

</h1>

<p class="mt-8 text-gray-300 text-xl leading-9">

Empowering <strong>Smart Royal Career Consultant</strong> with intelligent recruitment, employee administration, attendance management, payroll processing and organizational excellence.

</p>

<div class="mt-10 flex flex-wrap gap-4">

<span class="px-5 py-3 rounded-xl bg-purple-700/20 border border-purple-500/20">

Recruitment

</span>

<span class="px-5 py-3 rounded-xl bg-blue-700/20 border border-blue-500/20">

Attendance

</span>

<span class="px-5 py-3 rounded-xl bg-yellow-500/10 border border-yellow-500/20">

Payroll

</span>

<span class="px-5 py-3 rounded-xl bg-emerald-500/10 border border-emerald-500/20">

Reports

</span>

</div>

<div class="mt-10 flex gap-8">

<div class="glass rounded-2xl p-6">

<div class="text-sm text-gray-400">

Current Time

</div>

<div id="liveClock" class="text-3xl font-bold mt-2"></div>

</div>

<div class="glass rounded-2xl p-6">

<div class="text-sm text-gray-400">

Platform Status

</div>

<div class="flex items-center gap-3 mt-2">

<div class="pulse-dot"></div>

<span class="font-semibold">

ONLINE

</span>

</div>

</div>

</div>

</div>

<!-- RIGHT -->

<div>

<div class="glass rounded-3xl p-8">

<div class="flex justify-between items-center mb-8">

<h2 class="text-2xl font-bold">

Platform Modules

</h2>

<span class="text-green-400">

Operational

</span>

</div>

<div class="grid gap-4">

<div class="feature-card glass rounded-xl p-5 flex justify-between">

<span>Employee Management</span>

<span>✔</span>

</div>

<div class="feature-card glass rounded-xl p-5 flex justify-between">

<span>Attendance Monitoring</span>

<span>✔</span>

</div>

<div class="feature-card glass rounded-xl p-5 flex justify-between">

<span>Recruitment Support</span>

<span>✔</span>

</div>

<div class="feature-card glass rounded-xl p-5 flex justify-between">

<span>Payroll Processing</span>

<span>✔</span>

</div>

<div class="feature-card glass rounded-xl p-5 flex justify-between">

<span>Departments & Units</span>

<span>✔</span>

</div>

<div class="feature-card glass rounded-xl p-5 flex justify-between">

<span>Performance Reports</span>

<span>✔</span>

</div>

</div>

<div class="mt-8 border-t border-white/10 pt-6">

<div class="flex justify-between">

<span>Technology Partner</span>

<span class="text-yellow-400">

WRLD SOLUTIONS

</span>

</div>

</div>

</div>

</div>

</div>

</main>

<!-- ========================================= -->
<!-- ABOUT SMART ROYAL -->
<!-- ========================================= -->

<section class="py-24 px-8">

    <div class="max-w-7xl mx-auto">

        <div class="text-center mb-16">

            <span class="uppercase tracking-[0.3em] text-yellow-400 text-sm">
                About Smart Royal
            </span>

            <h2 class="mt-4 text-5xl font-black">
                Driving Excellence Through
                <span class="text-yellow-400">
                    People & Technology
                </span>
            </h2>

            <p class="mt-8 max-w-3xl mx-auto text-lg text-gray-300 leading-8">

                Smart Royal Career Consultant is committed to helping
                organizations build productive workforces through
                professional recruitment, HR consulting, staff outsourcing,
                employee development and organizational support.

                This Human Resource Management Platform simplifies daily HR
                operations while improving efficiency, accountability and
                workforce productivity.

            </p>

        </div>

        <div class="grid lg:grid-cols-2 gap-12">

            <!-- Left -->

            <div class="glass rounded-3xl p-10">

                <h3 class="text-3xl font-bold mb-8">

                    Core Services

                </h3>

                <div class="space-y-6">

                    <div class="flex items-center justify-between">

                        <span>Recruitment & Talent Placement</span>

                        <span>✓</span>

                    </div>

                    <div class="flex items-center justify-between">

                        <span>HR Consulting</span>

                        <span>✓</span>

                    </div>

                    <div class="flex items-center justify-between">

                        <span>Staff Outsourcing</span>

                        <span>✓</span>

                    </div>

                    <div class="flex items-center justify-between">

                        <span>Corporate Training</span>

                        <span>✓</span>

                    </div>

                    <div class="flex items-center justify-between">

                        <span>Performance Management</span>

                        <span>✓</span>

                    </div>

                    <div class="flex items-center justify-between">

                        <span>Career Advisory</span>

                        <span>✓</span>

                    </div>

                </div>

            </div>

            <!-- Right -->

            <div class="glass rounded-3xl p-10">

                <h3 class="text-3xl font-bold mb-8">

                    Platform Highlights

                </h3>

                <div class="grid grid-cols-2 gap-5">

                    <div class="feature-card glass rounded-2xl p-6">

                        👥

                        <h4 class="mt-5 font-bold">

                            Employee Records

                        </h4>

                    </div>

                    <div class="feature-card glass rounded-2xl p-6">

                        🕒

                        <h4 class="mt-5 font-bold">

                            Attendance

                        </h4>

                    </div>

                    <div class="feature-card glass rounded-2xl p-6">

                        💰

                        <h4 class="mt-5 font-bold">

                            Payroll

                        </h4>

                    </div>

                    <div class="feature-card glass rounded-2xl p-6">

                        📊

                        <h4 class="mt-5 font-bold">

                            Reports

                        </h4>

                    </div>

                    <div class="feature-card glass rounded-2xl p-6">

                        🏢

                        <h4 class="mt-5 font-bold">

                            Departments

                        </h4>

                    </div>

                    <div class="feature-card glass rounded-2xl p-6">

                        🔐

                        <h4 class="mt-5 font-bold">

                            Role Security

                        </h4>

                    </div>

                </div>

            </div>

        </div>

    </div>

</section>

<!-- ========================================= -->
<!-- WHY CHOOSE THIS PLATFORM -->
<!-- ========================================= -->

<section class="pb-24 px-8">

    <div class="max-w-7xl mx-auto">

        <div class="glass rounded-[35px] p-14">

            <div class="text-center">

                <span class="uppercase tracking-[0.3em] text-yellow-400 text-sm">

                    Enterprise Platform

                </span>

                <h2 class="mt-4 text-5xl font-black">

                    Why Organizations Choose This Platform

                </h2>

            </div>

            <div class="grid md:grid-cols-3 gap-8 mt-14">

                <div class="feature-card glass rounded-2xl p-8">

                    <div class="text-5xl mb-5">

                        ⚡

                    </div>

                    <h3 class="font-bold text-xl">

                        Faster HR Operations

                    </h3>

                    <p class="mt-4 text-gray-400">

                        Reduce manual work through digital employee management,
                        attendance tracking and workflow automation.

                    </p>

                </div>

                <div class="feature-card glass rounded-2xl p-8">

                    <div class="text-5xl mb-5">

                        🔒

                    </div>

                    <h3 class="font-bold text-xl">

                        Secure & Reliable

                    </h3>

                    <p class="mt-4 text-gray-400">

                        Role-based access, secure authentication and centralized
                        employee information management.

                    </p>

                </div>

                <div class="feature-card glass rounded-2xl p-8">

                    <div class="text-5xl mb-5">

                        📈

                    </div>

                    <h3 class="font-bold text-xl">

                        Better Decision Making

                    </h3>

                    <p class="mt-4 text-gray-400">

                        Generate attendance, payroll and workforce reports
                        instantly for informed management decisions.

                    </p>

                </div>

            </div>

        </div>

    </div>

</section>

<!-- ===================================================== -->
<!-- PLATFORM IMPACT -->
<!-- ===================================================== -->

<section class="py-24 px-8">

    <div class="max-w-7xl mx-auto">

        <div class="text-center mb-16">

            <span class="uppercase tracking-[0.3em] text-yellow-400 text-sm">
                Platform Impact
            </span>

            <h2 class="mt-4 text-5xl font-black">
                Built For Modern Organizations
            </h2>

            <p class="mt-6 text-gray-300 max-w-3xl mx-auto">
                From recruitment to payroll, the Smart Staff Management Platform
                helps organizations digitize HR operations while improving
                productivity, transparency and employee engagement.
            </p>

        </div>

        <div class="grid lg:grid-cols-4 md:grid-cols-2 gap-8">

            <div class="glass rounded-3xl p-10 text-center feature-card">
                <div class="text-5xl font-black text-yellow-400" data-count="100">0</div>
                <div class="mt-4 text-gray-300 uppercase tracking-widest text-sm">
                    Digital HR
                </div>
            </div>

            <div class="glass rounded-3xl p-10 text-center feature-card">
                <div class="text-5xl font-black text-purple-400" data-count="24">0</div>
                <div class="mt-4 text-gray-300 uppercase tracking-widest text-sm">
                    24/7 Availability
                </div>
            </div>

            <div class="glass rounded-3xl p-10 text-center feature-card">
                <div class="text-5xl font-black text-green-400" data-count="99">0</div>
                <div class="mt-4 text-gray-300 uppercase tracking-widest text-sm">
                    Secure Platform
                </div>
            </div>

            <div class="glass rounded-3xl p-10 text-center feature-card">
                <div class="text-5xl font-black text-blue-400" data-count="8">0</div>
                <div class="mt-4 text-gray-300 uppercase tracking-widest text-sm">
                    Core HR Modules
                </div>
            </div>

        </div>

    </div>

</section>

<!-- ===================================================== -->
<!-- HR WORKFLOW -->
<!-- ===================================================== -->

<section class="pb-24 px-8">

    <div class="max-w-7xl mx-auto">

        <div class="glass rounded-[35px] p-14">

            <div class="text-center mb-16">

                <span class="uppercase tracking-[0.3em] text-yellow-400 text-sm">
                    HR Workflow
                </span>

                <h2 class="text-5xl font-black mt-4">
                    One Platform. Complete Workforce Journey.
                </h2>

            </div>

            <div class="grid lg:grid-cols-5 gap-8">

                <div class="feature-card text-center">

                    <div class="w-20 h-20 mx-auto rounded-full bg-purple-700 flex items-center justify-center text-3xl">
                        👤
                    </div>

                    <h3 class="mt-5 font-bold">
                        Recruitment
                    </h3>

                    <p class="text-gray-400 mt-3 text-sm">
                        Hire qualified candidates efficiently.
                    </p>

                </div>

                <div class="feature-card text-center">

                    <div class="w-20 h-20 mx-auto rounded-full bg-blue-700 flex items-center justify-center text-3xl">
                        📄
                    </div>

                    <h3 class="mt-5 font-bold">
                        Onboarding
                    </h3>

                    <p class="text-gray-400 mt-3 text-sm">
                        Create employee profiles and assign departments.
                    </p>

                </div>

                <div class="feature-card text-center">

                    <div class="w-20 h-20 mx-auto rounded-full bg-green-700 flex items-center justify-center text-3xl">
                        🕒
                    </div>

                    <h3 class="mt-5 font-bold">
                        Attendance
                    </h3>

                    <p class="text-gray-400 mt-3 text-sm">
                        Monitor attendance in real time.
                    </p>

                </div>

                <div class="feature-card text-center">

                    <div class="w-20 h-20 mx-auto rounded-full bg-yellow-600 flex items-center justify-center text-3xl">
                        💰
                    </div>

                    <h3 class="mt-5 font-bold">
                        Payroll
                    </h3>

                    <p class="text-gray-400 mt-3 text-sm">
                        Process salaries accurately and efficiently.
                    </p>

                </div>

                <div class="feature-card text-center">

                    <div class="w-20 h-20 mx-auto rounded-full bg-indigo-700 flex items-center justify-center text-3xl">
                        📊
                    </div>

                    <h3 class="mt-5 font-bold">
                        Reports
                    </h3>

                    <p class="text-gray-400 mt-3 text-sm">
                        Gain valuable workforce insights instantly.
                    </p>

                </div>

            </div>

        </div>

    </div>

</section>

<!-- ===================================================== -->
<!-- TECHNOLOGY PARTNER -->
<!-- ===================================================== -->

<section class="pb-28 px-8">

    <div class="max-w-7xl mx-auto">

        <div class="glass rounded-[40px] overflow-hidden">

            <div class="grid lg:grid-cols-2">

                <div class="p-14">

                    <span class="uppercase tracking-[0.3em] text-yellow-400 text-sm">
                        Technology Partner
                    </span>

                    <h2 class="text-5xl font-black mt-5">
                        Powered by
                        <span class="text-yellow-400">
                            WRLD SOLUTIONS
                        </span>
                    </h2>

                    <p class="mt-8 text-gray-300 leading-8">

                        WRLD SOLUTIONS designs and develops modern digital
                        solutions that enable organizations to automate
                        operations, improve efficiency and accelerate digital
                        transformation.

                    </p>

                    <div class="grid grid-cols-2 gap-5 mt-10">

                        <div class="glass rounded-xl p-5">

                            💻

                            <h4 class="mt-4 font-semibold">
                                Software Development
                            </h4>

                        </div>

                        <div class="glass rounded-xl p-5">

                            🤖

                            <h4 class="mt-4 font-semibold">
                                AI Automation
                            </h4>

                        </div>

                        <div class="glass rounded-xl p-5">

                            ☁️

                            <h4 class="mt-4 font-semibold">
                                Cloud Solutions
                            </h4>

                        </div>

                        <div class="glass rounded-xl p-5">

                            🔒

                            <h4 class="mt-4 font-semibold">
                                System Integration
                            </h4>

                        </div>

                    </div>

                </div>

                <div class="bg-gradient-to-br from-purple-900 via-slate-900 to-black flex items-center justify-center p-16">

                    <div class="text-center">

                        <div class="w-32 h-32 rounded-full bg-yellow-500 flex items-center justify-center text-5xl font-black mx-auto shadow-2xl">

                            W

                        </div>

                        <h2 class="text-4xl font-black mt-8">

                            WRLD SOLUTIONS

                        </h2>

                        <p class="mt-5 text-gray-300">

                            Building Smart Organizations Through Technology

                        </p>

                    </div>

                </div>

            </div>

        </div>

    </div>

</section>

<!-- ================= FOOTER ================= -->
<!-- ===================================================== -->
<!-- ENTERPRISE FOOTER -->
<!-- ===================================================== -->

<footer class="border-t border-white/10 bg-black/30 backdrop-blur-xl">

    <div class="max-w-7xl mx-auto px-8 py-20">

        <div class="grid lg:grid-cols-4 gap-12">

            <!-- Smart Royal -->

            <div>

                <h3 class="text-2xl font-bold">

                    SMART ROYAL

                </h3>

                <p class="text-gray-400 mt-4 leading-8">

                    Human Resource Management Platform supporting
                    recruitment, employee administration,
                    attendance management, payroll processing
                    and organizational excellence.

                </p>

            </div>

            <!-- Platform -->

            <div>

                <h4 class="font-bold text-lg mb-6">

                    Platform

                </h4>

                <div class="space-y-3 text-gray-400">

                    <p>Employee Management</p>

                    <p>Attendance Tracking</p>

                    <p>Payroll Management</p>

                    <p>Departments & Units</p>

                    <p>Performance Reports</p>

                    <p>Recruitment Support</p>

                </div>

            </div>

            <!-- Technology -->

            <div>

                <h4 class="font-bold text-lg mb-6">

                    Technology Partner

                </h4>

                <div class="space-y-4">

                    <p class="text-yellow-400 font-semibold">

                        WRLD SOLUTIONS

                    </p>

                    <p class="text-gray-400">

                        Custom Software

                    </p>

                    <p class="text-gray-400">

                        AI Automation

                    </p>

                    <p class="text-gray-400">

                        Cloud Solutions

                    </p>

                    <p class="text-gray-400">

                        System Integration

                    </p>

                </div>

            </div>

            <!-- Contact -->

            <div>

                <h4 class="font-bold text-lg mb-6">

                    Contact

                </h4>

                <div class="space-y-3 text-gray-400">

                    <p>
                        📍 Upper Hill,
                        KMA Building,
                        Nairobi
                    </p>

                    <p>

                        📧 info@smartroyalcareer.co.ke


                    </p>

                    <p>

                        📧 aldrinewrldsolutions@gmail.com

                    </p>


                    <p>

                        🌍 Powered by WRLD SOLUTIONS

                    </p>

                </div>

            </div>

        </div>

        <div class="border-t border-white/10 mt-16 pt-8 flex flex-col md:flex-row justify-between items-center">

            <p class="text-gray-500">

                © {{ date('Y') }}

                Smart Staff Management Platform

            </p>

            <p class="text-gray-500 mt-4 md:mt-0">

                Designed & Developed by

                <span class="text-yellow-400 font-semibold">

                    WRLD SOLUTIONS

                </span>

            </p>

        </div>

    </div>

</footer>


<!-- ================= SCRIPTS ================= -->
<script>
/* Live Clock */
function updateClock(){

    const now=new Date();

    const options={

        weekday:'long',

        hour:'2-digit',

        minute:'2-digit',

        second:'2-digit'

    };

    document.getElementById("liveClock").innerHTML=

        now.toLocaleTimeString([],{

            hour:'2-digit',

            minute:'2-digit',

            second:'2-digit'

        });

}

setInterval(updateClock,1000);

updateClock();
</script>

</body>
</html>
