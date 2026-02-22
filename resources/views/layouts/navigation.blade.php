@php
use App\Models\ChatRoom;

$chatRoom = null;

// Get the chat room for the user's unit
if(auth()->user()->unit_id) {
    $chatRoom = ChatRoom::where('unit_id', auth()->user()->unit_id)->first();
}

// Fallback to any chat room if none found
if(!$chatRoom) {
    $chatRoom = ChatRoom::first();
}
@endphp

<nav x-data="{ open: false, openNotif: false }" class="bg-white border-b border-gray-100">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">

            <!-- Left: Logo + Links -->
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}">
                        <x-application-logo class="block h-9 w-auto fill-current text-gray-800" />
                    </a>
                </div>

                <!-- Desktop Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    <x-nav-link
                        :href="route('dashboard')"
                        :active="request()->routeIs('dashboard')"
                    >
                        {{ __('Dashboard') }}
                    </x-nav-link>
                </div>
            </div>

            <!-- Right: Notifications + Chat + User -->
            <div class="hidden sm:flex sm:items-center sm:ms-6 gap-4">

                <!-- 🔔 Notifications -->
                <div class="relative">
                    <button
                        @click="openNotif = !openNotif"
                        class="relative text-xl focus:outline-none"
                    >
                        🔔
                        @if(auth()->user()->unreadNotifications->count())
                            <span
                                class="absolute -top-2 -right-2 bg-red-600 text-white text-xs px-1.5 rounded-full"
                            >
                                {{ auth()->user()->unreadNotifications->count() }}
                            </span>
                        @endif
                    </button>

                    <!-- Notification Dropdown -->
                    <div
                        x-show="openNotif"
                        x-cloak
                        @click.away="openNotif = false"
                        class="absolute right-0 mt-2 w-80 bg-white shadow-lg rounded-lg z-50"
                    >
                        <div class="p-3 border-b font-semibold">
                            Notifications
                        </div>

                        <div class="max-h-64 overflow-y-auto">
                            @forelse(auth()->user()->unreadNotifications as $notification)
                                <div class="p-3 text-sm border-b hover:bg-gray-100">
                                    {{ $notification->data['message'] ?? 'New notification' }}
                                </div>
                            @empty
                                <div class="p-3 text-sm text-gray-500">
                                    No new notifications
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>

                <!-- 💬 Chat Room -->
                <a
                    href="{{ $chatRoom ? route('chat.index', $chatRoom->id) : '#' }}"
                    class="bg-indigo-600 text-white px-3 py-2 rounded hover:bg-indigo-700 transition"
                >
                    💬 Chat
                </a>

                <!-- User Dropdown -->
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button
                            class="inline-flex items-center px-3 py-2 border border-transparent text-sm font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition"
                        >
                            <div>{{ Auth::user()->name }}</div>

                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4" viewBox="0 0 20 20">
                                    <path
                                        fill-rule="evenodd"
                                        d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a 1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                        clip-rule="evenodd"
                                    />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">
                            {{ __('Profile') }}
                        </x-dropdown-link>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link
                                :href="route('logout')"
                                onclick="event.preventDefault(); this.closest('form').submit();"
                            >
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button
                    @click="open = !open"
                    class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none transition"
                >
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path
                            :class="{'hidden': open, 'inline-flex': !open}"
                            class="inline-flex"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16"
                        />
                        <path
                            :class="{'hidden': !open, 'inline-flex': open}"
                            class="hidden"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M6 18L18 6M6 6l12 12"
                        />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Mobile Menu -->
    <div :class="{'block': open, 'hidden': !open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link
                :href="route('dashboard')"
                :active="request()->routeIs('dashboard')"
            >
                {{ __('Dashboard') }}
            </x-responsive-nav-link>

            <x-responsive-nav-link
                :href="$chatRoom ? route('chat.index', $chatRoom->id) : '#'"
            >
                💬 Chat
            </x-responsive-nav-link>

            <x-responsive-nav-link href="#">
                🔔 Notifications ({{ auth()->user()->unreadNotifications->count() }})
            </x-responsive-nav-link>
        </div>

        <!-- Mobile User Info -->
        <div class="pt-4 pb-1 border-t border-gray-200">
            <div class="px-4">
                <div class="font-medium text-base text-gray-800">
                    {{ Auth::user()->name }}
                </div>
                <div class="font-medium text-sm text-gray-500">
                    {{ Auth::user()->email }}
                </div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')">
                    {{ __('Profile') }}
                </x-responsive-nav-link>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-responsive-nav-link
                        :href="route('logout')"
                        onclick="event.preventDefault(); this.closest('form').submit();"
                    >
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>
