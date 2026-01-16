{{-- resources/views/public/home.blade.php --}}
<x-guest-layout>
    @php
        // ambil featured event dari list yang ada (paling dekat waktunya)
        $featured = $events->first();
    @endphp

    {{-- HERO --}}
    <section class="grid grid-cols-1 lg:grid-cols-2 gap-8 items-start">
        <div>
            <div class="inline-flex items-center gap-2 text-xs text-slate-500 mb-3">
                <span class="badge-soft">Event terbaru & tiket resmi</span>
            </div>

            <h1 class="text-4xl md:text-5xl font-extrabold text-ink leading-tight">
                Cari eventnya, <br>
                amankan <span class="text-brand-700">tiketnya</span>.
            </h1>

            <p class="mt-4 text-slate-600 max-w-xl">
                Dari konser sampai seminar—semua dalam satu tempat. Checkout cepat, stok terpantau,
                dan riwayat pembelian jelas.
            </p>

            {{-- SEARCH: FIX beneran ke route events --}}
            <div class="mt-6 card p-4">
                <form action="{{ route('public.events.index') }}" method="GET" class="flex flex-col sm:flex-row gap-2">
                    <input
                        type="text"
                        name="q"
                        value="{{ request('q') }}"
                        class="input"
                        placeholder="Cari event… (judul / lokasi)"
                    />
                    <button type="submit" class="btn-primary">
                        🔎 Cari Event
                    </button>
                </form>

                {{-- chips kategori: FIX jadi link --}}
                <div class="mt-3 flex flex-wrap gap-2">
                    @foreach (['Music','Seminar','Sport','Workshop','Festival'] as $chip)
                        <a href="{{ route('public.events.index', ['q' => $chip]) }}"
                           class="badge-soft hover:opacity-90 transition">
                            {{ $chip }}
                        </a>
                    @endforeach
                </div>

                {{-- kotak-kotak info: FIX jadi link juga --}}
                <div class="mt-4 grid grid-cols-1 sm:grid-cols-3 gap-3">
                    <a href="{{ route('public.events.index') }}" class="card p-4 hover:shadow-md transition">
                        <div class="text-xs text-slate-500">Tiket resmi</div>
                        <div class="font-bold text-ink">Aman & terverifikasi</div>
                    </a>

                    <a href="{{ route('public.events.index') }}" class="card p-4 hover:shadow-md transition">
                        <div class="text-xs text-slate-500">Checkout</div>
                        <div class="font-bold text-ink">Cepat, anti ribet</div>
                    </a>

                    @auth
                        <a href="{{ route('buyer.orders.index') }}" class="card p-4 hover:shadow-md transition">
                            <div class="text-xs text-slate-500">Riwayat</div>
                            <div class="font-bold text-ink">Transaksi tercatat</div>
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="card p-4 hover:shadow-md transition">
                            <div class="text-xs text-slate-500">Riwayat</div>
                            <div class="font-bold text-ink">Login dulu ya 😭</div>
                        </a>
                    @endauth
                </div>
            </div>

            <div class="mt-5 flex items-center gap-3">
                <a href="{{ route('public.events.index') }}" class="btn-primary">
                    🎟️ Jelajahi Event
                </a>

                @auth
                    @if((auth()->user()->role ?? null) === 'admin')
                        <a href="{{ route('admin.dashboard') }}" class="btn-ghost">
                            🛠️ Admin Panel
                        </a>
                    @endif
                @endauth
            </div>
        </div>

        {{-- FEATURED CARD --}}
        <div class="card p-6">
            <div class="flex items-center justify-between">
                <div class="text-xs text-slate-500">Featured Event</div>
                <span class="badge-soft">Featured</span>
            </div>

            @if($featured)
                <div class="mt-3">
                    <div class="text-xl font-extrabold text-ink">{{ $featured->judul }}</div>
                    <div class="text-sm text-slate-500">
                        {{ $featured->kategori?->nama ?? '-' }} • {{ $featured->lokasi }}
                    </div>
                </div>

                <div class="mt-4 grid grid-cols-2 gap-3">
                    <div class="card p-4">
                        <div class="text-xs text-slate-500">Lokasi</div>
                        <div class="font-bold text-ink">{{ $featured->lokasi }}</div>
                    </div>
                    <div class="card p-4">
                        <div class="text-xs text-slate-500">Waktu</div>
                        <div class="font-bold text-ink">
                            {{ optional($featured->waktu)->format('D, d M Y') }}
                        </div>
                        <div class="text-xs text-slate-500 mt-1">
                            {{ optional($featured->waktu)->format('H:i') }}
                        </div>
                    </div>
                </div>

                <div class="mt-4 card p-4">
                    <div class="text-xs text-slate-500">Tentang event</div>
                    <div class="text-sm text-ink mt-1 line-clamp-3">
                        {{ $featured->deskripsi }}
                    </div>
                </div>

                {{-- FIX: Pesan Tiket -> ke detail dulu --}}
                <a href="{{ route('public.events.show', $featured) }}" class="btn-primary w-full mt-4">
                    🧾 Pesan Tiket
                </a>

                @php
                    $min = $featured->tikets->min('harga');
                    $stokTotal = (int) $featured->tikets->sum('stok');
                @endphp

                <div class="mt-3 flex items-center justify-between text-xs text-slate-500">
                    <div>Mulai dari <span class="font-bold text-ink">Rp {{ number_format($min ?? 0, 0, ',', '.') }}</span></div>
                    <div>Stok {{ $stokTotal }}</div>
                </div>
            @else
                <div class="mt-4 text-slate-500 text-sm">Belum ada event.</div>
            @endif
        </div>
    </section>

    {{-- POPULAR --}}
    <section id="popular" class="mt-10">
        <div class="flex items-center justify-between">
            <h2 class="text-xl font-extrabold text-ink">Event Populer</h2>
            <a href="{{ route('public.events.index') }}" class="text-sm text-brand-700 hover:underline">
                Lihat semua →
            </a>
        </div>

        <div class="mt-5 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($events as $event)
                @php
                    $minPrice = $event->tikets->min('harga');
                    $stokTotal = (int) $event->tikets->sum('stok');
                @endphp

                <div class="card overflow-hidden">
                    {{-- banner --}}
                    <div class="h-40 bg-slate-100">
                        @if($event->gambar)
                            <img src="{{ asset('storage/'.$event->gambar) }}" class="w-full h-40 object-cover" alt="poster">
                        @endif
                    </div>

                    <div class="p-5">
                        <div class="flex items-center justify-between">
                            <span class="badge-soft">{{ $event->kategori?->nama ?? '-' }}</span>
                            <div class="text-xs text-slate-500">Stok {{ $stokTotal }}</div>
                        </div>

                        <div class="mt-3 text-lg font-extrabold text-ink">
                            {{ $event->judul }}
                        </div>

                        <p class="mt-1 text-sm text-slate-600 line-clamp-2">
                            {{ $event->deskripsi }}
                        </p>

                        <div class="mt-4 grid grid-cols-2 gap-3">
                            <div class="card p-3">
                                <div class="text-xs text-slate-500">Lokasi</div>
                                <div class="font-bold text-ink">{{ $event->lokasi }}</div>
                            </div>
                            <div class="card p-3">
                                <div class="text-xs text-slate-500">Tanggal</div>
                                <div class="font-bold text-ink">{{ optional($event->waktu)->format('d M Y') }}</div>
                            </div>
                        </div>

                        <div class="mt-4 flex items-end justify-between">
                            <div>
                                <div class="text-xs text-slate-500">Mulai dari</div>
                                <div class="font-extrabold text-ink">
                                    Rp {{ number_format($minPrice ?? 0, 0, ',', '.') }}
                                </div>
                            </div>

                            {{-- FIX: Lihat Detail -> link --}}
                            <a href="{{ route('public.events.show', $event) }}" class="btn-primary">
                                Lihat Detail
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </section>
</x-guest-layout>
