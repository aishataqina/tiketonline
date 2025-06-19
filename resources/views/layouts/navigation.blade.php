<nav x-data="{ open: false }" class="bg-white border-b border-gray-100">
    <!-- Primary Navigation Menu -->
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
                    <x-nav-link :href="route('orders.index')" :active="request()->routeIs('orders.index')">
                        Pesanan Saya
                    </x-nav-link>
                    <x-nav-link :href="route('transactions.store')" :active="request()->routeIs('transactions.*')">
                        Pembayaran
                    </x-nav-link>
                    <x-nav-link :href="route('cart.index')" :active="request()->routeIs('cart.index')">
                        Keranjang
                    </x-nav-link>
                </div>
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button
                            class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                            <div>{{ Auth::user()->name }}</div>

                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg"
                                    viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                        clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">
                            {{ __('Profile') }}
                        </x-dropdown-link>

                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf

                            <x-dropdown-link :href="route('logout')"
                                onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open"
                    class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-700 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-700 transition duration-150 ease-in-out">
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

    <!-- Responsive Navigation Menu -->
    <div :class="{ 'block': open, 'hidden': !open }" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('orders.index')" :active="request()->routeIs('orders.index')">
                Pesanan Saya
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('transactions.store')" :active="request()->routeIs('transactions.*')">
                Pembayaran
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('cart.index')" :active="request()->routeIs('cart.index')">
                Keranjang
            </x-responsive-nav-link>
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-200">
            <div class="px-4">
                <div class="font-medium text-base text-gray-700">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-gray-700">{{ Auth::user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')">
                    {{ __('Profile') }}
                </x-responsive-nav-link>

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <x-responsive-nav-link :href="route('logout')"
                        onclick="event.preventDefault();
                                        this.closest('form').submit();">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>
