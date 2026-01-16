<nav x-data="{ open: false }" class="bg-white/70 backdrop-blur border-b border-gray-100 sticky top-0 z-40">
    @php
        $isAuth  = auth()->check();
        $isAdmin = $isAuth && (auth()->user()->role ?? null) === 'admin';

        $dashHref   = $isAdmin ? route('admin.dashboard') : route('dashboard');
        $dashActive = request()->routeIs('dashboard') || request()->routeIs('admin.dashboard');
    @endphp

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            {{-- LEFT --}}
            <div class="flex">
                {{-- LOGO --}}
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('home') }}" class="flex items-center gap-3">
                        <x-application-logo class="block h-9 w-auto" />
                    </a>
                </div>

                {{-- DESKTOP MENU --}}
                <div class="hidden space-x-2 sm:-my-px sm:ms-10 sm:flex items-center">
                    {{-- Home --}}
                    <x-nav-link :href="route('home')" :active="request()->routeIs('home')">
                        {{ __('Home') }}
                    </x-nav-link>

                    {{-- Public Events --}}
                    @if(Route::has('public.events.index'))
                        <x-nav-link :href="route('public.events.index')" :active="request()->routeIs('public.events.*')">
                            {{ __('Events') }}
                        </x-nav-link>
                    @endif

                    @auth
                        {{-- Dashboard --}}
                        <x-nav-link :href="$dashHref" :active="$dashActive">
                            {{ __('Dashboard') }}
                        </x-nav-link>

                        @if($isAdmin)
                            {{-- Admin Events --}}
                            @if(Route::has('admin.events.index'))
                                <x-nav-link :href="route('admin.events.index')" :active="request()->routeIs('admin.events.*')">
                                    {{ __('Admin Events') }}
                                </x-nav-link>
                            @endif

                            {{-- Kategori --}}
                            @if(Route::has('admin.kategori.index'))
                                <x-nav-link :href="route('admin.kategori.index')" :active="request()->routeIs('admin.kategori.*')">
                                    {{ __('Kategori') }}
                                </x-nav-link>
                            @endif

                            {{-- Transaksi --}}
                            @if(Route::has('admin.orders.index'))
                                <x-nav-link :href="route('admin.orders.index')" :active="request()->routeIs('admin.orders.*')">
                                    {{ __('Transaksi') }}
                                </x-nav-link>
                            @endif
                        @else
                            {{-- Buyer Orders --}}
                            @if(Route::has('buyer.orders.index'))
                                <x-nav-link :href="route('buyer.orders.index')" :active="request()->routeIs('buyer.orders.*')">
                                    {{ __('Orders') }}
                                </x-nav-link>
                            @endif
                        @endif
                    @endauth
                </div>
            </div>

            {{-- RIGHT --}}
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                @auth
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <button class="inline-flex items-center gap-2 px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-700 bg-white/60 hover:bg-white hover:text-gray-900 focus:outline-none transition ease-in-out duration-150">
                                <div class="flex flex-col items-start leading-tight">
                                    <span class="font-semibold">{{ Auth::user()->name }}</span>
                                    @if($isAdmin)
                                        <span class="text-[11px] text-indigo-600 font-semibold -mt-0.5">Admin</span>
                                    @else
                                        <span class="text-[11px] text-gray-500 -mt-0.5">User</span>
                                    @endif
                                </div>

                                <div class="ms-1">
                                    <svg class="fill-current h-4 w-4 text-gray-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                            </button>
                        </x-slot>

                        <x-slot name="content">
                            {{-- Profile --}}
                            <x-dropdown-link :href="route('profile.edit')">
                                {{ __('Profile') }}
                            </x-dropdown-link>

                            {{-- Public Events --}}
                            @if(Route::has('public.events.index'))
                                <x-dropdown-link :href="route('public.events.index')">
                                    {{ __('Events') }}
                                </x-dropdown-link>
                            @endif

                            @if($isAdmin)
                                <div class="border-t border-gray-100 my-1"></div>

                                <x-dropdown-link :href="route('admin.dashboard')">
                                    {{ __('Admin Panel') }}
                                </x-dropdown-link>

                                @if(Route::has('admin.events.index'))
                                    <x-dropdown-link :href="route('admin.events.index')">
                                        {{ __('Admin Events') }}
                                    </x-dropdown-link>
                                @endif

                                @if(Route::has('admin.kategori.index'))
                                    <x-dropdown-link :href="route('admin.kategori.index')">
                                        {{ __('Kategori') }}
                                    </x-dropdown-link>
                                @endif

                                @if(Route::has('admin.orders.index'))
                                    <x-dropdown-link :href="route('admin.orders.index')">
                                        {{ __('Transaksi') }}
                                    </x-dropdown-link>
                                @endif
                            @else
                                @if(Route::has('buyer.orders.index'))
                                    <x-dropdown-link :href="route('buyer.orders.index')">
                                        {{ __('Orders') }}
                                    </x-dropdown-link>
                                @endif

                                <x-dropdown-link :href="route('home')">
                                    {{ __('Public Home') }}
                                </x-dropdown-link>
                            @endif

                            <div class="border-t border-gray-100 my-1"></div>

                            {{-- Logout --}}
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault(); this.closest('form').submit();">
                                    {{ __('Log Out') }}
                                </x-dropdown-link>
                            </form>
                        </x-slot>
                    </x-dropdown>
                @else
                    <div class="flex items-center gap-2">
                        <a href="{{ route('login') }}" class="btn-ghost">Login</a>
                        <a href="{{ route('register') }}" class="btn-primary">Register</a>
                    </div>
                @endauth
            </div>

            {{-- HAMBURGER --}}
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-500 hover:text-gray-700 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-700 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex"
                            stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden"
                            stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    {{-- RESPONSIVE MENU --}}
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('home')" :active="request()->routeIs('home')">
                {{ __('Home') }}
            </x-responsive-nav-link>

            @if(Route::has('public.events.index'))
                <x-responsive-nav-link :href="route('public.events.index')" :active="request()->routeIs('public.events.*')">
                    {{ __('Events') }}
                </x-responsive-nav-link>
            @endif

            @auth
                <x-responsive-nav-link :href="$dashHref" :active="$dashActive">
                    {{ __('Dashboard') }}
                </x-responsive-nav-link>

                @if($isAdmin)
                    @if(Route::has('admin.events.index'))
                        <x-responsive-nav-link :href="route('admin.events.index')" :active="request()->routeIs('admin.events.*')">
                            {{ __('Admin Events') }}
                        </x-responsive-nav-link>
                    @endif

                    @if(Route::has('admin.kategori.index'))
                        <x-responsive-nav-link :href="route('admin.kategori.index')" :active="request()->routeIs('admin.kategori.*')">
                            {{ __('Kategori') }}
                        </x-responsive-nav-link>
                    @endif

                    @if(Route::has('admin.orders.index'))
                        <x-responsive-nav-link :href="route('admin.orders.index')" :active="request()->routeIs('admin.orders.*')">
                            {{ __('Transaksi') }}
                        </x-responsive-nav-link>
                    @endif
                @else
                    @if(Route::has('buyer.orders.index'))
                        <x-responsive-nav-link :href="route('buyer.orders.index')" :active="request()->routeIs('buyer.orders.*')">
                            {{ __('Orders') }}
                        </x-responsive-nav-link>
                    @endif
                @endif
            @else
                <x-responsive-nav-link :href="route('login')" :active="request()->routeIs('login')">
                    {{ __('Login') }}
                </x-responsive-nav-link>

                <x-responsive-nav-link :href="route('register')" :active="request()->routeIs('register')">
                    {{ __('Register') }}
                </x-responsive-nav-link>
            @endauth
        </div>

        @auth
            <div class="pt-4 pb-1 border-t border-gray-200">
                <div class="px-4">
                    <div class="font-medium text-base text-gray-800">{{ Auth::user()->name }}</div>
                    <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
                </div>

                <div class="mt-3 space-y-1">
                    <x-responsive-nav-link :href="route('profile.edit')">
                        {{ __('Profile') }}
                    </x-responsive-nav-link>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault(); this.closest('form').submit();">
                            {{ __('Log Out') }}
                        </x-responsive-nav-link>
                    </form>
                </div>
            </div>
        @endauth
    </div>
</nav>
