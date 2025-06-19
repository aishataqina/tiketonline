<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>JelajahEvent</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <style>
        /* Custom font for Inter, if needed */
        body {
            font-family: 'Inter', sans-serif;
        }

        [x-cloak] {
            display: none !important;
        }
    </style>
</head>

<body class="bg-gray-100 antialiased min-h-screen flex flex-col">
    <nav x-data="{ open: false, userDropdown: false }" class="bg-white shadow-md">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex">
                    <!-- Logo -->
                    <div class="shrink-0 flex items-center">
                        <a href="/" class="flex items-center text-gray-700 font-extrabold text-xl">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor"
                                class="bi bi-ticket-perforated text-blue-600" viewBox="0 0 16 16">
                                <path
                                    d="M0 4.5A1.5 1.5 0 0 1 1.5 3H14.5A1.5 1.5 0 0 1 16 4.5c0 .563-.186 1.07-.5 1.5.314.43.5.937.5 1.5s-.186 1.07-.5 1.5c.314.43.5.937.5 1.5A1.5 1.5 0 0 1 14.5 13H1.5A1.5 1.5 0 0 1 0 11.5c0-.563.186-1.07.5-1.5A2.5 2.5 0 0 1 0 8c0-.563.186-1.07.5-1.5A2.5 2.5 0 0 1 0 4.5zm1.5-.5A.5.5 0 0 0 1 4.5c0 .276.112.526.293.707A.5.5 0 0 1 1.5 6a.5.5 0 0 1-.207.793A.5.5 0 0 0 1 7.5c0 .276.112.526.293.707A.5.5 0 0 1 1.5 9a.5.5 0 0 1-.207.793A.5.5 0 0 0 1 10.5a.5.5 0 0 0 .5.5h13a.5.5 0 0 0 .5-.5c0-.276-.112-.526-.293-.707A.5.5 0 0 1 14.5 10a.5.5 0 0 1 .207-.793A.5.5 0 0 0 15 8.5a.5.5 0 0 0-.5-.5h-13a.5.5 0 0 0-.5.5c0 .276.112.526.293.707A.5.5 0 0 1 1.5 10a.5.5 0 0 1-.207.793A.5.5 0 0 0 1 11.5a.5.5 0 0 0 .5.5h13a.5.5 0 0 0 .5-.5c0-.276-.112-.526-.293-.707A.5.5 0 0 1 14.5 12a.5.5 0 0 1 .207-.793A.5.5 0 0 0 15 10.5a.5.5 0 0 0-.5-.5h-13a.5.5 0 0 0-.5.5z" />
                            </svg>
                            <span class="ml-2">JelajahEvent</span>
                        </a>
                    </div>

                    <!-- Navigation Links -->
                    <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
                        @auth
                            <a href="{{ route('orders.index') }}"
                                class="inline-flex items-center px-1 pt-1 text-sm font-medium leading-5 text-gray-900 hover:text-blue-600 {{ request()->routeIs('orders.index') ? 'border-b-2 border-blue-600' : '' }}">
                                Pesanan Saya
                            </a>
                            <a href="{{ route('transactions.store') }}"
                                class="inline-flex items-center px-1 pt-1 text-sm font-medium leading-5 text-gray-900 hover:text-blue-600 {{ request()->routeIs('transactions.*') ? 'border-b-2 border-blue-600' : '' }}">
                                Pembayaran
                            </a>
                            <a href="{{ route('cart.index') }}"
                                class="inline-flex items-center px-1 pt-1 text-sm font-medium leading-5 text-gray-900 hover:text-blue-600 {{ request()->routeIs('cart.index') ? 'border-b-2 border-blue-600' : '' }}">
                                Keranjang
                            </a>
                        @endauth
                    </div>
                </div>

                <!-- Settings Dropdown -->
                <div class="hidden sm:flex sm:items-center sm:ml-6">
                    @auth
                        <div class="ml-3 relative">
                            <div>
                                <button @click="userDropdown = !userDropdown"
                                    class="flex items-center text-sm font-medium text-gray-700 hover:text-gray-800 focus:outline-none transition duration-150 ease-in-out">
                                    <div>{{ Auth::user()->name }}</div>

                                    <div class="ml-1">
                                        <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg"
                                            viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                </button>
                            </div>

                            <div x-show="userDropdown" @click.away="userDropdown = false"
                                x-transition:enter="transition ease-out duration-200"
                                x-transition:enter-start="transform opacity-0 scale-95"
                                x-transition:enter-end="transform opacity-100 scale-100"
                                x-transition:leave="transition ease-in duration-75"
                                x-transition:leave-start="transform opacity-100 scale-100"
                                x-transition:leave-end="transform opacity-0 scale-95"
                                class="absolute right-0 z-50 mt-2 w-48 rounded-md shadow-lg origin-top-right" x-cloak>
                                <div class="rounded-md ring-1 ring-black ring-opacity-5 py-1 bg-white">
                                    <a href="{{ route('profile.edit') }}"
                                        class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Profile</a>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit"
                                            class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                            Logout
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @else
                        <a href="{{ route('login') }}" class="text-gray-700 hover:text-blue-600">Login</a>
                    @endauth
                </div>

                <!-- Hamburger -->
                <div class="-mr-2 flex items-center sm:hidden">
                    <button @click="open = !open"
                        class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                        <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                            <path :class="{ 'hidden': open, 'inline-flex': !open }" class="inline-flex"
                                stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 6h16M4 12h16M4 18h16" />
                            <path :class="{ 'hidden': !open, 'inline-flex': open }" class="hidden" stroke-linecap="round"
                                stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile menu -->
        <div :class="{ 'block': open, 'hidden': !open }" class="hidden sm:hidden">
            <div class="pt-2 pb-3 space-y-1">
                @auth
                    <a href="{{ route('orders.index') }}"
                        class="block pl-3 pr-4 py-2 border-l-4 text-base font-medium {{ request()->routeIs('orders.index') ? 'border-blue-600 text-blue-600 bg-blue-50' : 'border-transparent text-gray-600 hover:text-gray-800 hover:bg-gray-50 hover:border-gray-300' }}">
                        Pesanan Saya
                    </a>
                    <a href="{{ route('transactions.store') }}"
                        class="block pl-3 pr-4 py-2 border-l-4 text-base font-medium {{ request()->routeIs('transactions.*') ? 'border-blue-600 text-blue-600 bg-blue-50' : 'border-transparent text-gray-600 hover:text-gray-800 hover:bg-gray-50 hover:border-gray-300' }}">
                        Pembayaran
                    </a>
                    <a href="{{ route('cart.index') }}"
                        class="block pl-3 pr-4 py-2 border-l-4 text-base font-medium {{ request()->routeIs('cart.index') ? 'border-blue-600 text-blue-600 bg-blue-50' : 'border-transparent text-gray-600 hover:text-gray-800 hover:bg-gray-50 hover:border-gray-300' }}">
                        Keranjang
                    </a>
                @endauth
            </div>

            <!-- Mobile user menu -->
            @auth
                <div class="pt-4 pb-1 border-t border-gray-200">
                    <div class="px-4">
                        <div class="font-medium text-base text-gray-800">{{ Auth::user()->name }}</div>
                        <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
                    </div>

                    <div class="mt-3 space-y-1">
                        <a href="{{ route('profile.edit') }}"
                            class="block pl-3 pr-4 py-2 border-l-4 border-transparent text-base font-medium text-gray-600 hover:text-gray-800 hover:bg-gray-50 hover:border-gray-300">
                            Profile
                        </a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit"
                                class="block w-full text-left pl-3 pr-4 py-2 border-l-4 border-transparent text-base font-medium text-gray-600 hover:text-gray-800 hover:bg-gray-50 hover:border-gray-300">
                                Logout
                            </button>
                        </form>
                    </div>
                </div>
            @endauth
        </div>
    </nav>

    <!-- Main Content -->
    <div class="main-content py-4 flex-grow">
        @yield('content')
    </div>

    <!-- Footer -->
    <footer class="bg-white border-t border-gray-200 py-3 text-center text-gray-700 text-sm">
        &copy; 2024 JelajahEvent. All rights reserved.
    </footer>
</body>

</html>
