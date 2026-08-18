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
    :root {
        --royal-purple: #6d28d9;
        --royal-purple-dark: #3b0764;
        --royal-purple-light: #a855f7;

        --gold: #d4a017;
        --gold-light: #facc15;

        --black: #050505;
        --white: #ffffff;
    }

    * {
        box-sizing: border-box;
    }

    html {
        scroll-behavior: smooth;
    }

    body {
        overflow-x: hidden;
        background: var(--black);
    }

    /* =========================================================
       MAIN BACKGROUND
    ========================================================= */

    .animated-bg {
        background:
            radial-gradient(circle at 10% 10%, rgba(109, 40, 217, .20) 0%, transparent 30%),
            radial-gradient(circle at 85% 15%, rgba(212, 160, 23, .12) 0%, transparent 28%),
            radial-gradient(circle at 50% 90%, rgba(109, 40, 217, .14) 0%, transparent 30%),
            linear-gradient(
                135deg,
                #020203 0%,
                #08050d 30%,
                #111111 60%,
                #09070c 100%
            );

        background-size: 400% 400%;
        animation: gradientMove 18s ease infinite;
    }

    @keyframes gradientMove {
        0% {
            background-position: 0% 50%;
        }

        50% {
            background-position: 100% 50%;
        }

        100% {
            background-position: 0% 50%;
        }
    }

    /* =========================================================
       GLOBAL GLASS EFFECT
    ========================================================= */

    .glass {
        backdrop-filter: blur(18px);
        -webkit-backdrop-filter: blur(18px);

        background:
            linear-gradient(
                145deg,
                rgba(255, 255, 255, .075),
                rgba(255, 255, 255, .025)
            );

        border: 1px solid rgba(255, 255, 255, .09);

        box-shadow:
            0 20px 50px rgba(0, 0, 0, .45),
            inset 0 1px 0 rgba(255, 255, 255, .04);

        position: relative;
        overflow: hidden;
    }

    .glass::before {
        content: "";
        position: absolute;
        inset: 0;

        background:
            linear-gradient(
                120deg,
                transparent 20%,
                rgba(255,255,255,.045) 50%,
                transparent 80%
            );

        transform: translateX(-120%);
        transition: transform 0.8s ease;

        pointer-events: none;
    }

    .glass:hover::before {
        transform: translateX(120%);
    }

    /* =========================================================
       HERO TITLE
    ========================================================= */

    .hero-title {
        background:
            linear-gradient(
                90deg,
                #ffffff 0%,
                #ffffff 42%,
                #facc15 72%,
                #d4a017 100%
            );

        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;

        background-size: 200% auto;

        animation: titleShimmer 6s linear infinite;
    }

    @keyframes titleShimmer {
        0% {
            background-position: 0% center;
        }

        100% {
            background-position: 200% center;
        }
    }

    /* =========================================================
       PULSE INDICATOR
    ========================================================= */

    .pulse-dot {
        width: 10px;
        height: 10px;
        border-radius: 999px;
        background: #22c55e;
        position: relative;
        flex-shrink: 0;
    }

    .pulse-dot::after {
        content: "";
        position: absolute;
        inset: -5px;

        border-radius: inherit;

        border: 1px solid rgba(34, 197, 94, .55);

        animation: pulseRing 1.6s infinite;
    }

    @keyframes pulseRing {
        0% {
            transform: scale(.65);
            opacity: .9;
        }

        100% {
            transform: scale(1.7);
            opacity: 0;
        }
    }

    /* =========================================================
       FEATURE CARDS
    ========================================================= */

    .feature-card {
        transition:
            transform .4s cubic-bezier(.2,.8,.2,1),
            border-color .4s ease,
            box-shadow .4s ease,
            background .4s ease;

        border: 1px solid rgba(255,255,255,.06);
    }

    .feature-card:hover {
        transform: translateY(-8px) scale(1.015);

        border-color:
            rgba(212, 160, 23, .75);

        box-shadow:
            0 20px 45px rgba(0,0,0,.4),
            0 0 30px rgba(109,40,217,.10);
    }

    /* =========================================================
       FLOATING BACKGROUND ORBS
    ========================================================= */

    .floating-orb {
        position: absolute;
        border-radius: 999px;
        pointer-events: none;
        filter: blur(80px);
        opacity: .18;
        animation: floatOrb 10s ease-in-out infinite;
    }

    .orb-purple {
        background: var(--royal-purple);
    }

    .orb-gold {
        background: var(--gold);
    }

    @keyframes floatOrb {
        0%,
        100% {
            transform: translate3d(0, 0, 0) scale(1);
        }

        50% {
            transform: translate3d(0, -30px, 0) scale(1.08);
        }
    }

    /* =========================================================
       HERO BADGES
    ========================================================= */

    .gold-badge {
        background:
            linear-gradient(
                135deg,
                rgba(212,160,23,.16),
                rgba(109,40,217,.12)
            );

        border: 1px solid rgba(212,160,23,.35);

        transition: all .35s ease;
    }

    .gold-badge:hover {
        transform: translateY(-4px);

        border-color:
            rgba(212,160,23,.75);

        box-shadow:
            0 10px 30px rgba(212,160,23,.10);
    }

    /* =========================================================
       PLATFORM MODULE PANEL
    ========================================================= */

    .modules-panel {
        background:
            linear-gradient(
                145deg,
                rgba(33, 18, 52, .94),
                rgba(8, 8, 8, .97)
            );

        border:
            1px solid rgba(212, 160, 23, .20);

        box-shadow:
            0 30px 70px rgba(0,0,0,.55),
            0 0 50px rgba(109,40,217,.10);

        position: relative;
    }

    .modules-panel::after {
        content: "";
        position: absolute;

        top: -100px;
        right: -100px;

        width: 220px;
        height: 220px;

        border-radius: 50%;

        background:
            rgba(212,160,23,.08);

        filter: blur(40px);

        pointer-events: none;
    }

    .module-card {
        position: relative;

        border:
            1px solid rgba(255,255,255,.08);

        background:
            linear-gradient(
                135deg,
                rgba(255,255,255,.075),
                rgba(255,255,255,.025)
            );

        transition:
            transform .35s ease,
            border-color .35s ease,
            background .35s ease,
            box-shadow .35s ease;

        cursor: default;
    }

    .module-card:hover {
        transform: translateX(6px);

        border-color:
            rgba(212,160,23,.65);

        background:
            linear-gradient(
                135deg,
                rgba(109,40,217,.23),
                rgba(212,160,23,.08)
            );

        box-shadow:
            -6px 0 0 rgba(212,160,23,.65),
            0 15px 40px rgba(0,0,0,.35);
    }

    .module-icon {
        width: 50px;
        height: 50px;

        display: flex;
        align-items: center;
        justify-content: center;

        border-radius: 15px;

        background:
            linear-gradient(
                135deg,
                rgba(109,40,217,.80),
                rgba(59,7,100,.95)
            );

        border:
            1px solid rgba(212,160,23,.32);

        color: #fff;

        box-shadow:
            0 8px 25px rgba(109,40,217,.20);
    }

    .module-check {
        width: 34px;
        height: 34px;

        display: flex;
        align-items: center;
        justify-content: center;

        border-radius: 50%;

        color:
            var(--gold-light);

        background:
            rgba(212,160,23,.10);

        border:
            1px solid rgba(212,160,23,.25);

        font-size: 15px;
    }

    /* =========================================================
       SECTION REVEAL
    ========================================================= */

    .reveal {
        opacity: 0;
        transform: translateY(35px);

        transition:
            opacity .9s ease,
            transform .9s cubic-bezier(.2,.8,.2,1);
    }

    .reveal.active {
        opacity: 1;
        transform: translateY(0);
    }

    .reveal-delay-1 {
        transition-delay: .1s;
    }

    .reveal-delay-2 {
        transition-delay: .2s;
    }

    .reveal-delay-3 {
        transition-delay: .3s;
    }

    /* =========================================================
       FLOATING ICON ANIMATION
    ========================================================= */

    .floating-icon {
        animation:
            floatingIcon 4s ease-in-out infinite;
    }

    @keyframes floatingIcon {
        0%,
        100% {
            transform: translateY(0);
        }

        50% {
            transform: translateY(-7px);
        }
    }

    /* =========================================================
       COUNTERS
    ========================================================= */

    .counter-value {
        font-variant-numeric: tabular-nums;
    }

    /* =========================================================
       BUTTON SHINE
    ========================================================= */

    .brand-button {
        position: relative;
        overflow: hidden;
        isolation: isolate;
    }

    .brand-button::before {
        content: "";

        position: absolute;

        top: 0;
        left: -110%;

        width: 60%;
        height: 100%;

        background:
            linear-gradient(
                100deg,
                transparent,
                rgba(255,255,255,.30),
                transparent
            );

        transform:
            skewX(-20deg);

        transition:
            left .7s ease;

        z-index: -1;
    }

    .brand-button:hover::before {
        left: 130%;
    }

    /* =========================================================
       WORKFLOW CONNECTOR
    ========================================================= */

    .workflow-step {
        position: relative;
    }

    @media (min-width: 1024px) {

        .workflow-step:not(:last-child)::after {

            content: "";

            position: absolute;

            top: 40px;
            left: calc(100% - 8px);

            width: 48px;
            height: 2px;

            background:
                linear-gradient(
                    90deg,
                    rgba(212,160,23,.65),
                    rgba(109,40,217,.15)
                );
        }
    }

    /* =========================================================
       WRLD SOLUTIONS TECHNOLOGY FLOW
       ONLY ICON + TECHNOLOGY NAME
    ========================================================= */

    .technology-flow {
        position: relative;

        width: 100%;

        margin-top: 10px;

        padding:
            22px 0;

        overflow: hidden;

        border-top:
            1px solid rgba(212,160,23,.08);

        border-bottom:
            1px solid rgba(255,255,255,.06);

        background:
            linear-gradient(
                180deg,
                rgba(109,40,217,.035),
                rgba(0,0,0,.20),
                rgba(212,160,23,.025)
            );
    }

    .technology-flow::before {
        content: "";

        position: absolute;

        inset: 0;

        background:
            radial-gradient(
                circle at 20% 50%,
                rgba(109,40,217,.10),
                transparent 26%
            ),
            radial-gradient(
                circle at 80% 50%,
                rgba(212,160,23,.08),
                transparent 26%
            );

        pointer-events: none;
    }

    /* ---------------------------------------------------------
       Edge fading
    --------------------------------------------------------- */

    .technology-flow::after {
        content: "";

        position: absolute;

        inset: 0;

        pointer-events: none;

        z-index: 5;

        background:
            linear-gradient(
                90deg,
                rgba(5,5,5,1) 0%,
                rgba(5,5,5,0) 12%,
                rgba(5,5,5,0) 88%,
                rgba(5,5,5,1) 100%
            );
    }

    .technology-track {
        display: flex;

        width: max-content;

        align-items: center;

        gap: 48px;

        animation:
            techFlowRightToLeft 34s
            linear infinite;

        will-change:
            transform;
    }

    @keyframes techFlowRightToLeft {

        from {
            transform:
                translate3d(0, 0, 0);
        }

        to {
            transform:
                translate3d(-50%, 0, 0);
        }
    }

    .technology-flow:hover .technology-track {
        animation-play-state:
            paused;
    }

    /* ---------------------------------------------------------
       Individual floating technologies
    --------------------------------------------------------- */

    .technology-item {

        display: inline-flex;

        align-items: center;

        gap: 12px;

        white-space: nowrap;

        color:
            rgba(255,255,255,.85);

        position: relative;

        transition:
            transform .35s ease,
            color .35s ease,
            opacity .35s ease;

        animation:
            technologyFloat 4s ease-in-out infinite;
    }

    .technology-item:nth-child(2n) {
        animation-delay:
            -1.2s;
    }

    .technology-item:nth-child(3n) {
        animation-delay:
            -2.3s;
    }

    .technology-item:nth-child(4n) {
        animation-delay:
            -3.1s;
    }

    .technology-item:hover {
        color:
            var(--gold-light);

        transform:
            translateY(-5px)
            scale(1.05);
    }

    @keyframes technologyFloat {

        0%,
        100% {
            transform:
                translateY(0);
        }

        50% {
            transform:
                translateY(-5px);
        }
    }

    /* ---------------------------------------------------------
       Technology icon
    --------------------------------------------------------- */

    .technology-icon {

        width: 46px;
        height: 46px;

        display: flex;

        align-items: center;
        justify-content: center;

        border-radius: 14px;

        background:
            linear-gradient(
                145deg,
                rgba(109,40,217,.86),
                rgba(59,7,100,.97)
            );

        border:
            1px solid rgba(212,160,23,.30);

        color:
            #ffffff;

        box-shadow:
            0 8px 20px rgba(0,0,0,.35),
            inset 0 1px 0 rgba(255,255,255,.08);

        position: relative;

        flex-shrink: 0;
    }

    .technology-icon::after {

        content: "";

        position: absolute;

        inset: -1px;

        border-radius:
            inherit;

        border:
            1px solid rgba(168,85,247,.18);

        animation:
            iconPulse 3s ease-in-out infinite;
    }

    @keyframes iconPulse {

        0%,
        100% {
            opacity: .35;
            transform:
                scale(1);
        }

        50% {
            opacity: .85;
            transform:
                scale(1.08);
        }
    }

    .technology-icon svg {

        width: 23px;
        height: 23px;

        display:
            block;

        stroke:
            currentColor;

        fill:
            none;

        stroke-width:
            1.8;

        stroke-linecap:
            round;

        stroke-linejoin:
            round;
    }

    .technology-icon-text {

        font-size:
            15px;

        font-weight:
            900;

        letter-spacing:
            -.05em;
    }

    /* ---------------------------------------------------------
       Technology name
    --------------------------------------------------------- */

    .technology-name {

        font-size:
            15px;

        font-weight:
            700;

        letter-spacing:
            -.01em;

        text-shadow:
            0 0 15px rgba(255,255,255,.04);
    }

    /* ---------------------------------------------------------
       Tiny separator
    --------------------------------------------------------- */

    .technology-separator {

        width:
            5px;

        height:
            5px;

        border-radius:
            50%;

        background:
            var(--gold);

        box-shadow:
            0 0 12px rgba(212,160,23,.60);

        flex-shrink:
            0;
    }

    /* =========================================================
       ACCESSIBILITY
    ========================================================= */

    @media (prefers-reduced-motion: reduce) {

        html {
            scroll-behavior:
                auto;
        }

        *,
        *::before,
        *::after {

            animation-duration:
                0.01ms !important;

            animation-iteration-count:
                1 !important;

            transition-duration:
                0.01ms !important;
        }

        .technology-track {
            animation:
                none;
        }
    }

    /* =========================================================
       RESPONSIVE TECHNOLOGY FLOW
    ========================================================= */

    @media (max-width: 768px) {

        .technology-track {

            gap:
                34px;

            animation-duration:
                28s;
        }

        .technology-item {

            gap:
                9px;
        }

        .technology-icon {

            width:
                40px;

            height:
                40px;
        }

        .technology-name {

            font-size:
                13px;
        }

        .technology-separator {

            width:
                4px;

            height:
                4px;
        }
    }

    @media (max-width: 480px) {

        .technology-flow {
            padding:
                18px 0;
        }

        .technology-track {

            gap:
                28px;

            animation-duration:
                24s;
        }

        .technology-icon {

            width:
                38px;

            height:
                38px;

            border-radius:
                12px;
        }

        .technology-icon svg {
            width:
                20px;

            height:
                20px;
        }

        .technology-name {

            font-size:
                12px;
        }
    }
</style>
</head>

<body class="animated-bg text-gray-100 min-h-screen flex flex-col">

<!-- =========================================================
     FLOATING BACKGROUND ELEMENTS
========================================================= -->

<div class="fixed inset-0 overflow-hidden -z-10 pointer-events-none">

    <div
        class="floating-orb orb-purple w-96 h-96 -left-32 top-10"
        style="animation-delay:-2s;"
    ></div>

    <div
        class="floating-orb orb-gold w-[420px] h-[420px] -right-40 top-1/3"
        style="animation-delay:-5s;"
    ></div>

    <div
        class="floating-orb orb-purple w-72 h-72 left-1/2 bottom-0"
        style="animation-delay:-7s;"
    ></div>

</div>


<!-- ================= NAV ================= -->

<header class="sticky top-0 z-50 border-b border-white/10 glass">

@if(Route::has('login'))

<nav class="max-w-7xl mx-auto px-8 py-5 flex items-center justify-between">

<div class="flex items-center gap-4">

<div class="w-14 h-14 rounded-2xl bg-gradient-to-r from-purple-700 to-yellow-500 flex items-center justify-center text-white text-xl font-bold shadow-lg floating-icon">
    SR
</div>

<div>

<h2 class="text-2xl font-bold tracking-wide">
    SMART ROYAL
</h2>

<p class="text-sm text-gray-200">
    Human Resource Consultancy & Recruitment Services
</p>

<p class="text-xs text-yellow-400">
    Powered by WRLD SOLUTIONS
</p>

<div class="mt-2 inline-flex items-center gap-2 text-xs text-gray-300">

<span class="w-2 h-2 rounded-full bg-green-400 animate-pulse"></span>

Enterprise Edition

</div>

</div>

</div>


<!-- LOGIN BUTTON -->

<div class="flex items-center gap-4">

@auth

<a
    href="{{ url('/dashboard') }}"
    class="brand-button px-6 py-3 rounded-xl bg-gradient-to-r from-purple-700 to-yellow-500 text-white font-semibold transition duration-300 hover:scale-105 hover:shadow-2xl shadow-lg"
>
    Dashboard
</a>

@else

<a
    href="{{ route('login') }}"
    class="brand-button px-6 py-3 rounded-xl bg-gradient-to-r from-purple-700 to-yellow-500 text-white font-semibold transition duration-300 hover:scale-105 hover:shadow-2xl shadow-lg"
>
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

<div class="reveal">

<div class="inline-flex items-center gap-3 gold-badge rounded-full px-5 py-2 mb-8">

<div class="pulse-dot"></div>

<span class="text-sm font-medium">
    Trusted by Smart Royal Career Consultant
</span>

</div>

<h1 class="hero-title text-6xl font-black leading-tight">

    Human Resource Consultancy

    <br>

    & Recruitment Services

</h1>

<p class="mt-8 text-gray-200 text-xl leading-9">

    Empowering
    <strong class="text-white">
        Smart Royal Career Consultant
    </strong>

    with intelligent recruitment, employee administration,
    attendance management, payroll processing and organizational excellence.

</p>


<div class="mt-10 flex flex-wrap gap-4">

<span class="gold-badge px-5 py-3 rounded-xl text-white">
    Recruitment
</span>

<span class="gold-badge px-5 py-3 rounded-xl text-white">
    Attendance
</span>

<span class="gold-badge px-5 py-3 rounded-xl text-white">
    Payroll
</span>

<span class="gold-badge px-5 py-3 rounded-xl text-white">
    Reports
</span>

</div>


<div class="mt-10 flex gap-8 flex-wrap">

<div class="glass rounded-2xl p-6 hover:border-yellow-500/30 transition">

<div class="text-sm text-gray-400">
    Current Time
</div>

<div
    id="liveClock"
    class="text-3xl font-bold mt-2 text-white"
></div>

</div>


<div class="glass rounded-2xl p-6 hover:border-yellow-500/30 transition">

<div class="text-sm text-gray-400">
    Platform Status
</div>

<div class="flex items-center gap-3 mt-2">

<div class="pulse-dot"></div>

<span class="font-semibold text-white">
    ONLINE
</span>

</div>

</div>

</div>

</div>


<!-- RIGHT / PLATFORM MODULES -->

<div class="reveal reveal-delay-2">

<div class="modules-panel rounded-[32px] p-8">

<div class="flex justify-between items-start mb-8">

<div>

<p class="text-xs uppercase tracking-[0.25em] text-yellow-400 font-semibold">
    Smart Royal Ecosystem
</p>

<h2 class="text-3xl font-black mt-2 text-white">
    Platform Modules
</h2>

<p class="mt-2 text-gray-400 text-sm">
    Everything you need to manage modern HR operations.
</p>

</div>

<span class="flex items-center gap-2 text-green-400 text-sm font-semibold">

<span class="w-2.5 h-2.5 bg-green-400 rounded-full animate-pulse"></span>

Operational

</span>

</div>


<div class="grid gap-4">

<!-- MODULE 1 -->

<div class="module-card rounded-2xl p-5 flex items-center justify-between gap-4">

<div class="flex items-center gap-4">

<div class="module-icon text-xl">
    👥
</div>

<div>

<h3 class="text-white font-bold text-base">
    Employee Management
</h3>

<p class="text-gray-400 text-sm mt-1">
    Centralized employee records and workforce administration.
</p>

</div>

</div>

<div class="module-check">
    ✔
</div>

</div>


<!-- MODULE 2 -->

<div class="module-card rounded-2xl p-5 flex items-center justify-between gap-4">

<div class="flex items-center gap-4">

<div class="module-icon text-xl">
    🕒
</div>

<div>

<h3 class="text-white font-bold text-base">
    Attendance Monitoring
</h3>

<p class="text-gray-400 text-sm mt-1">
    Monitor staff attendance, clock-ins and working activity.
</p>

</div>

</div>

<div class="module-check">
    ✔
</div>

</div>


<!-- MODULE 3 -->

<div class="module-card rounded-2xl p-5 flex items-center justify-between gap-4">

<div class="flex items-center gap-4">

<div class="module-icon text-xl">
    🎯
</div>

<div>

<h3 class="text-white font-bold text-base">
    Recruitment Support
</h3>

<p class="text-gray-400 text-sm mt-1">
    Support talent acquisition and recruitment workflows.
</p>

</div>

</div>

<div class="module-check">
    ✔
</div>

</div>


<!-- MODULE 4 -->

<div class="module-card rounded-2xl p-5 flex items-center justify-between gap-4">

<div class="flex items-center gap-4">

<div class="module-icon text-xl">
    💰
</div>

<div>

<h3 class="text-white font-bold text-base">
    Payroll Processing
</h3>

<p class="text-gray-400 text-sm mt-1">
    Streamline salary processing and payroll administration.
</p>

</div>

</div>

<div class="module-check">
    ✔
</div>

</div>


<!-- MODULE 5 -->

<div class="module-card rounded-2xl p-5 flex items-center justify-between gap-4">

<div class="flex items-center gap-4">

<div class="module-icon text-xl">
    🏢
</div>

<div>

<h3 class="text-white font-bold text-base">
    Departments & Units
</h3>

<p class="text-gray-400 text-sm mt-1">
    Organize teams, departments and workforce structures.
</p>

</div>

</div>

<div class="module-check">
    ✔
</div>

</div>


<!-- MODULE 6 -->

<div class="module-card rounded-2xl p-5 flex items-center justify-between gap-4">

<div class="flex items-center gap-4">

<div class="module-icon text-xl">
    📊
</div>

<div>

<h3 class="text-white font-bold text-base">
    Performance Reports
</h3>

<p class="text-gray-400 text-sm mt-1">
    Generate useful insights for smarter management decisions.
</p>

</div>

</div>

<div class="module-check">
    ✔
</div>

</div>

</div>


<div class="mt-8 border-t border-white/10 pt-6 flex items-center justify-between">

<span class="text-gray-400">
    Technology Partner
</span>

<span class="text-yellow-400 font-bold">
    WRLD SOLUTIONS
</span>

</div>

</div>

</div>

</div>

</main>


<!-- =========================================
     ABOUT SMART ROYAL
========================================= -->

<section class="py-24 px-8 reveal">

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

<!-- LEFT -->

<div class="glass rounded-3xl p-10 feature-card">

<h3 class="text-3xl font-bold mb-8">
    Core Services
</h3>

<div class="space-y-6">

<div class="flex items-center justify-between">

<span>
    Recruitment & Talent Placement
</span>

<span class="text-yellow-400">
    ✓
</span>

</div>

<div class="flex items-center justify-between">

<span>
    HR Consulting
</span>

<span class="text-yellow-400">
    ✓
</span>

</div>

<div class="flex items-center justify-between">

<span>
    Staff Outsourcing
</span>

<span class="text-yellow-400">
    ✓
</span>

</div>

<div class="flex items-center justify-between">

<span>
    Corporate Training
</span>

<span class="text-yellow-400">
    ✓
</span>

</div>

<div class="flex items-center justify-between">

<span>
    Performance Management
</span>

<span class="text-yellow-400">
    ✓
</span>

</div>

<div class="flex items-center justify-between">

<span>
    Career Advisory
</span>

<span class="text-yellow-400">
    ✓
</span>

</div>

</div>

</div>


<!-- RIGHT -->

<div class="glass rounded-3xl p-10 feature-card">

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


<!-- =========================================
     WHY CHOOSE THIS PLATFORM
========================================= -->

<section class="pb-24 px-8 reveal">

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

<div class="text-5xl mb-5 floating-icon">
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

<div class="text-5xl mb-5 floating-icon">
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

<div class="text-5xl mb-5 floating-icon">
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


<!-- =====================================================
     PLATFORM IMPACT
===================================================== -->

<section class="py-24 px-8 reveal">

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

<div
    class="text-5xl font-black text-yellow-400 counter-value"
    data-count="100"
    data-suffix="%"
>
    0
</div>

<div class="mt-4 text-gray-300 uppercase tracking-widest text-sm">
    Digital HR
</div>

</div>


<div class="glass rounded-3xl p-10 text-center feature-card">

<div
    class="text-5xl font-black text-purple-400 counter-value"
    data-count="24"
    data-suffix="/7"
>
    0
</div>

<div class="mt-4 text-gray-300 uppercase tracking-widest text-sm">
    24/7 Availability
</div>

</div>


<div class="glass rounded-3xl p-10 text-center feature-card">

<div
    class="text-5xl font-black text-yellow-400 counter-value"
    data-count="99"
    data-suffix="%"
>
    0
</div>

<div class="mt-4 text-gray-300 uppercase tracking-widest text-sm">
    Secure Platform
</div>

</div>


<div class="glass rounded-3xl p-10 text-center feature-card">

<div
    class="text-5xl font-black text-white counter-value"
    data-count="8"
    data-suffix="+"
>
    0
</div>

<div class="mt-4 text-gray-300 uppercase tracking-widest text-sm">
    Core HR Modules
</div>

</div>

</div>

</div>

</section>


<!-- =====================================================
     HR WORKFLOW
===================================================== -->

<section class="pb-24 px-8 reveal">

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

<div class="workflow-step feature-card text-center">

<div class="w-20 h-20 mx-auto rounded-full bg-gradient-to-br from-purple-700 to-purple-950 flex items-center justify-center text-3xl">
    👤
</div>

<h3 class="mt-5 font-bold">
    Recruitment
</h3>

<p class="text-gray-400 mt-3 text-sm">
    Hire qualified candidates efficiently.
</p>

</div>


<div class="workflow-step feature-card text-center">

<div class="w-20 h-20 mx-auto rounded-full bg-gradient-to-br from-purple-700 to-black flex items-center justify-center text-3xl">
    📄
</div>

<h3 class="mt-5 font-bold">
    Onboarding
</h3>

<p class="text-gray-400 mt-3 text-sm">
    Create employee profiles and assign departments.
</p>

</div>


<div class="workflow-step feature-card text-center">

<div class="w-20 h-20 mx-auto rounded-full bg-gradient-to-br from-yellow-600 to-yellow-800 flex items-center justify-center text-3xl">
    🕒
</div>

<h3 class="mt-5 font-bold">
    Attendance
</h3>

<p class="text-gray-400 mt-3 text-sm">
    Monitor attendance in real time.
</p>

</div>


<div class="workflow-step feature-card text-center">

<div class="w-20 h-20 mx-auto rounded-full bg-gradient-to-br from-yellow-500 to-yellow-700 flex items-center justify-center text-3xl">
    💰
</div>

<h3 class="mt-5 font-bold">
    Payroll
</h3>

<p class="text-gray-400 mt-3 text-sm">
    Process salaries accurately and efficiently.
</p>

</div>


<div class="workflow-step feature-card text-center">

<div class="w-20 h-20 mx-auto rounded-full bg-gradient-to-br from-purple-700 to-yellow-600 flex items-center justify-center text-3xl">
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


<!-- =====================================================
     TECHNOLOGY PARTNER
===================================================== -->

<section class="pb-10 px-8 reveal">

<div class="max-w-7xl mx-auto">

<div class="glass rounded-[40px] overflow-hidden">

<div class="grid lg:grid-cols-2">

<!-- LEFT SIDE -->

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

<div class="glass rounded-xl p-5 feature-card">

    💻

    <h4 class="mt-4 font-semibold">
        Software Development
    </h4>

</div>


<div class="glass rounded-xl p-5 feature-card">

    🤖

    <h4 class="mt-4 font-semibold">
        AI Automation
    </h4>

</div>


<div class="glass rounded-xl p-5 feature-card">

    ☁️

    <h4 class="mt-4 font-semibold">
        Cloud Solutions
    </h4>

</div>


<div class="glass rounded-xl p-5 feature-card">

    🔒

    <h4 class="mt-4 font-semibold">
        System Integration
    </h4>

</div>

</div>

</div>


<!-- RIGHT SIDE -->

<div class="bg-gradient-to-br from-purple-950 via-black to-black flex items-center justify-center p-16">

<div class="text-center">

<div class="w-32 h-32 rounded-full bg-gradient-to-br from-yellow-400 to-yellow-600 flex items-center justify-center text-5xl font-black mx-auto shadow-2xl floating-icon">
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


<!-- =====================================================
     WRLD SOLUTIONS TECHNOLOGY FLOW
===================================================== -->

<section class="technology-flow reveal">

    <div class="technology-track">

        <!-- =================================================
             FIRST TECHNOLOGY SET
        ================================================= -->

        <!-- OpenAI -->

        <div class="technology-item">

            <div class="technology-icon">

                <svg viewBox="0 0 24 24">

                    <path d="M12 4.5c1.8-2 5.4-1.1 5.9 1.6 2.7-.2 4.6 2.7 3.1 5.1 2.1 1.8.8 5.3-1.9 5.7-.2 2.7-3.3 4-5.3 2-2.3 1.5-5.3-.2-5.2-2.9-2.7-.4-3.9-3.6-2-5.4-1.2-2.4 1-5.2 3.6-4.6 1.3-2.3 4.5-2.5 5.8-.3Z"/>

                    <path d="M9.2 8.2 12 6.6l2.8 1.6v3.3L12 13.1l-2.8-1.6V8.2Z"/>

                    <path d="M12 13.1v3.4"/>

                </svg>

            </div>

            <span class="technology-name">
                OpenAI
            </span>

            <span class="technology-separator"></span>

        </div>


        <!-- Ollama -->

        <div class="technology-item">

            <div class="technology-icon">

                <span class="technology-icon-text">
                    O
                </span>

            </div>

            <span class="technology-name">
                Ollama
            </span>

            <span class="technology-separator"></span>

        </div>


        <!-- n8n -->

        <div class="technology-item">

            <div class="technology-icon">

                <svg viewBox="0 0 24 24">

                    <path d="M7 7h5v5H7z"/>

                    <path d="M12 12h5v5h-5z"/>

                    <path d="M12 9.5V12"/>

                    <path d="M9.5 12h5"/>

                </svg>

            </div>

            <span class="technology-name">
                n8n
            </span>

            <span class="technology-separator"></span>

        </div>


        <!-- APIs -->

        <div class="technology-item">

            <div class="technology-icon">

                <svg viewBox="0 0 24 24">

                    <circle cx="6" cy="7" r="2.2"/>

                    <circle cx="18" cy="7" r="2.2"/>

                    <circle cx="12" cy="17" r="2.2"/>

                    <path d="M7.8 8.2 10.4 15"/>

                    <path d="M16.2 8.2 13.6 15"/>

                    <path d="M8.3 7h7.4"/>

                </svg>

            </div>

            <span class="technology-name">
                APIs
            </span>

            <span class="technology-separator"></span>

        </div>


        <!-- Zapier -->

        <div class="technology-item">

            <div class="technology-icon">

                <svg viewBox="0 0 24 24">

                    <path d="M12 4v5"/>

                    <path d="M12 15v5"/>

                    <path d="M4 12h5"/>

                    <path d="M15 12h5"/>

                    <path d="m6.3 6.3 3.5 3.5"/>

                    <path d="m14.2 14.2 3.5 3.5"/>

                    <path d="m17.7 6.3-3.5 3.5"/>

                    <path d="m9.8 14.2-3.5 3.5"/>

                </svg>

            </div>

            <span class="technology-name">
                Zapier
            </span>

            <span class="technology-separator"></span>

        </div>


        <!-- MySQL -->

        <div class="technology-item">

            <div class="technology-icon">

                <svg viewBox="0 0 24 24">

                    <ellipse
                        cx="12"
                        cy="6"
                        rx="6"
                        ry="2.8"
                    />

                    <path
                        d="M6 6v7c0 1.6 2.7 3 6 3s6-1.4 6-3V6"
                    />

                </svg>

            </div>

            <span class="technology-name">
                MySQL
            </span>

            <span class="technology-separator"></span>

        </div>


        <!-- PostgreSQL -->

        <div class="technology-item">

            <div class="technology-icon">

                <svg viewBox="0 0 24 24">

                    <path d="M8 18c-1.1-1-1.7-2.8-1.6-5.2.1-3.4 1.5-6.1 4.5-6.6 2.8-.5 5.8.9 6.6 3.5.8 2.7-.7 4.6-2.8 5.6"/>

                    <path d="M9.5 10.5c1.8-.5 3.7-.4 5.1.3"/>

                    <path d="M12.6 16.2c-.8 1.6-1.5 3.1-3.7 3.1"/>

                </svg>

            </div>

            <span class="technology-name">
                PostgreSQL
            </span>

            <span class="technology-separator"></span>

        </div>


        <!-- =================================================
             DUPLICATE SET FOR SEAMLESS LOOP
        ================================================= -->

        <!-- OpenAI -->

        <div class="technology-item">

            <div class="technology-icon">

                <svg viewBox="0 0 24 24">

                    <path d="M12 4.5c1.8-2 5.4-1.1 5.9 1.6 2.7-.2 4.6 2.7 3.1 5.1 2.1 1.8.8 5.3-1.9 5.7-.2 2.7-3.3 4-5.3 2-2.3 1.5-5.3-.2-5.2-2.9-2.7-.4-3.9-3.6-2-5.4-1.2-2.4 1-5.2 3.6-4.6 1.3-2.3 4.5-2.5 5.8-.3Z"/>

                    <path d="M9.2 8.2 12 6.6l2.8 1.6v3.3L12 13.1l-2.8-1.6V8.2Z"/>

                    <path d="M12 13.1v3.4"/>

                </svg>

            </div>

            <span class="technology-name">
                OpenAI
            </span>

            <span class="technology-separator"></span>

        </div>


        <!-- Ollama -->

        <div class="technology-item">

            <div class="technology-icon">

                <span class="technology-icon-text">
                    O
                </span>

            </div>

            <span class="technology-name">
                Ollama
            </span>

            <span class="technology-separator"></span>

        </div>


        <!-- n8n -->

        <div class="technology-item">

            <div class="technology-icon">

                <svg viewBox="0 0 24 24">

                    <path d="M7 7h5v5H7z"/>

                    <path d="M12 12h5v5h-5z"/>

                    <path d="M12 9.5V12"/>

                    <path d="M9.5 12h5"/>

                </svg>

            </div>

            <span class="technology-name">
                n8n
            </span>

            <span class="technology-separator"></span>

        </div>


        <!-- APIs -->

        <div class="technology-item">

            <div class="technology-icon">

                <svg viewBox="0 0 24 24">

                    <circle cx="6" cy="7" r="2.2"/>

                    <circle cx="18" cy="7" r="2.2"/>

                    <circle cx="12" cy="17" r="2.2"/>

                    <path d="M7.8 8.2 10.4 15"/>

                    <path d="M16.2 8.2 13.6 15"/>

                    <path d="M8.3 7h7.4"/>

                </svg>

            </div>

            <span class="technology-name">
                APIs
            </span>

            <span class="technology-separator"></span>

        </div>


        <!-- Zapier -->

        <div class="technology-item">

            <div class="technology-icon">

                <svg viewBox="0 0 24 24">

                    <path d="M12 4v5"/>

                    <path d="M12 15v5"/>

                    <path d="M4 12h5"/>

                    <path d="M15 12h5"/>

                    <path d="m6.3 6.3 3.5 3.5"/>

                    <path d="m14.2 14.2 3.5 3.5"/>

                    <path d="m17.7 6.3-3.5 3.5"/>

                    <path d="m9.8 14.2-3.5 3.5"/>

                </svg>

            </div>

            <span class="technology-name">
                Zapier
            </span>

            <span class="technology-separator"></span>

        </div>


        <!-- MySQL -->

        <div class="technology-item">

            <div class="technology-icon">

                <svg viewBox="0 0 24 24">

                    <ellipse
                        cx="12"
                        cy="6"
                        rx="6"
                        ry="2.8"
                    />

                    <path
                        d="M6 6v7c0 1.6 2.7 3 6 3s6-1.4 6-3V6"
                    />

                </svg>

            </div>

            <span class="technology-name">
                MySQL
            </span>

            <span class="technology-separator"></span>

        </div>


        <!-- PostgreSQL -->

        <div class="technology-item">

            <div class="technology-icon">

                <svg viewBox="0 0 24 24">

                    <path d="M8 18c-1.1-1-1.7-2.8-1.6-5.2.1-3.4 1.5-6.1 4.5-6.6 2.8-.5 5.8.9 6.6 3.5.8 2.7-.7 4.6-2.8 5.6"/>

                    <path d="M9.5 10.5c1.8-.5 3.7-.4 5.1.3"/>

                    <path d="M12.6 16.2c-.8 1.6-1.5 3.1-3.7 3.1"/>

                </svg>

            </div>

            <span class="technology-name">
                PostgreSQL
            </span>

        </div>

    </div>

</section>


<!-- =====================================================
     ENTERPRISE FOOTER
===================================================== -->

<footer class="border-t border-white/10 bg-black/30 backdrop-blur-xl">

<div class="max-w-7xl mx-auto px-8 py-20">

<div class="grid lg:grid-cols-4 gap-12">

<!-- Smart Royal -->

<div>

<h3 class="text-2xl font-bold">
    SMART ROYAL
</h3>

<p class="text-gray-400 mt-4 leading-8">

Human Resource Consultancy & Recruitment Services supporting
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

<p>
    Employee Management
</p>

<p>
    Attendance Tracking
</p>

<p>
    Payroll Management
</p>

<p>
    Departments & Units
</p>

<p>
    Performance Reports
</p>

<p>
    Recruitment Support
</p>

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


<!-- =====================================================
     SCRIPTS
===================================================== -->

<script>

/* =========================================================
   LIVE CLOCK
========================================================= */

function updateClock() {

    const clock =
        document.getElementById("liveClock");

    if (!clock) {
        return;
    }

    const now =
        new Date();

    clock.innerHTML =
        now.toLocaleTimeString([], {

            hour: '2-digit',
            minute: '2-digit',
            second: '2-digit'

        });
}

setInterval(
    updateClock,
    1000
);

updateClock();


/* =========================================================
   SCROLL REVEAL
========================================================= */

const revealElements =
    document.querySelectorAll('.reveal');

const revealObserver =
    new IntersectionObserver(

        (entries, observer) => {

            entries.forEach(entry => {

                if (entry.isIntersecting) {

                    entry.target.classList.add(
                        'active'
                    );

                    observer.unobserve(
                        entry.target
                    );

                }

            });

        },

        {
            threshold: 0.12
        }

    );


revealElements.forEach(element => {

    revealObserver.observe(
        element
    );

});


/* =========================================================
   ANIMATED COUNTERS
========================================================= */

const counters =
    document.querySelectorAll('.counter-value');

let countersStarted =
    false;


function animateCounter(element) {

    const target =
        parseInt(
            element.dataset.count || 0
        );

    const suffix =
        element.dataset.suffix || '';

    const duration =
        1800;

    const startTime =
        performance.now();


    function updateCounter(currentTime) {

        const elapsed =
            currentTime - startTime;

        const progress =
            Math.min(
                elapsed / duration,
                1
            );

        const eased =
            1 - Math.pow(
                1 - progress,
                3
            );

        const current =
            Math.floor(
                target * eased
            );

        element.textContent =
            current + suffix;


        if (progress < 1) {

            requestAnimationFrame(
                updateCounter
            );

        } else {

            element.textContent =
                target + suffix;

        }

    }

    requestAnimationFrame(
        updateCounter
    );
}


const counterObserver =
    new IntersectionObserver(

        (entries, observer) => {

            entries.forEach(entry => {

                if (
                    entry.isIntersecting &&
                    !countersStarted
                ) {

                    countersStarted =
                        true;

                    counters.forEach(counter => {

                        animateCounter(
                            counter
                        );

                    });

                    observer.disconnect();

                }

            });

        },

        {
            threshold: 0.35
        }

    );


if (counters.length) {

    counterObserver.observe(
        counters[0]
    );

}


/* =========================================================
   MODULE STAGGER ANIMATION
========================================================= */

const moduleCards =
    document.querySelectorAll(
        '.module-card'
    );


moduleCards.forEach(
    (card, index) => {

        card.style.transitionDelay =
            `${index * 70}ms`;

    }
);


/* =========================================================
   SUBTLE MOUSE PARALLAX
========================================================= */

const heroSection =
    document.querySelector('main');


if (heroSection) {

    heroSection.addEventListener(
        'mousemove',
        (event) => {

            const x =
                (
                    event.clientX /
                    window.innerWidth -
                    .5
                ) * 8;

            const y =
                (
                    event.clientY /
                    window.innerHeight -
                    .5
                ) * 8;


            const orbs =
                document.querySelectorAll(
                    '.floating-orb'
                );


            orbs.forEach(
                (orb, index) => {

                    const factor =
                        (index + 1) * .45;

                    orb.style.transform =
                        `translate(
                            ${x * factor}px,
                            ${y * factor}px
                        )`;

                }
            );

        }
    );

}


/* =========================================================
   ACTIVE NAV SCROLL EFFECT
========================================================= */

window.addEventListener(
    'scroll',
    () => {

        const header =
            document.querySelector(
                'header'
            );


        if (!header) {
            return;
        }


        if (window.scrollY > 20) {

            header.style.boxShadow =
                '0 15px 40px rgba(0,0,0,.35)';

            header.style.borderColor =
                'rgba(212,160,23,.14)';

        } else {

            header.style.boxShadow =
                'none';

            header.style.borderColor =
                'rgba(255,255,255,.10)';

        }

    }
);


/* =========================================================
   TECHNOLOGY FLOW INTERACTION
========================================================= */

const technologyFlow =
    document.querySelector(
        '.technology-flow'
    );

const technologyTrack =
    document.querySelector(
        '.technology-track'
    );


if (
    technologyFlow &&
    technologyTrack
) {

    technologyFlow.addEventListener(
        'mouseenter',
        () => {

            technologyTrack.style.animationPlayState =
                'paused';

        }
    );


    technologyFlow.addEventListener(
        'mouseleave',
        () => {

            technologyTrack.style.animationPlayState =
                'running';

        }
    );

}

</script>

</body>
</html>