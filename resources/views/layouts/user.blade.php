<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>JelajahEvent</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <style>
        /* Custom font for Inter, if needed */
        body {
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>

<body class="bg-gray-100 antialiased min-h-screen flex flex-col">
    <!-- Navbar -->
    <nav class="bg-white shadow-md py-1">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <a class="flex items-center text-gray-700 font-extrabold text-xl" href="/">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor"
                        class="bi bi-ticket-perforated text-blue-600" viewBox="0 0 16 16">
                        <path
                            d="M0 4.5A1.5 1.5 0 0 1 1.5 3H14.5A1.5 1.5 0 0 1 16 4.5c0 .563-.186 1.07-.5 1.5.314.43.5.937.5 1.5s-.186 1.07-.5 1.5c.314.43.5.937.5 1.5A1.5 1.5 0 0 1 14.5 13H1.5A1.5 1.5 0 0 1 0 11.5c0-.563.186-1.07.5-1.5A2.5 2.5 0 0 1 0 8c0-.563.186-1.07.5-1.5A2.5 2.5 0 0 1 0 4.5zm1.5-.5A.5.5 0 0 0 1 4.5c0 .276.112.526.293.707A.5.5 0 0 1 1.5 6a.5.5 0 0 1-.207.793A.5.5 0 0 0 1 7.5c0 .276.112.526.293.707A.5.5 0 0 1 1.5 9a.5.5 0 0 1-.207.793A.5.5 0 0 0 1 10.5a.5.5 0 0 0 .5.5h13a.5.5 0 0 0 .5-.5c0-.276-.112-.526-.293-.707A.5.5 0 0 1 14.5 10a.5.5 0 0 1 .207-.793A.5.5 0 0 0 15 8.5a.5.5 0 0 0-.5-.5h-13a.5.5 0 0 0-.5.5c0 .276.112.526.293.707A.5.5 0 0 1 1.5 10a.5.5 0 0 1-.207.793A.5.5 0 0 0 1 11.5a.5.5 0 0 0 .5.5h13a.5.5 0 0 0 .5-.5c0-.276-.112-.526-.293-.707A.5.5 0 0 1 14.5 12a.5.5 0 0 1 .207-.793A.5.5 0 0 0 15 10.5a.5.5 0 0 0-.5-.5h-13a.5.5 0 0 0-.5.5z" />
                    </svg>
                    <span class="ml-2">JelajahEvent</span>
                </a>
                <div class="hidden lg:flex lg:items-center lg:w-auto" id="desktop-menu">
                    <ul class="flex flex-col lg:flex-row lg:ml-auto gap-4">
                        @auth
                            <li>
                                <a class="block py-2 px-4 text-gray-700 hover:text-blue-600 rounded hover:bg-gray-100 lg:hover:bg-transparent lg:p-0 font-medium {{ request()->is('orders*') ? 'text-blue-600' : '' }}"
                                    href="{{ route('orders.index') }}">Pesanan Saya</a>
                            </li>
                            <li>
                                <a class="block py-2 px-4 text-gray-700 hover:text-blue-600 rounded hover:bg-gray-100 lg:hover:bg-transparent lg:p-0 font-medium {{ request()->is('transactions*') ? 'text-blue-600' : '' }}"
                                    href="{{ route('transactions.store') }}">Pembayaran</a>
                            </li>
                            <li>
                                <a href="{{ route('cart.index') }}"
                                    class="block py-2 px-4 text-gray-700 hover:text-blue-600 rounded hover:bg-gray-100 lg:hover:bg-transparent lg:p-0 font-medium">Keranjang</a>
                            </li>
                            <li>
                                <form method="POST" action="{{ route('logout') }}" class="inline">
                                    @csrf
                                    <button type="submit"
                                        class="block py-2 px-4 text-gray-700 hover:text-blue-600 rounded hover:bg-gray-100 lg:hover:bg-transparent lg:p-0 font-medium w-full text-left">Logout</button>
                                </form>
                            </li>
                        @else
                            <li>
                                <a class="block py-2 px-4 text-gray-700 hover:text-blue-600 rounded hover:bg-gray-100 lg:hover:bg-transparent lg:p-0 font-medium {{ request()->is('login') ? 'text-blue-600' : '' }}"
                                    href="{{ route('login') }}">Login</a>
                            </li>
                        @endauth
                    </ul>
                </div>
            </div>
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

    <script>
        // JavaScript for mobile menu toggle
        document.addEventListener('DOMContentLoaded', function() {
            const mobileMenuButton = document.getElementById('mobile-menu-button');
            const mobileMenu = document.getElementById('mobile-menu');

            mobileMenuButton.addEventListener('click', function() {
                const isExpanded = this.getAttribute('aria-expanded') === 'true';
                this.setAttribute('aria-expanded', !isExpanded);
                mobileMenu.classList.toggle('hidden');
                // Toggle between menu and close icons
                this.querySelector('svg:first-child').classList.toggle('hidden'); // Menu icon
                this.querySelector('svg:last-child').classList.toggle('hidden'); // X icon
            });
        });
    </script>
</body>

</html>
