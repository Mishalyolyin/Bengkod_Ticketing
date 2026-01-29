<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div>
                <h2 class="font-extrabold text-xl text-ink">Admin Dashboard</h2>
                <!-- <p class="text-sm text-slate-500">Ringkasan cepat TIXORA. Biar admin nggak kerja pakai feeling.</p> -->
            </div>
            <div class="flex gap-2">
                <a href="{{ route('admin.kategori.index') }}" class="btn-primary">📁 Kelola Kategori</a>
                <a href="{{ route('home') }}" class="btn-ghost">↩ Public Home</a>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 space-y-6">

            {{-- Stats --}}
            <div class="grid sm:grid-cols-2 lg:grid-cols-5 gap-4">
                <div class="card p-5 bg-white/70">
                    <div class="text-xs text-slate-500">Kategori</div>
                    <div class="text-2xl font-extrabold">{{ $stats['kategori'] }}</div>
                </div>
                <div class="card p-5 bg-white/70">
                    <div class="text-xs text-slate-500">Event</div>
                    <div class="text-2xl font-extrabold">{{ $stats['event'] }}</div>
                </div>
                <div class="card p-5 bg-white/70">
                    <div class="text-xs text-slate-500">Tiket</div>
                    <div class="text-2xl font-extrabold">{{ $stats['tiket'] }}</div>
                </div>
                <div class="card p-5 bg-white/70">
                    <div class="text-xs text-slate-500">Order</div>
                    <div class="text-2xl font-extrabold">{{ $stats['order'] }}</div>
                </div>
                <div class="card p-5 bg-white/70">
                    <div class="text-xs text-slate-500">Revenue</div>
                    <div class="text-2xl font-extrabold">Rp {{ number_format($stats['revenue'], 0, ',', '.') }}</div>
                </div>
            </div>

            <div class="grid lg:grid-cols-2 gap-6">
                {{-- Latest Events --}}
                <div class="card p-6">
                    <div class="flex items-center justify-between">
                        <h3 class="font-extrabold text-lg">Event Terbaru</h3>
                        <span class="badge-soft">DB Live</span>
                    </div>

                    <div class="mt-4 space-y-3">
                        @forelse($latestEvents as $e)
                            <div class="card p-4 bg-white/60 flex items-start justify-between gap-3">
                                <div>
                                    <div class="font-extrabold">{{ $e->judul }}</div>
                                    <div class="text-sm text-slate-500">
                                        {{ $e->kategori->nama ?? 'Kategori' }} • {{ $e->lokasi ?? '-' }} ({{ $e->kota }})
                                    </div>
                                </div>
                                <div class="text-xs text-slate-500 text-right">
                                    {{ $e->waktu?->format('d M Y') ?? '-' }}<br>
                                    {{ $e->waktu?->format('H:i') ?? '' }}
                                </div>
                            </div>
                        @empty
                            <div class="text-slate-500">Belum ada event.</div>
                        @endforelse
                    </div>
                </div>

                {{-- Latest Orders --}}
                <div class="card p-6">
                    <div class="flex items-center justify-between">
                        <h3 class="font-extrabold text-lg">Transaksi Terbaru</h3>
                        <span class="badge-soft">Orders</span>
                    </div>

                    <div class="mt-4 space-y-3">
                        @forelse($latestOrders as $o)
                            <div class="card p-4 bg-white/60 flex items-start justify-between gap-3">
                                <div>
                                    <div class="font-extrabold">{{ $o->user->name ?? 'User' }}</div>
                                    <div class="text-sm text-slate-500">{{ $o->event->judul ?? 'Event' }}</div>
                                </div>
                                <div class="text-right">
                                    <div class="font-extrabold">Rp {{ number_format($o->total_price ?? 0, 0, ',', '.') }}</div>
                                    <div class="text-xs text-slate-500">{{ $o->order_date?->format('d M Y H:i') ?? '-' }}</div>
                                </div>
                            </div>
                        @empty
                            <div class="text-slate-500">Belum ada transaksi.</div>
                        @endforelse
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
