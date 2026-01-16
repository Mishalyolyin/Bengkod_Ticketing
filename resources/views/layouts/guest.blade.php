<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'TIXORA') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen bg-slate-50 text-ink">
    @php
        $user = auth()->user();
        $isAuthed = (bool) $user;
        $isAdmin = $isAuthed && (($user->role ?? null) === 'admin');

        $homeHref = route('home');
        $dashHref = $isAuthed
            ? ($isAdmin ? route('admin.dashboard') : route('dashboard'))
            : route('login');
    @endphp

    <div class="min-h-screen bg-noise">
        <header class="sticky top-0 z-40">
            <div class="backdrop-blur bg-white/70 border-b border-white/60">
                <div class="mx-auto max-w-6xl px-4 py-3 flex items-center justify-between gap-4">
                    {{-- FIX: jangan bikin teks brand lagi, karena component logo sudah termasuk --}}
                    <a href="{{ $homeHref }}" class="inline-flex items-center select-none">
                        <x-application-logo class="h-9 w-9" />
                    </a>

                    <div class="flex items-center gap-2">
                        @if(\Illuminate\Support\Facades\Route::has('public.events.index'))
                            <a href="{{ route('public.events.index') }}" class="btn-ghost">Events</a>
                        @endif

                        @auth
                            <a href="{{ $dashHref }}" class="btn-ghost">Dashboard</a>

                            @if(!$isAdmin && \Illuminate\Support\Facades\Route::has('buyer.orders.index'))
                                <a href="{{ route('buyer.orders.index') }}" class="btn-ghost">Orders</a>
                            @endif

                            @if($isAdmin)
                                @if(\Illuminate\Support\Facades\Route::has('admin.events.index'))
                                    <a href="{{ route('admin.events.index') }}" class="btn-ghost">Admin Events</a>
                                @endif
                                @if(\Illuminate\Support\Facades\Route::has('admin.kategori.index'))
                                    <a href="{{ route('admin.kategori.index') }}" class="btn-ghost">Kategori</a>
                                @endif
                                @if(\Illuminate\Support\Facades\Route::has('admin.orders.index'))
                                    <a href="{{ route('admin.orders.index') }}" class="btn-ghost">Transaksi</a>
                                @endif
                            @endif

                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="btn-ghost">Logout</button>
                            </form>
                        @else
                            <a href="{{ route('login') }}" class="btn-ghost">Login</a>
                            <a href="{{ route('register') }}" class="btn-primary">Register</a>
                        @endauth
                    </div>
                </div>
            </div>
        </header>

        <main class="mx-auto max-w-6xl px-4 py-10">
            {{ $slot }}
        </main>

        <footer class="mt-10">
            <div class="mx-auto max-w-6xl px-4 py-10 text-sm text-slate-500">
                <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-6">
                    <div class="space-y-2">
                        {{-- FIX: logo cukup sekali --}}
                        <div class="inline-flex items-center">
                            <x-application-logo class="h-8 w-8" />
                        </div>
                        <div class="text-xs">
                            © {{ date('Y') }} TIXORA. All rights reserved.
                        </div>
                    </div>

                    <div class="grid grid-cols-2 md:grid-cols-3 gap-x-8 gap-y-2">
                        <a class="hover:text-ink transition" href="{{ route('home') }}">Home</a>
                        @if(\Illuminate\Support\Facades\Route::has('public.events.index'))
                            <a class="hover:text-ink transition" href="{{ route('public.events.index') }}">Events</a>
                        @endif
                        <a class="hover:text-ink transition" href="#">Help</a>
                        <a class="hover:text-ink transition" href="#">Terms</a>
                        <a class="hover:text-ink transition" href="#">Privacy</a>

                        @auth
                            <a class="hover:text-ink transition" href="{{ $dashHref }}">Dashboard</a>
                            @if(!$isAdmin && \Illuminate\Support\Facades\Route::has('buyer.orders.index'))
                                <a class="hover:text-ink transition" href="{{ route('buyer.orders.index') }}">Orders</a>
                            @endif
                            @if($isAdmin && \Illuminate\Support\Facades\Route::has('admin.orders.index'))
                                <a class="hover:text-ink transition" href="{{ route('admin.orders.index') }}">Transaksi</a>
                            @endif
                        @else
                            <a class="hover:text-ink transition" href="{{ route('login') }}">Login</a>
                        @endauth
                    </div>
                </div>
            </div>
        </footer>
    </div>
</body>
</html>
