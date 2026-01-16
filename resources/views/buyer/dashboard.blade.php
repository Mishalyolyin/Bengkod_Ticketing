<x-app-layout>
    <x-slot name="header">
        <div class="flex items-start justify-between gap-4">
            <div>
                <h2 class="font-extrabold text-xl text-ink leading-tight">Dashboard</h2>
                <p class="text-sm text-slate-500">
                    Ringkasan aktivitas akun dan pembelian tiket Anda.
                </p>
            </div>

            <a href="{{ route('public.events.index') }}" class="btn-primary">
                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                    <path d="M11 19a8 8 0 1 1 0-16 8 8 0 0 1 0 16Z" stroke="currentColor" stroke-width="2"/>
                    <path d="M21 21l-4.3-4.3" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                </svg>
                Cari Event
            </a>
        </div>
    </x-slot>

    @php
        $recentOrders = $recentOrders ?? collect();
        $upcomingOrders = $upcomingOrders ?? collect();
        $ordersCount = (int)($ordersCount ?? 0);
        $ticketsBought = (int)($ticketsBought ?? 0);
        $totalSpent = (int)($totalSpent ?? 0);
    @endphp

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Stats --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="card p-5">
                    <div class="flex items-start justify-between">
                        <div>
                            <div class="text-xs text-slate-500">Total Pesanan</div>
                            <div class="text-2xl font-extrabold text-ink mt-1">{{ $ordersCount }}</div>
                        </div>
                        <div class="h-10 w-10 rounded-2xl bg-brand-50 border border-brand-100 flex items-center justify-center text-brand-700">
                            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                <path d="M7 7h14l-1.2 7.2a3 3 0 0 1-3 2.5H10.2a3 3 0 0 1-3-2.5L6 4H3" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                                <path d="M10 21a1 1 0 1 0 0-2 1 1 0 0 0 0 2ZM17 21a1 1 0 1 0 0-2 1 1 0 0 0 0 2Z" fill="currentColor"/>
                            </svg>
                        </div>
                    </div>
                    <div class="text-xs text-slate-500 mt-2">Jumlah transaksi pembelian tiket.</div>
                </div>

                <div class="card p-5">
                    <div class="flex items-start justify-between">
                        <div>
                            <div class="text-xs text-slate-500">Total Pengeluaran</div>
                            <div class="text-2xl font-extrabold text-ink mt-1">
                                Rp {{ number_format($totalSpent, 0, ',', '.') }}
                            </div>
                        </div>
                        <div class="h-10 w-10 rounded-2xl bg-emerald-50 border border-emerald-100 flex items-center justify-center text-emerald-700">
                            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                <path d="M12 1v22" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                                <path d="M17 6.5c0-2-2.2-3.5-5-3.5S7 4.5 7 6.5 9.2 10 12 10s5 1.5 5 3.5S14.8 17 12 17s-5-1.5-5-3.5" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                            </svg>
                        </div>
                    </div>
                    <div class="text-xs text-slate-500 mt-2">Akumulasi total pembayaran Anda.</div>
                </div>

                <div class="card p-5">
                    <div class="flex items-start justify-between">
                        <div>
                            <div class="text-xs text-slate-500">Tiket Dibeli</div>
                            <div class="text-2xl font-extrabold text-ink mt-1">{{ $ticketsBought }}</div>
                        </div>
                        <div class="h-10 w-10 rounded-2xl bg-slate-50 border border-slate-200 flex items-center justify-center text-slate-700">
                            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                <path d="M4 7a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v2a2 2 0 0 0 0 4v2a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2v-2a2 2 0 0 0 0-4V7Z" stroke="currentColor" stroke-width="2"/>
                                <path d="M12 8v8" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-dasharray="2 2"/>
                            </svg>
                        </div>
                    </div>
                    <div class="text-xs text-slate-500 mt-2">Total tiket dari seluruh pesanan.</div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                {{-- Recent Orders --}}
                <div class="lg:col-span-2 card p-6">
                    <div class="flex items-center justify-between gap-3">
                        <div>
                            <h3 class="text-lg font-extrabold text-ink">Pesanan Terbaru</h3>
                            <p class="text-sm text-slate-500">Ringkasan transaksi terakhir yang Anda lakukan.</p>
                        </div>

                        <a href="{{ route('buyer.orders.index') }}" class="btn-outline">Lihat Semua</a>

                    </div>

                    <div class="mt-4 divide-y divide-slate-100">
                        @forelse($recentOrders as $o)
                            <div class="py-4 flex items-start justify-between gap-4">
                                <div class="min-w-0">
                                    <div class="font-extrabold text-ink truncate">
                                        {{ $o->event?->judul ?? 'Event' }}
                                    </div>

                                    <div class="mt-1 text-xs text-slate-500 flex flex-wrap gap-x-2 gap-y-1">
                                        <span>{{ optional($o->order_date)->format('d M Y H:i') }}</span>
                                        <span>•</span>
                                        <span>{{ (int)($o->details_count ?? $o->details?->count() ?? 0) }} item</span>

                                        @if($o->event?->lokasi)
                                            <span>•</span>
                                            <span class="truncate max-w-[260px]">{{ $o->event->lokasi }}</span>
                                        @endif
                                    </div>
                                </div>

                                <div class="text-right shrink-0">
                                    <div class="font-extrabold text-ink">
                                        Rp {{ number_format((int)$o->total_price, 0, ',', '.') }}
                                    </div>
                                    <a href="{{ route('buyer.orders.show', $o) }}"
                                       class="text-xs text-brand-700 hover:underline font-semibold">
                                        Detail →
                                    </a>
                                </div>
                            </div>
                        @empty
                            <div class="py-10 text-center">
                                <div class="mx-auto h-12 w-12 rounded-2xl bg-slate-50 border border-slate-200 flex items-center justify-center text-slate-600">
                                    <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                        <path d="M7 7h14l-1.2 7.2a3 3 0 0 1-3 2.5H10.2a3 3 0 0 1-3-2.5L6 4H3" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                                    </svg>
                                </div>
                                <div class="mt-3 font-extrabold text-ink">Belum ada transaksi</div>
                                <div class="text-sm text-slate-500 mt-1">
                                    Mulai jelajahi event dan lakukan pembelian tiket untuk melihat riwayat di sini.
                                </div>
                                <div class="mt-4">
                                    <a href="{{ route('public.events.index') }}" class="btn-primary">Jelajahi Event</a>
                                </div>
                            </div>
                        @endforelse
                    </div>
                </div>

                {{-- Quick actions --}}
                <div class="card p-6">
                    <h3 class="text-lg font-extrabold text-ink">Akses Cepat</h3>
                    <p class="text-sm text-slate-500">Shortcut fitur penting untuk pengguna.</p>

                    <div class="mt-4 space-y-3">
                        <a href="{{ route('public.events.index') }}" class="btn-primary w-full justify-center">
                            Explore Events
                        </a>
                        <a href="{{ route('buyer.orders.index') }}" class="btn-ghost w-full justify-center">
                            Riwayat Orders
                        </a>
                        <a href="{{ route('profile.edit') }}" class="btn-ghost w-full justify-center">
                            Edit Profil
                        </a>
                    </div>

                    <div class="mt-5 rounded-xl border border-slate-200 bg-white/60 p-4">
                        <div class="text-xs text-slate-500">Tips</div>
                        <div class="text-sm text-ink font-semibold mt-1">
                            Gunakan filter kategori di halaman Events untuk mencari event lebih cepat.
                        </div>
                    </div>
                </div>
            </div>

            {{-- Upcoming --}}
            <div class="card p-6">
                <div class="flex items-center justify-between gap-3">
                    <div>
                        <h3 class="text-lg font-extrabold text-ink">Event Mendatang</h3>
                        <p class="text-sm text-slate-500">Daftar event yang sudah Anda pesan dan masih akan berlangsung.</p>
                    </div>
                </div>

                <div class="mt-4 divide-y divide-slate-100">
                    @forelse($upcomingOrders as $o)
                        <a href="{{ route('buyer.orders.show', $o) }}"
                           class="block py-4 rounded-xl px-3 -mx-3 hover:bg-white/60 transition">
                            <div class="flex items-start justify-between gap-4">
                                <div class="min-w-0">
                                    <div class="font-extrabold text-ink truncate">
                                        {{ $o->event?->judul ?? 'Event' }}
                                    </div>

                                    <div class="mt-1 text-xs text-slate-500 flex flex-wrap gap-x-2 gap-y-1">
                                        <span>{{ optional($o->event?->waktu)->format('d M Y H:i') }}</span>
                                        @if($o->event?->lokasi)
                                            <span>•</span>
                                            <span class="truncate max-w-[420px]">{{ $o->event->lokasi }}</span>
                                        @endif
                                        <span>•</span>
                                        <span class="font-semibold text-ink/80">
                                            Rp {{ number_format((int)$o->total_price, 0, ',', '.') }}
                                        </span>
                                    </div>
                                </div>

                                <div class="shrink-0 text-xs font-semibold text-brand-700">
                                    Lihat →
                                </div>
                            </div>
                        </a>
                    @empty
                        <div class="py-6 text-sm text-slate-500">
                            Belum ada event mendatang. Silakan lakukan pembelian tiket pada event yang tersedia.
                        </div>
                    @endforelse
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
